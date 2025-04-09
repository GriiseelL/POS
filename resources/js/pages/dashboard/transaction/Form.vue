<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Product, Transaction} from "@/types";
import ApiService from "@/core/services/ApiService";
import { useProduct } from "@/services/useProduct";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

// Ganti dari user ke product dan tipe User ke Product
const transaction = ref<Transaction>({} as Transaction);
const formRef = ref();

const formSchema = Yup.object().shape({
    id_product: Yup.string().required("product harus diisi"),
    price: Yup.string().required("Harga harus diisi"),
    sub_total: Yup.string().required("subTotal harus diisi"),
    total: Yup.string().required("Total harus diisi"),
    quantity: Yup.string().required("Quantity harus diisi"),
});

function getEdit() {
    block(document.getElementById("form-product"));
    ApiService.get("transaction", props.selected)
        .then(({ data }) => {
            console.log(data);
            transaction.value = data.transaction;
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
    formData.append("id_product", transaction.value.id_product);
    formData.append("price", transaction.value.price);
    formData.append("total", transaction.value.total);
    formData.append("sub_total", transaction.value.sub_total);
    formData.append("quantity", transaction.value.quantity);

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-product"));
    axios({
        method: "post",
        url: props.selected
            ? `/transaction/${props.selected}`
            : "/transaction/store",
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

const product = useProduct();
const products = computed(() =>
    product.data.value?.map((item: Product) => ({
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
                    <!-- Input Category -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Product
                        </label>
                        <Field name="id_product" v-model="transaction.id_product">
                            <select2
                                placeholder="Pilih product"
                                class="form-select-solid"
                                :options="products"
                                v-model="transaction.id_product"
                            />
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="id_product" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Input Nama -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Quantity
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="quantity"
                            autocomplete="off"
                            v-model="transaction.quantity"
                            placeholder="Masukkan quantity"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="quantity" />
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
                            v-model="transaction.price"
                            placeholder="Masukkan harga"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="price" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Input Nama -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            subTotal
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="sub_total"
                            autocomplete="off"
                            v-model="transaction.sub_total"
                            placeholder="Masukkan SubTotal"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="sub_total" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Input Nama -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Total
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="total"
                            autocomplete="off"
                            v-model="transaction.total"
                            placeholder="Masukkan total harga"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="total" />
                            </div>
                        </div>
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

