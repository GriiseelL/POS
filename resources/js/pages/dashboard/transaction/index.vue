<script setup lang="ts">
import { createColumnHelper } from "@tanstack/vue-table";
import Form from "./Form.vue";
import Swal from "sweetalert2";
import { h, ref, onMounted } from "vue";
import type { Transaction } from "@/types";
import axios from "axios";

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
                        onClick: () =>
                            fetchDetail(cell.row.original.transaction_code),
                    },
                    h("i", { class: "la la-eye fs-2" })
                ),
            ]),
    }),
];

const fetchDetail = async (transaction_code: string) => {
    try {
        const { data } = await axios.get(
            `/transaction/detail/${transaction_code}`
        );

        console.log("📦 RAW response:", data);

        const transaksiList = data.transactions || data.data?.transactions;

        if (!Array.isArray(transaksiList)) {
            throw new Error("Format data transaksi tidak sesuai.");
        }

        transactionDetails.value = transaksiList;

        const contentHtml = transaksiList
            .map(
                (item, index) => `
                <div style="text-align: left; margin-bottom: 8px;">
                    <strong>Item ${index + 1}</strong><br/>
                      Nama: ${
                          item.product_name || "Produk tidak ditemukan"
                      }<br/>
                    Jumlah: ${item.quantity}<br/>
                </div>
            `
            )
            .join("");

        Swal.fire({
            title: "Detail Transaksi",
            html: contentHtml || "Tidak ada detail.",
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

onMounted(() => {
    console.log("✅ Komponen TransactionDetail berhasil dimount!");
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
        <!-- <div class="card-header align-items-center d-flex">
      <h2 class="mb-0">List Transaction</h2>
      <button
        type="button"
        class="btn btn-sm btn-primary ms-auto"
        @click="openForm = true"
      >
        Tambah
        <i class="la la-plus"></i>
      </button>
    </div> -->
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
