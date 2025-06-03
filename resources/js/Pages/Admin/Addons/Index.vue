<template>

    <Head>
        <title>Addons - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>List of Addons</h5>
                                    <Link class="btn btn-primary" href="/admin/addons/create"><i
                                        class="fa fa-plus-circle"></i> Tambah Addons</Link>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th>No</th>
                                        <th>Nama Addon</th>
                                        <th>Harga</th>
                                        <th>Tipe</th>
                                        <th class="border-0 rounded-end" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(addon, index) in addons.data" :key="addon.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (addons.current_page - 1) *
                                            addons.per_page }}</td>
                                        <td>{{ addon.name }}</td>
                                        <td>{{ formatCurrency(addon.price) }}</td>
                                        <td>{{ addon.type }}</td>
                                        <td class="text-center">
                                            <Link class="btn btn-warning btn-sm"
                                                :href="`/admin/addons/${addon.id}/edit`">
                                            Edit
                                            </Link>
                                            <button @click.prevent="destroy(addon.id)"
                                                class="btn btn-sm btn-danger border-0 ms-2"><i class="fa fa-trash"></i>
                                                Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="addons.data.length === 0">
                                        <td colspan="7" class="text-center">Tidak ada data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination :links="addons.links" align="end" />
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
import formatCurrency from '../../../utils/formatCurrency';
import { router } from "@inertiajs/vue3";
import Swal from 'sweetalert2';

export default {
    layout: LayoutAdmin,
    components: {
        Pagination,
        Link,
    },
    props: {
        addons: Object,
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

                        router.delete(`/admin/addons/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Addon Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
        }

        return {
            formatCurrency,
            destroy,
        };
    },
};
</script>
