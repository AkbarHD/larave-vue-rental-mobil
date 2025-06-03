<template>

    <Head>
        <title>Customers - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Daftar Customer</h5>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Whatsapp</th>
                                        <th>Gender</th>
                                        <th class="border-0 rounded-end" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(customer, index) in customers.data" :key="customer.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (customers.current_page - 1) *
                                            customers.per_page }}</td>
                                        <td>{{ customer.name }}</td>
                                        <td>{{ customer.email }}</td>
                                        <td>{{ customer.whatsapp_number || '-' }}</td>
                                        <td>{{ customer.gender || '-' }}</td>
                                        <td class="text-center">
                                            <a v-if="customer.whatsapp_number"
                                                :href="`https://wa.me/${formatWhatsapp(customer.whatsapp_number)}`"
                                                target="_blank" class="btn btn-success btn-sm">
                                                <i class="fab fa-whatsapp"></i> Follow Up
                                            </a>
                                            <span v-else class="text-muted me-2">No WhatsApp</span>
                                            <button @click="showCustomerDetail(customer)"
                                                class="btn btn-info btn-sm ms-2">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="customers.data.length === 0">
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination :links="customers.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Detail Modal -->
    <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerDetailModalLabel">Detail Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="selectedCustomer">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h6 class="fw-bold">Informasi Dasar</h6>
                            <hr>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="fw-bold mb-1">Nama Lengkap:</p>
                            <p>{{ selectedCustomer.name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="fw-bold mb-1">Email:</p>
                            <p>{{ selectedCustomer.email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="fw-bold mb-1">Nomor WhatsApp:</p>
                            <p>{{ selectedCustomer.whatsapp_number || '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="fw-bold mb-1">Gender:</p>
                            <p>{{ selectedCustomer.gender || '-' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <p class="fw-bold mb-1">Alamat:</p>
                            <p>{{ selectedCustomer.address || '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="fw-bold mb-1">Tanggal Registrasi:</p>
                            <p>{{ formatDate(selectedCustomer.created_at) }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a v-if="selectedCustomer && selectedCustomer.whatsapp_number"
                        :href="`https://wa.me/${formatWhatsapp(selectedCustomer.whatsapp_number)}`" target="_blank"
                        class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> Follow Up
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from "../../../Layouts/Admin.vue";
import { Head } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import formatDate from "../../../utils/formatDate";
import { ref } from 'vue';

export default {
    layout: LayoutAdmin,
    components: {
        Pagination,
        Head
    },
    props: {
        customers: Object,
    },
    setup() {
        const selectedCustomer = ref(null);

        const showCustomerDetail = (customer) => {
            selectedCustomer.value = customer;
            const myModal = new bootstrap.Modal(document.getElementById('customerDetailModal'));
            myModal.show();
        };

        const formatWhatsapp = (number) => {
            let cleaned = number.replace(/\D/g, '');
            if (cleaned.startsWith('0')) {
                cleaned = '62' + cleaned.substring(1);
            } else if (!cleaned.startsWith('62')) {
                cleaned = '62' + cleaned;
            }
            return cleaned;
        };

        return {
            selectedCustomer,
            showCustomerDetail,
            formatWhatsapp,
            formatDate
        };
    },
};
</script>
