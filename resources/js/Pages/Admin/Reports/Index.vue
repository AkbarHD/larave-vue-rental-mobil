<template>

    <Head>
        <title>Report - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <!-- Report Filters -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h5><i class="fa fa-chart-bar me-2"></i>Laporan Penyewaan</h5>
                        <hr />

                        <form @submit.prevent="generateReport">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="start_date">Tanggal Mulai</label>
                                        <input type="date" id="start_date" class="form-control"
                                            v-model="filters.start_date" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="end_date">Tanggal Akhir</label>
                                        <input type="date" id="end_date" class="form-control"
                                            v-model="filters.end_date" />
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fa fa-search me-1"></i> Generate Laporan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="row" v-if="hasData">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Detail Penyewaan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Pelanggan</th>
                                        <th>Mobil</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Durasi</th>
                                        <th>Base Fee</th>
                                        <th>Additional Fee</th>
                                        <th>Late Fee</th>
                                        <th>Total Biaya</th>
                                        <th>Status</th>
                                        <th>Metode Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="reportData.data.length === 0">
                                        <td colspan="11" class="text-center">Tidak ada data</td>
                                    </tr>
                                    <tr v-for="(rental, index) in reportData.data" :key="rental.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (reportData.current_page - 1) *
                                            reportData.per_page }}</td>
                                        <td>{{ rental.user_name }}</td>
                                        <td>{{ rental.car_name }}</td>
                                        <td>
                                            {{ formatDate(rental.rental_start_date) }} -
                                            {{ formatDate(rental.rental_end_date) }}
                                        </td>
                                        <td>{{ rental.total_days }} hari</td>
                                        <td>Rp {{ formatCurrency(rental.base_rental_fee) }}</td>
                                        <td>Rp {{ formatCurrency(rental.additional_fee) }}</td>
                                        <td>Rp {{ formatCurrency(rental.late_fee) }}</td>
                                        <td>Rp {{ formatCurrency(rental.total_fee) }}</td>
                                        <td>
                                            <span :class="getStatusBadgeClass(rental.status)">
                                                {{ getStatusLabel(rental.status) }}
                                            </span>
                                        </td>
                                        <td>{{ rental.payment_type }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <Pagination v-if="reportData.links" :links="reportData.links" align="end" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Data Message -->
        <div class="row" v-if="!hasData">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i> Pilih rentang tanggal dan klik "Generate Laporan" untuk
                    melihat data penyewaan.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from "../../../Layouts/Admin.vue";
import { reactive, ref, computed } from "vue";
import axios from "axios";
import Pagination from "../../../Components/Pagination.vue";
import formatCurrency from "../../../utils/formatCurrency";
import formatDate from "../../../utils/formatDate";
import { Head } from '@inertiajs/vue3';

export default {
    layout: LayoutAdmin,
    components: {
        Head,
        Pagination
    },
    props: {
        startDate: String,
        endDate: String,
        reportData: Object,
    },
    setup(props) {
        const filters = reactive({
            start_date: props.startDate,
            end_date: props.endDate,
        });

        const currentReportData = ref(props.reportData || { data: [] });
        const hasData = computed(() => currentReportData.value.data && currentReportData.value.data.length > 0);

        const generateReport = async () => {
            const response = await axios.post('/admin/reports/rentals/generate', filters);
            currentReportData.value = response.data.reportData;
        };

        const getStatusLabel = (status) => {
            const labels = {
                'pending': 'Tertunda',
                'approved': 'Disetujui',
                'rejected': 'Ditolak'
            };
            return labels[status] || status;
        };

        const getStatusBadgeClass = (status) => {
            const classes = {
                'pending': 'badge bg-warning',
                'approved': 'badge bg-success',
                'rejected': 'badge bg-danger'
            };
            return classes[status] || 'badge bg-secondary';
        };

        return {
            filters,
            reportData: currentReportData,
            hasData,
            generateReport,
            formatDate,
            formatCurrency,
            getStatusLabel,
            getStatusBadgeClass,
        };
    }
};
</script>
