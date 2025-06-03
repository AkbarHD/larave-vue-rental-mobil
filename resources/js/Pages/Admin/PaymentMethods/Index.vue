<template>

    <Head>
        <title>Payment - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <div class="row mt-1">
            <!-- Tabel Data Metode Pembayaran -->
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Daftar Metode Pembayaran</h5>
                            <!-- Tombol Tambah Metode Pembayaran -->
                            <Link href="/admin/payments/create" class="btn btn-primary" type="button">
                            <i class="fa fa-plus-circle"></i>
                            Tambah Metode Pembayaran
                            </Link>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th>No</th>
                                        <th>Nama Bank</th>
                                        <th>Tipe</th>
                                        <th>Nama Rekening</th>
                                        <th>No Rekening</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(method, index) in paymentMethods.data" :key="method.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (paymentMethods.current_page - 1)
                                            * paymentMethods.per_page }}</td>
                                        <td>{{ method.bank_name || '-' }}</td>
                                        <td>{{ method.type }}</td>
                                        <td>{{ method.account_name || '-' }}</td>
                                        <td>{{ method.account_number || '-' }}</td>
                                        <td>
                                            <Link :href="`/admin/payments/${method.id}/edit`"
                                                class="btn btn-sm btn-warning ms-2" type="button">
                                            <i class="fa fa-pencil-alt"></i> Edit
                                            </Link>
                                            <button @click.prevent="destroy(method.id)"
                                                class="btn btn-sm btn-danger border-0 ms-2"><i class="fa fa-trash"></i>
                                                Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="paymentMethods.data.length === 0">
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination :links="paymentMethods.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from "../../../Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";


export default {
    layout: LayoutAdmin,
    components: {
        Link,
        Pagination,
    },
    props: {
        paymentMethods: Object,
    },

    setup() {
        const destroy = (id) => {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    router.delete(`/admin/payments/${id}`);
                    Swal.fire("Dihapus!", "Metode pembayaran telah dihapus.", "success");
                }
            });
        };

        return {
            destroy,
        };
    },

};
</script>
