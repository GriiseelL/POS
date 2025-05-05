<script setup lang="ts">
import { createColumnHelper } from "@tanstack/vue-table";
import Form from "./Form.vue";
import Swal from "sweetalert2";
import { h, ref, onMounted } from "vue";
import type { Transaction } from "@/types";
import axios from "axios";
import printJS from "print-js";

// STATE
const showDetail = ref(false);
const transactionDetails = ref<any[]>([]);

// TABLE COLUMN SETUP
const column = createColumnHelper<Transaction>();

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("transaction_code", {
        header: "Transaction Code",
    }),
    column.display({
        id: "action",
        header: "Action",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => fetchDetail(cell.row.original.id),
                    },
                    h("i", { class: "la la-eye fs-2" })
                ),
            ]),
    }),
];

const fetchDetail = async (id_transaksi: string) => {
    console.log(id_transaksi);
    try {
        const { data } = await axios.get(`/transaction/detail/${id_transaksi}`);

        console.log("📦 RAW response:", data);

        const transaksiList = data.transactions || data.data?.transactions;

        if (!Array.isArray(transaksiList)) {
            throw new Error("Format data transaksi tidak sesuai.");
        }

        const formatRupiah = (num: number) =>
            new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
            }).format(num);

        transactionDetails.value = transaksiList;

        const contentHtml = transaksiList
            .map(
                (item, index) => `
                <div style="text-align: left; margin-bottom: 8px;">
                    <strong>Item ${index + 1}</strong><br/>
                    Nama: ${item.product_name || "Produk tidak ditemukan"}<br/>
                    Jumlah: ${item.quantity}<br/>
                    <strong>Harga: ${formatRupiah(
                        item.product_price
                    )}</strong><br/>
                    <strong>Metode Pembayaran: ${
                        item.metode_pembayaran
                    }</strong><br/>
                    <strong>Seller: ${
                        item.seller
                    }</strong><br/>

                </div>
            `
            )
            .join("");

        const pajakPersen = 12;

        const subtotal = transaksiList.reduce((sum, item) => {
            return sum + Number(item.product_price) * Number(item.quantity);
        }, 0);

        const pajak = (subtotal * pajakPersen) / 100;
        const totalTransaksi = subtotal + pajak;

        const finalHtml = `
            ${contentHtml}
            <div style="text-align: left; margin-top: 12px;">
                <strong>Subtotal: ${formatRupiah(subtotal)}</strong><br/>
                <strong>Pajak (${pajakPersen}%): ${formatRupiah(
            pajak
        )}</strong><br/>
                <strong>Total (termasuk pajak): ${formatRupiah(
                    totalTransaksi
                )}</strong><br>
            </div>
        `;

        Swal.fire({
            title: "Detail Transaksi",
            html: finalHtml || "Tidak ada detail.",
            confirmButtonText: "Tutup",
            width: 600,
            customClass: {
                popup: "text-left",
            },
        });
    } catch (err: any) {
        console.error("❌ Gagal ambil data detail:", err);
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text:
                err.response?.data?.message ||
                err.message ||
                "Tidak bisa ambil detail transaksi.",
        });
    }
};


const loading = ref(false);

const handleDownload = () => {
  loading.value = true;

  // Simulasi download (ganti dengan aksi nyata)
  window.location.href = "/transaction/download";

  // Optional: reset loading kalau perlu
  setTimeout(() => {
    loading.value = false;
  }, 3000);
};

onMounted(() => {
    console.log("✅ Komponen TransactionDetail berhasil dimount!");
});

onMounted(async () => {
    // console.log("✅ Komponen TransactionDetail berhasil dimount!");

    const urlParams = new URLSearchParams(window.location.search);
    const code = urlParams.get("code");
    const shouldPrint = urlParams.get("print");

    if (code && shouldPrint === "true") {
        try {
            const trx = await axios.get(`/transaction/${code}`);

            const receiptRes = await axios.post('/xendit/struk', trx.data);

            printJS({
                printable: receiptRes.data,
                type: "raw-html",
                style: `
                    body { font-family: 'Courier New', monospace; font-size: 14px; }
                    table { width: 100%; }
                    td, th { padding: 4px; text-align: left; }
                `,
            });

            // Hapus parameter print dari URL setelah print
            const cleanUrl = window.location.origin + window.location.pathname + `?code=${code}`;
            window.history.replaceState({}, document.title, cleanUrl);

        } catch (err) {
            console.error("❌ Gagal cetak struk otomatis:", err);
            Swal.fire("Gagal", "Tidak bisa cetak struk", "error");
        }
    }
});
</script>

<template>
    <!-- MODAL -->
    <!-- <TransactionDetail
        v-if="showDetail"
        :detailData="transactionDetails"
        @close="showDetail = false"
    /> -->

    <!-- MAIN CONTENT -->
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h2 class="mb-0">List Transaction</h2>
            <button
                tabindex="3"
                @click="handleDownload"
                class="btn btn-lg btn-primary mb-5"
                :disabled="loading"
            >
            <i class="la la-download"></i>
            <span class="indicator-label" v-if="!loading">Download</span>

                <span v-else>
                    <span
                        class="spinner-border spinner-border-sm align-middle ms-2"
                    ></span>
                </span>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-product"
                url="/transaction"
                :columns="columns"
            />
        </div>
    </div>
</template>
