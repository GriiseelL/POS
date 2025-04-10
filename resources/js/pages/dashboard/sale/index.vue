<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import type { Category } from "@/types";
import { useCategory } from "@/services/useCategory";
import api from "@/core/api";

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
        products.value = response.data.data;
    } catch (error) {
        console.error("Gagal mengambil data produk", error);
    }
};

let currentOrder = ref([]);

const fetchDataProduct = async (id) => {
    // alert(id);
    // return;
    try {
        const response = await api.get(`api/product/item/` + id, {
            // params: { id },
        });

        const state = currentOrder.value.some((item) => item.id == id);

        if (state == true) {
            currentOrder.value = currentOrder.value.map((item) => {
                if (item.id === id) {
                    let newQuantity = item.quantity + 1;
                    return {
                        ...item,
                        quantity: newQuantity,
                    };
                }
                return item;
            });
        } else {
            response.data.product.quantity = 1;
            currentOrder.value.push(response.data.product);
        }
        // console.log("munculini:", [response.data.product]);
        // console.log(currentOrder.value);
    } catch (error) {
        console.error("gagal", error);
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

const cash = async (method = "Cash") => {
    try {
      const payload = currentOrder.value.map(item => ({
    id_product: item.id,
    quantity: item.quantity,
    price: item.price,
    sub_total: item.price * item.quantity,
    total: subtotal.value + tax.value, // bisa disesuaikan
}));

        const response = await api.post("/api/transaction/store", payload);
        console.log("Transaksi berhasil disimpan:", response.data);

        currentOrder.value = [];
    } catch (error) {
        console.error("Gagal menyimpan transaksi:", error);
    }
};


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
                        <h5 class="fw-bold">📁 Categories</h5>
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
                            <h5 class="fw-bold">🛒 Product List</h5>
                            <button class="btn btn-primary">
                                + Add Product
                            </button>
                        </div>
                        <div class="row g-2">
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold">📋 Current Order</h5>
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
                                <span class="fw-bold">${{ item.price }}</span>
                            </div>
                        </div>
                        <div class="p-2 bg-light border rounded">
                            <p>
                                Subtotal: <strong>${{ subtotal }}</strong>
                            </p>
                            <p>
                                Tax (12%): <strong>${{ tax }}</strong>
                            </p>
                            <p class="fw-bold text-success">
                                Total: ${{ total }}
                            </p>
                        </div>
                        <h6 class="mt-3 fw-bold">💳 Payment Method</h6>
                        <button class="btn btn-outline-secondary w-100 my-1" @click="cash(Cash)">
                            💵 Cash
                        </button>
                        <button class="btn btn-outline-secondary w-100 my-1">
                            💳 Card
                        </button>
                        <button class="btn btn-outline-secondary w-100 my-1">
                            📱 E-Wallet
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
