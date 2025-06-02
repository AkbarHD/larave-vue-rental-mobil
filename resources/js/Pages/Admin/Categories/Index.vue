<template>

    <Head>
        <title>Categories - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>List of Categories</h5>
                                    <Link class="btn btn-primary" href="/admin/categories/create"><i
                                        class="fa fa-plus-circle"></i> Add Category</Link>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th>No</th>
                                        <th>Category Name</th>
                                        <th>Slug</th>
                                        <th class="border-0 rounded-end" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(category, index) in categories.data" :key="category.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (categories.current_page - 1) *
                                            categories.per_page }}</td>
                                        <td>{{ category.name }}</td>
                                        <td>{{ category.slug }}</td>
                                        <td class="text-center">
                                            <Link class="btn btn-warning btn-sm"
                                                :href="`/admin/categories/${category.id}/edit`">
                                            Edit
                                            </Link>
                                            <button @click.prevent="destroy(category.id)"
                                                class="btn btn-sm btn-danger border-0 ms-2"><i class="fa fa-trash"></i>
                                                Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="categories.data.length === 0">
                                        <td colspan="4" class="text-center">No categories found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination :links="categories.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from "../../../Layouts/Admin.vue";
import { Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import { router } from "@inertiajs/vue3";
import Swal from 'sweetalert2';

export default {
    layout: LayoutAdmin,
    components: {
        Pagination,
        Link,
    },
    props: {
        categories: Object,
    },
    setup() {
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
                        router.delete(`/admin/categories/${id}`);
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Category Berhasil Dihapus.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                });
        };

        return {
            destroy,
        };
    },
};
</script>
