<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Category } from "@/types";
import ApiService from "@/core/services/ApiService";

const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);
const category = ref<Category>({} as Category);
const formRef = ref();

const formSchema = Yup.object().shape({
    category: Yup.string().required("Kategori harus diisi"),
});

function getEdit() {
    block(document.getElementById("form-category"));
    ApiService.get("product/category", props.selected)
        .then(({ data }) => {
            category.value = data.category;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-category"));
        });
}

function submit() {
    const formData = new FormData();
    formData.append("name", category.value.name);

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-category"));
    axios({
        method: "post",
        url: props.selected
            ? `/product/category/${props.selected}`
            : "/product/category/store",
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
            unblock(document.getElementById("form-category"));
        });
}

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
        id="form-category"
        ref="formRef"
    >
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ props.selected ? "Edit" : "Tambah" }} Kategori</h2>
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
                    <!-- Input Kategori -->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Kategori
                        </label>
                        <Field
                            class="form-control form-control-lg form-control-solid"
                            type="text"
                            name="category"
                            autocomplete="off"
                            v-model="category.name"
                            placeholder="Masukkan Kategori"
                        />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="category" />
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
