<template>

    <Head>
        <title>Edit Metode Pembayaran - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/payments" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i
                    class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>

                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-edit"></i> Edit Metode Pembayaran</h5>
                        <hr />
                        <form @submit.prevent="submit">
                            <div class="row">
                                <!-- Kolom Kiri: Informasi Bank -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type">Tipe</label>
                                        <select class="form-select" v-model="form.type" id="type">
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="qris">QRIS</option>
                                        </select>
                                        <div v-if="errors.type" class="alert alert-danger mt-2">
                                            {{ errors.type }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bank_name">Nama Bank</label>
                                        <input type="text" class="form-control" id="bank_name"
                                            placeholder="Masukkan Nama Bank" v-model="form.bank_name" />
                                        <div v-if="errors.bank_name" class="alert alert-danger mt-2">
                                            {{ errors.bank_name }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Kolom Kanan: Informasi Rekening -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_name">Nama Rekening</label>
                                        <input type="text" class="form-control" id="account_name"
                                            placeholder="Masukkan Nama Rekening" v-model="form.account_name" />
                                        <div v-if="errors.account_name" class="alert alert-danger mt-2">
                                            {{ errors.account_name }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="account_number">Nomor Rekening</label>
                                        <input type="number" class="form-control" id="account_number"
                                            placeholder="Masukkan Nomor Rekening" v-model="form.account_number" />
                                        <div v-if="errors.account_number" class="alert alert-danger mt-2">
                                            {{ errors.account_number }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image">QR Code</label>
                                        <input type="file" class="form-control" id="image"
                                            @change="handleImageUpload" />
                                        <div v-if="errors.image" class="alert alert-danger mt-2">
                                            {{ errors.image }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="reset" class="btn btn-md btn-warning border-0 shadow">Reset</button>
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
import { Link, router } from "@inertiajs/vue3";

export default {
    layout: LayoutAdmin,

    components: {
        Link,
    },

    props: {
        errors: Object,
        payment: Object,
    },
    setup(props) {
        const form = reactive({
            type: props.payment.type || "",
            bank_name: props.payment.bank_name || "",
            account_name: props.payment.account_name || "",
            account_number: props.payment.account_number || "",
            image: null,
        });

        const handleImageUpload = (event) => {
            const file = event.target.files[0];
            if (file && file.type.match("image.*")) {
                form.image = file;
            } else {
                Swal.fire({
                    title: "Error",
                    text: "File must be an image!",
                    icon: "error",
                });
            }
        };


        const submit = () => {
            const formData = new FormData();
            formData.append("type", form.type || "");
            formData.append("bank_name", form.bank_name || "");
            formData.append("account_name", form.account_name || "");
            formData.append("account_number", form.account_number || "");

            if (form.image) {
                formData.append("image", form.image);
            }

            formData.append("_method", "PUT");

            router.post(`/admin/payments/${props.payment.id}`, formData, {
                forceFormData: true,
                onSuccess: () => {
                    Swal.fire({
                        title: "Success!",
                        text: "Metode pembayaran berhasil diperbarui!",
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
