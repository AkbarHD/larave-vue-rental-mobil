<template>

    <Head>
        <title>Edit Category - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <form @submit.prevent="submitForm">
                            <!-- Category Name Input -->
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control" id="name" v-model="form.name" required />
                                <div v-if="errors.name" class="alert alert-danger mt-2">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Update
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
        category: Object,
        errors: Object,
    },
    setup(props) {
        const form = ref({
            name: props.category.name,
        });

        const submitForm = () => {
            router.put(`/admin/categories/${props.category.id}`, form.value, {
                onSuccess: () => {
                    Swal.fire("Success!", "Category berhasil diperbarui!", "success").then(() => {
                        window.location.href = "/admin/categories";
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
