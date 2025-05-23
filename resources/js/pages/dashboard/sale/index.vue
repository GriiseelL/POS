<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import Swal from "sweetalert2";
import * as Yup from "yup";
import axios from "@/libs/axios";
import type { Category } from "@/types";
import { useCategory } from "@/services/useCategory";
import api from "@/core/api";
import printJS from "print-js"

const categories = ref([]);
const fetchDataCategory = async () => {
    //fetch data
    await api
        .get("/api/product/category")

        .then((response) => {
            console.log(response.data.data);
            //set response data to state "posts"
            categories.value = response.data.data;
        });
};

const products = ref([]);
const fetchProducts = async (id_category) => {
    try {
        const response = await api.get(`/api/product/items`, {
            params: { id_category },
        });

        console.log("Data produk yang diterima:", response.data); // Debugging

        const availableProducts = response.data.data.filter(product => product.stock > 0);

        products.value = availableProducts;
    } catch (error) {
        console.error("Gagal mengambil data produk", error);
    }
};

let currentOrder = ref([]);

const fetchDataProduct = async (id) => {
    try {
        const response = await api.get(`api/product/item/` + id);
        const product = response.data.product;

        // ❗ CEK STOK HABIS
        if (product.stock <= 0) {
            Swal.fire({
                icon: "warning",
                title: "Stok Habis",
                text: "Produk ini tidak bisa dipilih karena stoknya habis!",
            });
            return;
        }

        // Cek apakah produk sudah ada di currentOrder
        const state = currentOrder.value.some((item) => item.id === id);

        if (state) {
            currentOrder.value = currentOrder.value.map((item) => {
                if (item.id === id) {
                    let newQuantity = item.quantity + 1;

                    if (newQuantity > product.stock) {
                        Swal.fire({
                            icon: "warning",
                            title: "Stok Habis",
                            text: "Jumlah melebihi stok tersedia!",
                        });
                        return item;
                    }

                    return {
                        ...item,
                        quantity: newQuantity,
                    };
                }
                return item;
            });
        } else {
            product.quantity = 1;
            currentOrder.value.push(product);
        }
    } catch (error) {
        console.error("Gagal mengambil data produk:", error);
    }
};

// const delete = ref([]);
const decreaseQty = (id) => {
    currentOrder.value = currentOrder.value.flatMap((item) => {
        if (item.id === id) {
            if (item.quantity > 1) {
                return {
                    ...item,
                    quantity: item.quantity - 1,
                };
            } else {
                // quantity = 1, jadi dihapus
                return []; // hapus item
            }
        }
        return item; // item lain tetap
    });
};

const subtotal = computed(() => {
    return currentOrder.value.reduce((total, item) => {
        return total + item.price * item.quantity;
    }, 0);
});

const tax = computed(() => {
    return subtotal.value * 0.12;
});

const total = computed(() => {
    return subtotal.value + tax.value;
});

const generateTransactionCode = () => {
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 10).replace(/-/g, ""); // YYYYMMDD
    const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak
    return `TRX${dateStr}${random}`;
};

const payWithXendit = async (method = "Debit") => {
    try {
        const code = generateTransactionCode();
        // 1️⃣ Tabel ringkasan
        const itemsHtml = currentOrder.value
            .map(
                (item) => `
        <tr>
          <td>${item.name}</td>
          <td>x${item.quantity}</td>
          <td>Rp${(item.price * item.quantity).toLocaleString()}</td>
        </tr>
      `
            )
            .join("");


        const htmlContent = `
      <table style="width:100%;text-align:left;margin-bottom:10px;">
        <thead>
          <tr>
            <th>Barang</th><th>Qty</th><th>Total</th>
          </tr>
        </thead>
        <tbody>${itemsHtml}</tbody>
        <tfoot>
          <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>Rp${(
                subtotal.value + tax.value
            ).toLocaleString()}</strong></td>
          </tr>
        </tfoot>
      </table>
    `;

        // 2️⃣ Konfirmasi
        const { isConfirmed } = await Swal.fire({
            title: "Konfirmasi Transaksi",
            html: htmlContent,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Buat Invoice!",
            cancelButtonText: "Batal",
            width: 600,
        });
        if (!isConfirmed) return;

        // 3️⃣ Kirim ke backend
        const body = {
            metode_pembayaran: method,
            items: currentOrder.value.map((item) => ({
                id_product: item.id,
                price: item.price,
                quantity: item.quantity,
                // subtotal: item.price * item.quantity,
            })),
            // jika butuh redirect custom setelah bayar:
            success_redirect_url: `${window.location.origin}/dashboard/transaction?code=${code}`, // sekarang code ada
        };

        const res = await api.post("/api/xendit/store", body);
        console.log("Xendit response:", res.data);

        if (!res.data.success) {
            throw new Error(res.data.message || "Failed to create invoice");
        }

        // 4️⃣ Langsung redirect sekali ke URL Xendit
        window.location.href = res.data.payment_url;
    } catch (error) {
        console.error(error);
        Swal.fire(
            "Gagal",
            error.message || "Tidak bisa membuat invoice",
            "error"
        );
    }
};

// import Swal from "sweetalert2";

const cash = async (method = "Cash") => {
    try {
        const code = generateTransactionCode();

        // ✅ Tampilkan tabel barang
        const itemsHtml = currentOrder.value
            .map((item) => {
                return `
                <tr>
                    <td>${item.name}</td>
                    <td>x${item.quantity}</td>
                    <td>Rp${(item.price * item.quantity).toLocaleString()}</td>
                </tr>
            `;
            })
            .join("");

        const htmlContent = `
            <table style="width:100%;text-align:left;margin-bottom:10px;">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong>Rp${(
                            subtotal.value + tax.value
                        ).toLocaleString()}</strong></td>
                    </tr>
                </tfoot>
            </table>
        `;

        const result = await Swal.fire({
            title: "Konfirmasi Transaksi",
            html: htmlContent,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal",
            focusConfirm: false,
            width: 600,
        });

        if (result.isConfirmed) {
            // 🔥 Perbaiki payload: langsung bentuk array of objek
            const payload = {
                metode_pembayaran: method,
                items: currentOrder.value.map((item) => ({
                    id_product: item.id,
                    price: item.price,
                    quantity: item.quantity,
                    subtotal: item.price * item.quantity,
                })),
            };

            const response = await api.post("/api/transaction/store", payload);
            console.log("Transaksi berhasil disimpan:", response.data);

            const receiptData = {
                transaction_code: code,
                items: currentOrder.value,
                subtotal: subtotal.value,
                tax: tax.value,
                details: currentOrder.value // Pastikan data details ada
                // total: total.value,
            };

            const { data: { data: htmlStr } } = await api.post('/api/xendit/struk/cash', receiptData);

            printJS({
                printable: htmlStr,
                type: 'raw-html',
            style: `
        body { font-family: 'Courier New', monospace; font-size: 14px; }
        table { width: 100%; }
        td, th { padding: 4px; text-align: left; }
    `,
            });

            currentOrder.value = [];

            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Transaksi berhasil disimpan!",
                confirmButtonText: "Oke",
            });
        }
    } catch (error) {
        console.error("Gagal menyimpan transaksi:", error);

        Swal.fire({
            icon: "error",
            title: "Gagal",
            text:
                error.response?.data?.message ||
                error.message ||
                "Terjadi kesalahan saat menyimpan transaksi.",
        });
    }
};

const formatRupiah = (num: number) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(num);

onMounted(() => {
    //call method "fetchDataPosts"
    fetchDataCategory();
});
</script>

<template>
    <div class="container-fluid py-4 bg-body">
        <div class="row">
            <!-- Kategori -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold">Categories</h5>
                        <button
                            @click="fetchProducts(category.id)"
                            class="btn btn-outline-secondary w-100 my-1 text-start"
                            v-for="category in categories"
                            :key="category.id"
                        >
                            {{ category.name }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div
                            class="d-flex justify-content-between align-items-center mb-2"
                        >
                            <h5 class="fw-bold">Product List</h5>
                        </div>
                        <div class="row g-2">
                            <template v-if="products.length > 0">
                            <div
                                class="col-5 col-md-5"
                                v-for="product in products"
                                :key="product.id"
                            >
                                <div class="card shadow-sm">
                                    <button
                                        @click="fetchDataProduct(product.id)"
                                        class="btn btn-outline-secondary w-100 my-1 text-start text-center"
                                    >
                                        <!-- <h6 class="card-title"> -->
                                        <!-- {{ product.name }} -->
                                        <!-- </h6> -->
                                        <!-- <p class="text-success fw-bold">
                                            ${{ product.price }}
                                        </p> -->

                                        <img
                                            v-if="product.photo"
                                            :src="`/storage/${product.photo}`"
                                            alt="Foto Produk"
                                            style="
                                                width: 100%;
                                                max-height: 200px;
                                                object-fit: cover;
                                                border-radius: 8px;
                                            "
                                        />
                                        <div class="mt-2">
                                            <strong>{{ product.name }}</strong>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            </template>
                            <template v-else>
                                <div class="col-12 text-center text-muted py-4">
                                    <h5>Semua produk dalam kategori ini habis.</h5>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold">Current Order</h5>
                        <div
                            class="border-bottom pb-2 mb-2"
                            v-for="item in currentOrder"
                            :key="item.id"
                        >
                            <div class="d-flex justify-content-between">
                                <i
                                    class="la la-trash fs-2"
                                    style="color: red; cursor: pointer"
                                    @click="decreaseQty(item.id)"
                                ></i>

                                <span
                                    >{{ item.name }} x{{ item.quantity }}</span
                                >
                                <span class="fw-bold">{{
                                    formatRupiah(item.price)
                                }}</span>
                            </div>
                        </div>
                        <div class="p-2 bg-light border rounded">
                            <p>
                                Subtotal:
                                <strong>{{ formatRupiah(subtotal) }}</strong>
                            </p>
                            <p>
                                Tax (12%):
                                <strong>{{ formatRupiah(tax) }}</strong>
                            </p>
                            <p class="fw-bold text-success">
                                Total: {{ formatRupiah(total) }}
                            </p>
                        </div>
                        <h6 class="mt-3 fw-bold">Payment Status</h6>
                        <button
                            class="btn btn-outline-secondary w-100 my-1"
                            @click="cash(Cash)"
                        >
                            💵 Cash
                        </button>
                        <button
                            class="btn btn-outline-secondary w-100 my-1"
                            @click="payWithXendit(Debit)"
                        >
                            📱 Debit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Bootstrap Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .bg-body {
        background-color: #212529 !important;
        color: #f8f9fa !important;
    }
    .card {
        background-color: #2c2f33 !important;
        color: #f8f9fa !important;
    }
    .btn-outline-dark {
        border-color: #f8f9fa;
        color: #f8f9fa;
    }
    .btn-outline-dark:hover {
        background-color: #f8f9fa;
        color: #212529;
    }
}
</style>
