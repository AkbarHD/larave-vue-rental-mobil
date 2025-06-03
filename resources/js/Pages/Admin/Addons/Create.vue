<template>
    <Head>
        <title>Create Addon - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <form @submit.prevent="submitForm">
                            <div class="form-group">
                                <label for="name">Nama Addon</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    v-model="form.name"
                                />
                                <div v-if="errors.name" class="alert alert-danger mt-2">
                                    {{ errors.name }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="price"
                                    v-model="form.price"
                                />
                                <div v-if="errors.price" class="alert alert-danger mt-2">
                                    {{ errors.price }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type">Tipe</label>
                                <select
                                    class="form-control"
                                    id="type"
                                    v-model="form.type"
                                >
                                    <option value="driver">Driver</option>
                                    <option value="fuel">Fuel</option>
                                    <option value="insurance">Insurance</option>
                                    <option value="additional_service">Additional Service</option>
                                </select>
                                <div v-if="errors.type" class="alert alert-danger mt-2">
                                    {{ errors.type }}
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
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
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

export default {
    layout: LayoutAdmin,
    props: {
        errors: Object,
    },
    setup() {
        const form = ref({
            name: "",
            price: "",
            type: "driver",
        });

        const submitForm = () => {
            router.post("/admin/addons", form.value, {
                onSuccess: () => {
                    Swal.fire("Berhasil!", "Addon berhasil ditambahkan.", "success").then(() => {
                        window.location.href = "/admin/addons";
                    });
                },
            });
        };

        return {
            form,
            submitForm,
        };
    },
};
</script>
