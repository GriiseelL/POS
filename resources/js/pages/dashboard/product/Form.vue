<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Category, Product, Role } from "@/types";
import ApiService from "@/core/services/ApiService";
import { useCategory } from "@/services/useCategory";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

// Ganti dari user ke product dan tipe User ke Product
const product = ref<Product>({} as Product);
const formRef = ref();
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const photo = ref<any>([]);

const formSchema = Yup.object().shape({
    name: Yup.string().required("Nama harus diisi"),
    id_category: Yup.string().required("Kategori harus diisi"),
    price: Yup.string().required("Harga harus diisi"),
    stock: Yup.string().required("Stok harus diisi"),
});

function getEdit() {
    block(document.getElementById("form-product"));
    ApiService.get("product/product", props.selected)
        .then(({ data }) => {
            console.log(data);
            product.value = data.product;
            photo.value = data.product.photo
                ? ["/storage/" + data.product.photo]
                : [];
            console.log("Kategori Produk:", product.value.id_category);
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-product"));
        });
    console.log("Selected ID:", props.selected);
}

function submit() {
    const formData = new FormData();
    formData.append("name", product.value.name);
    formData.append("id_category", product.value.id_category);
    formData.append("price", product.value.price);
    formData.append("stock", product.value.stock);

    if (photo.value.length) {
        formData.append("photo", photo.value[0].file);
    }
    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-product"));
    axios({
        method: "post",
        url: props.selected
            ? `/product/product/${props.selected}`
            : "/product/items/store",
        data: formData,
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(() => {
            emit("close");
            emit("refresh");
            toast.success("Data berhasil disimpan");
            formRef.value.resetForm();
        })
        .catch((err: any) => {
            formRef.value.setErrors(err.response.data.errors);
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-product"));
        });
}

const category = useCategory();
const categories = computed(() =>
    category.data.value?.map((item: Category) => ({
        id: item.id,
        text: item.name,
    }))
);

onMounted(() => {
    if (props.selected) {
        getEdit();
    }
});

watch(
    () => props.selected,
    () => {
        if (props.selected) {
            getEdit();
        }
    }
);
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submit"
        :validation-schema="formSchema"
        id="form-product"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">
                {{ props.selected ? "Edit" : "Tambah" }} Product
            </h2>
            <button
                type="button"
                class="btn btn-sm btn-light-danger ms-auto"
                @click="emit('close')"
            >
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- Input Nama -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nama
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="name"
                            autocomplete="off"
                            v-model="product.name"
                            placeholder="Masukkan Nama"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Input Nama -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Stok
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="stock"
                            autocomplete="off"
                            v-model="product.stock"
                            placeholder="Masukkan Stok"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="stock" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Input Nama -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Price
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="price"
                            autocomplete="off"
                            v-model="product.price"
                            placeholder="Masukkan Nama"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="price" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Input Category -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Category
                        </label>
                        <Field name="id_category" v-model="product.id_category">
                            <select2
                                placeholder="Pilih Kategori"
                                class="form-select-solid"
                                :options="categories"
                                v-model="product.id_category"
                            />
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="id_category" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6"
                            >Photo product</label
                        >
                        <file-upload
                            :files="photo"
                            :accepted-file-types="fileTypes"
                            v-on:updatefiles="(file) => (photo = file)"
                        ></file-upload>
                        <ErrorMessage name="photo" class="text-danger" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>
