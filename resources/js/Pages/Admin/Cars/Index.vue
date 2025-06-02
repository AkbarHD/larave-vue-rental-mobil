<template>
    <Head>
        <title>Cars - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">

        <!-- Cars Table -->
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>List of Cars</h5>
                                    <Link
                                        class="btn btn-primary"
                                        href="/admin/cars/create"
                                    >
                                    <i class="fa fa-plus-circle"></i>
                                        Tambah Car
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th class="border-0 rounded-start" style="width:5%">No.</th>
                                        <th class="border-0">Merk</th>
                                        <th class="border-0">Model</th>
                                        <th class="border-0">Plat Nomor</th>
                                        <th class="border-0">Tahun</th>
                                        <th class="border-0">Fuel Type</th>
                                        <th class="border-0">Harga/hari</th>
                                        <th class="border-0 rounded-end" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(car, index) in cars.data" :key="car.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (cars.current_page - 1) * cars.per_page }}</td>
                                        <td>{{ car.brand }}</td>
                                        <td>{{ car.model }}</td>
                                        <td>{{ car.license_plate }}</td>
                                        <td>{{ car.year }}</td>
                                        <td>{{ car.fuel_type }}</td>
                                        <td>{{ formatCurrency(car.daily_rate) }}</td>
                                        <td class="text-center">
                                            <Link
                                                class="btn btn-warning btn-sm"
                                                :href="`/admin/cars/${car.id}/edit`"
                                            >
                                                Edit
                                            </Link>
                                            <button @click.prevent="destroy(car.id)" class="btn btn-sm btn-danger border-0 ms-2"><i class="fa fa-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :links="cars.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LayoutAdmin from '../../../Layouts/Admin.vue';
    import Pagination from '../../../Components/Pagination.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import formatCurrency from '../../../utils/formatCurrency'
    import Swal from 'sweetalert2';

    export default {
        layout: LayoutAdmin,
        components: {
            Head,
            Link,
            Pagination
        },
        props: {
            cars: Object,
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

                        router.delete(`/admin/cars/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Car Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                formatCurrency,
                destroy
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
