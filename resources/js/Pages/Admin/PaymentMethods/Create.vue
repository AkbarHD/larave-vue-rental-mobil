<template>

    <Head>
        <title>Create Metode Pembayaran - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-plus"></i> Tambah Metode Pembayaran</h5>
                        <hr />
                        <form @submit.prevent="submit">
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label>Tipe</label>
                                        <select class="form-select" v-model="form.type">
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="qris">QRIS</option>
                                        </select>
                                        <div v-if="errors.type" class="alert alert-danger mt-2">
                                            {{ errors.type }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Nama Bank</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Nama Bank"
                                            v-model="form.bank_name" />
                                        <div v-if="errors.bank_name" class="alert alert-danger mt-2">
                                            {{ errors.bank_name }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama Rekening</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Nama Rekening"
                                            v-model="form.account_name" />

                                        <div v-if="errors.account_name" class="alert alert-danger mt-2">
                                            {{ errors.account_name }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Nomor Rekening</label>
                                        <input type="number" class="form-control" placeholder="Masukkan Nomor Rekening"
                                            v-model="form.account_number"></input>
                                        <div v-if="errors.account_number" class="alert alert-danger mt-2">
                                            {{ errors.account_number }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>QR Code</label>
                                        <input type="file" class="form-control" @change="handleImageUpload" />
                                        <div v-if="errors.image" class="alert alert-danger mt-2">
                                            {{ errors.image }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary" :disabled="isLoading">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from "../../../Layouts/Admin.vue";
import { reactive } from "vue";
import Swal from "sweetalert2";
import { router } from "@inertiajs/vue3";

export default {
    layout: LayoutAdmin,
    props: {
        errors: Object,
    },
    setup() {
        const form = reactive({
            type: "",
            bank_name: "",
            account_name: "",
            account_number: "",
            image: null,
        });

        const handleImageUpload = (event) => {
            form.image = event.target.files[0];
        };

        const submit = () => {
            const formData = new FormData();
            formData.append('type', form.type);
            formData.append('bank_name', form.bank_name);
            formData.append('account_name', form.account_name);
            formData.append('account_number', form.account_number);

            if (form.image) {
                formData.append('image', form.image);
            }

            router.post("/admin/payments", formData, {
                onSuccess: () => {
                    Swal.fire({
                        title: "Success!",
                        text: "Metode pembayaran berhasil ditambahkan!",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                },
            });
        };

        return {
            form,
            handleImageUpload,
            submit
        };
    },
};
</script>
