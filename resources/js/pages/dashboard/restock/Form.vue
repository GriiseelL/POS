<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import * as Yup from "yup";
import { Field, ErrorMessage } from "vee-validate";
import { toast } from "vue3-toastify";
import axios from "@/libs/axios";
import ApiService from "@/core/services/ApiService";
import { useProduct } from "@/services/useProduct";

const props = defineProps({
    selected: { type: String, default: null },
});
const emit = defineEmits(["close", "refresh"]);

const riwayat = ref({
    id_product: "",
    quantity: "",
    tipe: "masuk", // atau "keluar"
});
const formRef = ref();
const formSchema = Yup.object().shape({
    id_product: Yup.string().required("Produk wajib diisi"),
    quantity: Yup.number().required("quantity stok wajib diisi").min(1),
    tipe: Yup.string().required(),
});

const product = useProduct();
const products = computed(() =>
    product.data.value?.map((item) => ({
        id: item.id,
        text: item.name,
    }))
);

function submit() {
    axios
        .post("/riwayat_stock", riwayat.value)
        .then(() => {
            toast.success("Riwayat stok berhasil disimpan");
            emit("close");
            emit("refresh");
            formRef.value.resetForm();
        })
        .catch((err) => {
            toast.error(err.response?.data?.message || "Gagal menyimpan");
            formRef.value.setErrors(err.response?.data?.errors || {});
        });
}
</script>

<template>
    <VForm
        id="form-riwayat"
        ref="formRef"
        class="form card"
        @submit="submit"
        :validation-schema="formSchema"
    >
        <div class="card-header">
            <h2>{{ props.selected ? "Edit" : "Tambah" }} Riwayat Stok</h2>
            <button class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Batal <i class="la la-times-circle"></i>
            </button>
        </div>

<!--        <div class="col-md-6">-->
            <!-- Input Category -->
            <div class="fv-row mb-7">
                <label class="form-label fw-bold fs-6 required">
                    Product
                </label>
                <Field name="id_product" v-model="riwayat.id_product">
                    <select2
                        placeholder="Pilih Product"
                        class="form-select-solid"
                        :options="products"
                        v-model="riwayat.id_product"
                    />
                </Field>
                <div class="fv-plugins-message-container">
                    <div class="fv-help-block">
                        <ErrorMessage name="id_category" />
                    </div>
                </div>
            </div>
<!--        </div>-->

<!--        <div class="col-md-6">-->
        <div class="mb-7">
                <label class="form-label required">quantity Stok</label>
                <Field
                    name="quantity"
                    type="number"
                    class="form-control"
                    v-model="riwayat.quantity"
                    placeholder="Masukkan quantity"
                /> 
                <ErrorMessage name="stock" class="text-danger" />
            </div>
<!--            </div>-->

            <div class="mb-4">
                <label class="form-label required">Tipe</label>
                <Field name="tipe" as="select" class="form-select" v-model="riwayat.tipe">
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </Field>
                <ErrorMessage name="tipe" class="text-danger" />
            </div>

        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">Simpan</button>
        </div>
    </VForm>
</template>


<style>
/* Untuk Chrome, Safari, Edge */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: auto;
    margin: 0;
}

/* Untuk Firefox */
input[type="number"] {
    -moz-appearance: number-input;
}
</style>
