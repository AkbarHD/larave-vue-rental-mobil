<template>
    <Head>
        <title>Sliders - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <!-- Image Upload Form -->
            <div class="col-md-12 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5 class="mb-3">Upload Slider Image</h5>
                        <form @submit.prevent="submit">
                            <div class="row align-items-center">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="image" class="form-label">Upload Image</label>
                                        <input
                                            type="file"
                                            id="image"
                                            class="form-control"
                                            @change="handleFileUpload"
                                        />
                                        <small class="text-muted">Allowed formats: JPG, PNG. Max size: 2MB</small>
                                        <div v-if="errors.image" class="alert alert-danger mt-2">
                                            {{ errors.image }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end mb-3">
                                    <button
                                    type="submit"
                                    class="btn btn-primary w-100 shadow custom-button"
                                >
                                    <i class="fas fa-paper-plane"></i>
                                    Submit
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sliders Table -->
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5 class="mb-3">List of Sliders</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th class="border-0 rounded-start" style="width:5%">No.</th>
                                        <th class="border-0">Nama Slider</th>
                                        <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(slider, index) in sliders.data" :key="slider.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (sliders.current_page - 1) * sliders.per_page }}</td>
                                        <td>
                                             <img :src="`${slider.image}`" alt="Slider Image" class="img-fluid rounded" style="max-height: 50px;">
                                         </td>
                                        <td class="text-center">
                                            <!-- Delete button for each slider -->
                                            <button @click.prevent="destroy(slider.id)" class="btn btn-sm btn-danger border-0 ms-2"><i class="fa fa-trash"></i> Delete</button>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination Component -->
                        <Pagination :links="sliders.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue';
import Swal from "sweetalert2";
import LayoutAdmin from '../../../Layouts/Admin.vue';
import Pagination from '../../../Components/Pagination.vue';
import { router } from '@inertiajs/vue3';

export default {
    layout: LayoutAdmin,
    components: {
        Pagination
    },
    props: {
        sliders: Object,
        errors: Object
    },
    setup() {
        const formData = reactive({
            image: null
        });

        const handleFileUpload = (event) => {
            formData.image = event.target.files[0];
        };

        const submit = () => {
            const payload = new FormData();
            payload.append("image", formData.image);

            router.post("/admin/sliders", payload, {
                onSuccess: () => {
                    Swal.fire({
                        title: "Success!",
                        text: "Slider berhasil ditambahkan.",
                        icon: "success",
                        confirmButtonText: "OK",
                    });
                    formData.image = null;
                    document.getElementById("image").value = null;
                },
            });
        };

            const destroy = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        router.delete(`/admin/sliders/${id}`);
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Slider Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

        return {
            formData,
            handleFileUpload,
            submit,
            destroy,
        };
    }
};
</script>

<style scoped>
    .img-fluid {
        max-width: 100%;
        height: auto;
    }
</style>
