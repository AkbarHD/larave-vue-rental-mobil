<template>

    <Head>
        <title>Cars - Rental Mobil</title>
    </Head>

    <div class="container-fluid mb-5 mt-5">
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
                                        <th scope="col">Status Rental</th>
                                        <th scope="col">Status Pembayaran</th>
                                        <th scope="col" class="text-center">Aksi</th>
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
                                            <td>
                                                <span :class="getStatusBadgeClass(rental.status)">
                                                    {{ rental.status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center action-buttons">
                                                    <!-- Detail Button - Always visible -->
                                                    <Link :href="`/rentals/${rental.id}`"
                                                        class="btn btn-sm btn-info me-2" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                    </Link>

                                                    <!-- Admin Actions Dropdown for Pending Status -->
                                                    <div v-if="rental.status === 'pending'"
                                                        class="dropdown d-inline me-2">
                                                        <button class="btn btn-sm btn-primary dropdown-toggle"
                                                            type="button" :id="`actionDropdown${rental.id}`"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-cog"></i> Aksi
                                                        </button>
                                                        <ul class="dropdown-menu"
                                                            :aria-labelledby="`actionDropdown${rental.id}`">
                                                            <li>
                                                                <a class="dropdown-item text-success" href="#"
                                                                    @click.prevent="approveRental(rental.id)">
                                                                    <i class="fas fa-check me-1"></i> Approve
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    @click.prevent="rejectRental(rental.id)">
                                                                    <i class="fas fa-times me-1"></i> Reject
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Return Management Button for Approved Status -->
                                                    <button v-if="rental.status === 'approved' && !rental.is_returned"
                                                        class="btn btn-sm btn-success me-2"
                                                        @click="openReturnModal(rental)" title="Kelola Pengembalian">
                                                        <i class="fas fa-car me-1"></i> Pengembalian
                                                    </button>

                                                    <!-- Return Info Badge for Returned Rentals -->
                                                    <button v-if="rental.is_returned"
                                                        class="btn btn-sm btn-outline-secondary me-2"
                                                        @click="openReturnInfoModal(rental)" title="Info Pengembalian">
                                                        <i class="fas fa-info-circle me-1"></i> Info Pengembalian
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <!-- Jika tidak ada data rental, menamplikan pesan "Belum ada rental yang tersedia." -->
                                    <tr v-if="!(rentals && rentals.data.length > 0)">
                                        <td colspan="7" class="text-center">Belum ada rental yang tersedia.</td>
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

    <!-- Return Form Modal -->
    <div class="modal fade" id="returnFormModal" tabindex="-1" aria-labelledby="returnFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="returnFormModalLabel">
                        <i class="fas fa-car-side me-2"></i>Form Pengembalian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="selectedRental">
                        <div class="mb-3">
                            <label class="form-label">Mobil:</label>
                            <div class="form-control-plaintext">{{ selectedRental.car.brand }} {{
                                selectedRental.car.model
                                }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengembalian:</label>
                            <input v-model="selectedRental.return_date" type="date" class="form-control" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Mobil:</label>
                            <select v-model="selectedRental.car.status" class="form-select">
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>

                        <!-- Calculate if return is late -->
                        <div v-if="isLateReturn(selectedRental)" class="alert alert-warning mt-2 p-3">
                            <p class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Pengembalian terlambat!</p>
                            <p class="mb-2 fw-bold">Denda: {{ calculateLateFee(selectedRental) }}</p>

                            <div class="mb-2">
                                <label class="form-label">Upload Bukti Pembayaran Denda:</label>
                                <input type="file" @change="handleLateFeeProofChange($event, selectedRental.id)"
                                    class="form-control" accept="image/*" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-primary" @click="confirmReturn(selectedRental?.id)">
                        <i class="fas fa-check me-1"></i> Konfirmasi Pengembalian
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Info Modal -->
    <div class="modal fade" id="returnInfoModal" tabindex="-1" aria-labelledby="returnInfoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="returnInfoModalLabel">
                        <i class="fas fa-check-circle me-2 text-success"></i>Informasi Pengembalian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="selectedRental">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mobil:</label>
                            <div class="form-control-plaintext">{{ selectedRental.car.brand }} {{
                                selectedRental.car.model
                                }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Pengembalian:</label>
                            <div class="form-control-plaintext">{{ formatDate(selectedRental.return_date) }}</div>
                        </div>

                        <div class="mb-3" v-if="selectedRental.late_fee > 0">
                            <label class="form-label fw-bold">Denda Keterlambatan:</label>
                            <div class="form-control-plaintext">{{ formatCurrency(selectedRental.late_fee) }}</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import formatCurrency from '../../../utils/formatCurrency';
import Pagination from '../../../Components/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { reactive, ref, onMounted } from 'vue';

export default {
    layout: LayoutAdmin,

    components: {
        Pagination,
        Link,
    },

    props: {
        rentals: Object,
    },

    setup(props) {
        const rentals = reactive(props.rentals);
        const returnFormModal = ref(null);
        const returnInfoModal = ref(null);
        const selectedRental = ref(null);

        onMounted(() => {
            returnFormModal.value = new bootstrap.Modal(document.getElementById('returnFormModal'));
            returnInfoModal.value = new bootstrap.Modal(document.getElementById('returnInfoModal'));
        });

        const formatDate = (date) => {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(date).toLocaleDateString('id-ID', options);
        };

        const getStatusBadgeClass = (status) => {
            const classes = {
                'pending': 'badge bg-warning text-dark',
                'approved': 'badge bg-success',
                'rejected': 'badge bg-danger',
                'completed': 'badge bg-info'
            };
            return classes[status] || 'badge bg-secondary';
        };

        const openReturnModal = (rental) => {
            selectedRental.value = { ...rental };
            returnFormModal.value.show();
        };

        const openReturnInfoModal = (rental) => {
            selectedRental.value = { ...rental };
            returnInfoModal.value.show();
        };

        const approveRental = async (rentalId) => {
            // Confirm dialog before approving
            Swal.fire({
                title: 'Konfirmasi Approval',
                text: 'Apakah Anda yakin ingin menyetujui rental ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await router.post(`/rentals/${rentalId}/approve`);
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Rental telah disetujui.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        };

        const rejectRental = (rentalId) => {
            Swal.fire({
                title: 'Alasan Penolakan',
                input: 'textarea',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputAttributes: {
                    'aria-label': 'Alasan penolakan'
                },
                showCancelButton: true,
                confirmButtonText: 'Tolak Rental',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Anda harus memberikan alasan penolakan';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    router.post(`/rentals/${rentalId}/reject`, {
                        rejection_reason: result.value
                    }, {
                        onSuccess: () => {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Rental telah ditolak.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    });
                }
            });
        };

        const isLateReturn = (rental) => {
            if (!rental || !rental.return_date) return false;
            const endDate = new Date(rental.rental_end_date);
            const returnDate = new Date(rental.return_date);
            return returnDate > endDate;
        };

        const calculateLateFee = (rental) => {
            if (!rental || !isLateReturn(rental)) return 0;

            const endDate = new Date(rental.rental_end_date);
            const returnDate = new Date(rental.return_date);
            const diffTime = Math.abs(returnDate - endDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            const lateFee = diffDays * rental.car.penalty_rate_per_day;
            return formatCurrency(lateFee);
        };

        const handleLateFeeProofChange = (event, rentalId) => {
            const file = event.target.files[0];
            if (file && selectedRental.value && selectedRental.value.id === rentalId) {
                selectedRental.value.late_fee_payment_proof_file = file;
            }
        };

        const confirmReturn = (rentalId) => {
            if (!selectedRental.value || !selectedRental.value.return_date) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Tanggal pengembalian tidak boleh kosong.',
                    icon: 'error',
                    showConfirmButton: true,
                });
                return;
            }

            if (isLateReturn(selectedRental.value) && !selectedRental.value.late_fee_payment_proof_file) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Bukti pembayaran denda diperlukan untuk pengembalian terlambat.',
                    icon: 'error',
                    showConfirmButton: true,
                });
                return;
            }

            // Confirmation dialog
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: 'Apakah data pengembalian sudah benar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Konfirmasi',
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('return_date', selectedRental.value.return_date);
                    formData.append('car_status', selectedRental.value.car.status);

                    if (selectedRental.value.late_fee_payment_proof_file) {
                        formData.append('late_fee_payment_proof', selectedRental.value.late_fee_payment_proof_file);
                    }

                    router.post(`/rentals/${rentalId}/confirm-return`, formData, {
                        onSuccess: () => {
                            // Hide the modal
                            returnFormModal.value.hide();

                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Pengembalian mobil berhasil dikonfirmasi.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                    });
                }
            });
        };

        return {
            rentals,
            formatDate,
            approveRental,
            rejectRental,
            confirmReturn,
            isLateReturn,
            calculateLateFee,
            handleLateFeeProofChange,
            formatCurrency,
            getStatusBadgeClass,
            openReturnModal,
            openReturnInfoModal,
            selectedRental
        };
    },
};
</script>
