<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Product } from "@/types";

const column = createColumnHelper<Product>();
const paginateRef = ref<any>(null);
const selected = ref<number>(0);
const openForm = ref<boolean>(false);

const showImageModal = ref(false);
const previewImageUrl = ref("");

const { delete: deleteUser } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
    column.accessor("no", {
        header: "#",
    }),
    column.accessor("name", {
        header: "Nama",
    }),
    column.accessor("category.name", {
        header: "Category",
    }),
    column.accessor("photo", {
        header: "Photo product",
        cell: (cell) => {
            const src = `/storage/${cell.getValue()}`;
            return cell.getValue()
                ? h("img", {
                      src,
                      alt: "Foto Produk",
                      style: "width: 80px; height: 50px; border-radius: 4px; cursor: pointer;",
                      onClick: () => {
                          previewImageUrl.value = src;
                          showImageModal.value = true;
                      },
                  })
                : "Tidak ada foto";
        },
    }),
    column.accessor("price", {
        header: "Price",
    }),
    column.accessor("stock", {
        header: "Stok",
    }),
    column.accessor("id", {
        header: "Action",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => {
                            selected.value = cell.getValue();
                            openForm.value = true;
                        },
                    },
                    h("i", { class: "la la-pencil fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deleteUser(`/product/product/${cell.getValue()}`),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
});
</script>

<template>
    <Form
        :selected="selected"
        @close="openForm = false"
        v-if="openForm"
        @refresh="refresh"
    />

    <div
        v-if="showImageModal"
        @click="showImageModal = false"
        style="
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        "
    >
        <img
            :src="previewImageUrl"
            alt="Preview"
            style="max-width: 90%; max-height: 90%; border-radius: 10px"
        />
    </div>

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">List Product</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-auto"
                v-if="!openForm"
                @click="openForm = true"
            >
                Tambah
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-product"
                url="/product/items"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
