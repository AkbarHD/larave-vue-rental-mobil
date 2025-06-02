<template>

    <Head>
        <title>Cars - Rental Mobil</title>
    </Head>

    <div class="container-fluid mb-5 mt-3">
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>List of Rentals</h5>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th scope="col">No</th>
                                        <th scope="col">Mobil</th>
                                        <th scope="col">Tanggal Mulai</th>
                                        <th scope="col">Tanggal Selesai</th>
                                        <th scope="col">Jumlah Pembayaran</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Jika data rental tersedia, tampilkan data rental -->
                                    <template v-if="rentals && rentals.data.length > 0">
                                        <tr v-for="(rental, index) in rentals.data" :key="rental.id">
                                            <td class="fw-bold text-center">{{ index + 1 + (rentals.current_page - 1) *
                                                rentals.per_page }}</td>
                                            <td>{{ rental.car.brand }} {{ rental.car.model }}</td>
                                            <td>{{ formatDate(rental.rental_start_date) }}</td>
                                            <td>{{ formatDate(rental.rental_end_date) }}</td>
                                            <td>{{ formatCurrency(rental.total_fee) }}</td>
                                            <td>{{ rental.status }}</td>
                                            <td>
                                                <Link :href="`/rentals/${rental.id}`" class="btn btn-sm btn-info me-2"
                                                    title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                                </Link>
                                            </td>
                                        </tr>
                                    </template>
                                    <!-- Jika tidak ada data rental, menamplikan pesan "Belum ada rental yang tersedia." -->
                                    <tr v-if="!(rentals && rentals.data.length > 0)">
                                        <td colspan="6" class="text-center">Belum ada rental yang tersedia.</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <Pagination v-if="rentals && rentals.data.length > 0" :links="rentals.links" align="end" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import formatCurrency from '../../../utils/formatCurrency';
import formatDate from '../../../utils/formatDate';
import Pagination from '../../../Components/Pagination.vue';

export default {
    layout: LayoutAdmin,

    components: {
        Pagination,
    },

    props: {
        rentals: Object,
    },

    setup() {
        return {
            formatCurrency,
            formatDate,
        };
    },
};
</script>
