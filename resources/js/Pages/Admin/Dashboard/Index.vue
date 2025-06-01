<template>
    <Head>
        <title>Dashboard - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <!-- Data Statistik Umum -->
        <div class="row">
            <!-- Total Cars -->
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                                    <i class="fa fa-car fa-fw"></i>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Total Mobil</h2>
                                    <h3 class="fw-extrabold mb-1">{{ totalCars }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Total Mobil</h2>
                                    <h3 class="fw-extrabold mb-1">{{ totalCars }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rented Cars -->
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-secondary rounded me-4 me-sm-0">
                                    <i class="fa fa-car-side fa-fw"></i>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Mobil Disewa</h2>
                                    <h3 class="fw-extrabold mb-1">{{ rentedCars }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Mobil Disewa</h2>
                                    <h3 class="fw-extrabold mb-1">{{ rentedCars }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                    <i class="fa fa-users fa-fw"></i>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Total Customer</h2>
                                    <h3 class="fw-extrabold mb-1">{{ totalCustomers }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Total Customer</h2>
                                    <h3 class="fw-extrabold mb-1">{{ totalCustomers }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pendapatan -->
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-success rounded me-4 me-sm-0">
                                    <i class="fa fa-money-bill-wave fa-fw"></i>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h5">Pendapatan</h2>
                                    <h3 class="fw-extrabold mb-1"> {{ formatCurrency(rentalStats.total_revenue) }}</h3>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h5">Pendapatan</h2>
                                    <h3 class="fw-extrabold mb-1"> {{ formatCurrency(rentalStats.total_revenue) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Kedua: Statistik Penyewaan -->
        <div class="row">
            <!-- Total Penyewaan & Status -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="fs-5 fw-bold mb-0">Statistik Penyewaan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 col-md-4 text-center">
                                <h2 class="h5 mb-3">Total Penyewaan</h2>
                                <h3 class="display-6 fw-extrabold">{{ rentalStats.total_rentals }}</h3>
                            </div>
                            <div class="col-12 col-md-4 text-center">
                                <h2 class="h5 mb-3">Denda Keterlambatan</h2>
                                <h3 class="display-6 fw-extrabold">Rp {{ formatCurrency(rentalStats.total_late_fees) }}</h3>
                            </div>
                            <div class="col-12 col-md-4 text-center">
                                <h2 class="h5 mb-3">Sewa Disetujui</h2>
                                <h3 class="display-6 fw-extrabold">{{ rentalStats.status_counts.approved }}</h3>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h2 class="h5 mb-3">Distribusi Status Penyewaan</h2>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <span class="badge bg-warning">Tertunda</span>
                                            <span class="ms-2">Pending</span>
                                        </div>
                                        <span>{{ rentalStats.status_counts.pending }}</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" :style="`width: ${getStatusPercentage('pending')}%`"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <span class="badge bg-success">Disetujui</span>
                                            <span class="ms-2">Approved</span>
                                        </div>
                                        <span>{{ rentalStats.status_counts.approved }}</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" :style="`width: ${getStatusPercentage('approved')}%`"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <span class="badge bg-danger">Ditolak</span>
                                            <span class="ms-2">Rejected</span>
                                        </div>
                                        <span>{{ rentalStats.status_counts.rejected }}</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" :style="`width: ${getStatusPercentage('rejected')}%`"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobil Paling Populer -->
            <div class="col-12 col-xl-4 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="fs-5 fw-bold mb-0">Mobil Paling Populer</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0">Nama Mobil</th>
                                        <th class="border-0 text-end">Jumlah Sewa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="rentalStats.popular_cars.length === 0">
                                        <td colspan="2" class="text-center">Tidak ada data</td>
                                    </tr>
                                    <tr v-for="(car, index) in rentalStats.popular_cars" :key="index">
                                        <td>{{ car.car_name }}</td>
                                        <td class="text-end">{{ car.rental_count }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LayoutAdmin from '../../../Layouts/Admin.vue';
    import formatCurrency from '../../../utils/formatCurrency.js';
    import { Head } from '@inertiajs/vue3';

    export default {
        layout: LayoutAdmin,
        components: {
            Head,
        },
        props: {
            totalCars: Number,
            rentedCars: Number,
            totalCustomers: Number,
            rentalStats: Object,
        },
        methods: {
            formatCurrency,
            getStatusPercentage(status) {
                const total = this.rentalStats.total_rentals;
                if (total === 0) return 0;

                const count = this.rentalStats.status_counts[status] || 0;
                return Math.round((count / total) * 100);
            }
        }
    };
</script>
