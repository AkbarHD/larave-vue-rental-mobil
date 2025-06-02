<template>

    <Head>
        <title>Detail Rental - Rental Mobil</title>
    </Head>
    <div class="col-md-8 col-lg-6 col-xxl-4 vstack gap-3 mx-auto p-4 bg-white shadow-sm rounded-3 my-4">
        <!-- Rental Details Section -->
        <div v-if="rental" class="rental-detail">
            <h4 class="mb-3 text-center fw-bold">Detail Rental</h4>

            <!-- User/Renter Information Card -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body p-3">
                    <h5 class="card-title text-primary mb-3">Informasi Penyewa</h5>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-badge me-2 text-secondary fs-5"></i>
                            <h6 class="mb-0 fw-bold">Nama : {{ rental.user.name }}</h6>
                        </div>

                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <i class="bi bi-gender-ambiguous text-secondary"></i>
                            </div>
                            <div>Jenis Kelamin : {{ rental.user.gender }}</div>
                        </div>

                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <i class="bi bi-envelope text-secondary"></i>
                            </div>
                            <div>Email : {{ rental.user.email }}</div>
                        </div>

                        <div class="d-flex mb-2" v-if="rental.user.whatsapp_number">
                            <div class="me-2">
                                <i class="bi bi-whatsapp text-secondary"></i>
                            </div>
                            <div>Nomor Whatsapp : {{ rental.user.whatsapp_number }}</div>
                        </div>

                        <div class="d-flex mb-3" v-if="rental.user.address">
                            <div class="me-2">
                                <i class="bi bi-geo-alt text-secondary"></i>
                            </div>
                            <div>Alamat : {{ rental.user.address }}</div>
                        </div>
                    </div>

                    <!-- ID Card Section -->
                    <div v-if="rental.user.image" class="mt-2">
                        <h6 class="fw-semibold mb-2">
                            <i class="bi bi-card-image me-2 text-secondary"></i>
                            ID Penyewa
                        </h6>
                        <div class="text-center">
                            <img :src="rental.user.image" alt="ID Penyewa" class="img-fluid rounded border shadow-sm"
                                style="max-height: 180px; cursor: pointer;" @click="openImage(rental.user.image)">
                            <p class="text-muted small mt-1">Klik gambar untuk memperbesar</p>
                        </div>
                    </div>
                    <div v-else class="alert alert-warning py-2 mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        ID Penyewa belum diunggah
                    </div>
                </div>
            </div>

            <!-- Car Information Card -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body p-3">
                    <h5 class="card-title text-primary mb-2">Informasi Mobil</h5>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Mobil:</span>
                        <span>{{ rental.car.brand }} {{ rental.car.model }}</span>
                    </div>
                </div>
            </div>

            <!-- Rental Period Card -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body p-3">
                    <h5 class="card-title text-primary mb-2">Periode Sewa</h5>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Tanggal Mulai:</span>
                        <span>{{ formatDate(rental.rental_start_date) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Tanggal Selesai:</span>
                        <span>{{ formatDate(rental.rental_end_date) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Tanggal Pengembalian:</span>
                        <span v-if="rental.return_date">{{ formatDate(rental.return_date) }}</span>
                        <span v-else class="text-muted">Belum dikembalikan</span>
                    </div>
                </div>
            </div>

            <!-- Financial Details Card -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body p-3">
                    <h5 class="card-title text-primary mb-2">Rincian Biaya</h5>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Biaya Dasar:</span>
                        <span>{{ formatCurrency(rentalFeeDetails.base_rental_fee) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Biaya Tambahan:</span>
                        <span>{{ formatCurrency(rentalFeeDetails.additional_fee) }}</span>
                    </div>
                    <div v-if="rental.addons && rental.addons.length > 0"
                        class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Biaya Addon:</span>
                        <span>{{ formatCurrency(rentalFeeDetails.addon_total_fee) }}</span>
                    </div>
                    <div v-if="rental.late_fee > 0" class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold">Denda Keterlambatan:</span>
                        <span class="text-danger">{{ formatCurrency(rental.late_fee) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Pembayaran:</span>
                        <span class="fw-bold text-primary">{{ formatCurrency(rentalFeeDetails.total_fee) }}</span>
                    </div>
                </div>
            </div>

            <!-- Status and Payment Proofs -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body p-3">
                    <h5 class="card-title text-primary mb-2">Status & Pembayaran</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Status Rental:</span>
                        <span :class="{
                            'badge bg-success': rental.status === 'approved',
                            'badge bg-warning': rental.status === 'pending',
                            'badge bg-danger': rental.status === 'rejected'
                        }">
                            {{ rental.status === 'approved' ? 'Disetujui' :
                                rental.status === 'pending' ? 'Menunggu' :
                                    rental.status === 'rejected' ? 'Ditolak' : rental.status }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Status Pembayaran:</span>
                        <span :class="{
                            'badge bg-success': getPaymentStatus === 'paid',
                            'badge bg-warning': getPaymentStatus === 'pending',
                            'badge bg-danger': getPaymentStatus === 'expired' || getPaymentStatus === 'failed',
                            'badge bg-secondary': getPaymentStatus === 'unknown'
                        }">
                            {{ getPaymentStatus === 'paid' ? 'Lunas' :
                                getPaymentStatus === 'pending' ? 'Menunggu Pembayaran' :
                                    getPaymentStatus === 'expired' ? 'Kadaluarsa' :
                                        getPaymentStatus === 'failed' ? 'Gagal' : 'Tidak Diketahui' }}
                        </span>
                    </div>


                    <!-- Rejection Reason (if status is rejected) -->
                    <div v-if="rental.status === 'rejected' && rental.rejection_reason"
                        class="alert alert-danger p-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <h6 class="mb-0 fw-bold">Alasan Penolakan:</h6>
                        </div>
                        <p class="mb-0 mt-2 ps-4">{{ rental.rejection_reason }}</p>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Metode Pembayaran:</span>
                        <span>{{ rental.payment_method }}</span>
                    </div>

                    <!-- Payment Type: Manual -->
                    <div v-if="hasPayment && getPaymentType === 'manual'">
                        <!-- Payment Proof -->
                        <div v-if="rental.payments[0].payment_proof" class="mb-3">
                            <p class="fw-semibold mb-2">Bukti Pembayaran:</p>
                            <div class="text-center">
                                <img :src="rental.payments[0].payment_proof" alt="Bukti Pembayaran"
                                    class="img-fluid rounded shadow-sm" style="max-height: 200px; cursor: pointer;"
                                    @click="openImage(rental.payments[0].payment_proof)">
                                <p class="text-muted small mt-1">Klik gambar untuk memperbesar</p>
                            </div>
                        </div>

                        <!-- Payment Date -->
                        <div v-if="rental.payments[0].paid_at"
                            class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Tanggal Pembayaran:</span>
                            <span>{{ formatDate(rental.payments[0].paid_at) }}</span>
                        </div>
                    </div>

                    <!-- Payment Type: Tripay -->
                    <div v-else-if="hasPayment && getPaymentType === 'tripay'">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Reference:</span>
                            <span>{{ rental.payments[0].payment_reference }}</span>
                        </div>
                        <div v-if="rental.payments[0].paid_at"
                            class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Tanggal Pembayaran:</span>
                            <span>{{ formatDate(rental.payments[0].paid_at) }}</span>
                        </div>
                        <div v-if="rental.payments[0].expired_at"
                            class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold">Expired:</span>
                            <span>{{ formatDate(rental.payments[0].expired_at) }}</span>
                            <span v-if="isExpired" class="badge bg-danger ms-2">Kadaluarsa</span>
                        </div>
                        <div v-if="getPaymentStatus === 'pending' && rental.payments[0].payment_url && !isExpired"
                            class="d-grid gap-2 mt-3">
                            <a :href="rental.payments[0].payment_url" target="_blank" class="btn btn-primary">
                                <i class="bi bi-credit-card me-1"></i> Bayar Sekarang
                            </a>
                        </div>
                        <div v-else-if="getPaymentStatus === 'expired'" class="alert alert-danger py-2 mt-2">
                            Pembayaran telah kadaluarsa
                        </div>
                        <div v-else-if="getPaymentStatus === 'failed'" class="alert alert-danger py-2 mt-2">
                            Pembayaran gagal
                        </div>
                        <div v-else-if="getPaymentStatus === 'paid'" class="alert alert-success py-2 mt-2">
                            Pembayaran berhasil
                        </div>
                    </div>


                    <!-- Late Fee Payment Proof -->
                    <div v-if="rental.late_fee_payment_proof" class="mt-3 mb-3">
                        <p class="fw-semibold mb-2">Bukti Pembayaran Denda:</p>
                        <div class="text-center">
                            <img :src="rental.late_fee_payment_proof" alt="Bukti Pembayaran Denda"
                                class="img-fluid rounded shadow-sm" style="max-height: 200px; cursor: pointer;"
                                @click="openImage(rental.late_fee_payment_proof)">
                            <p class="text-muted small mt-1">Klik gambar untuk memperbesar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Rental List Button -->
            <div class="d-flex justify-content-center mt-3">
                <Link href="/rentals" class="btn btn-primary px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Rental
                </Link>
            </div>
        </div>

        <!-- Error Message for Missing Rental Data -->
        <div v-else class="text-center p-4">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-1"></i>
            <p class="mt-3 mb-3">Detail rental tidak ditemukan.</p>
            <Link href="/rentals" class="btn btn-outline-primary">
            Kembali ke Daftar Rental
            </Link>
        </div>

        <!-- Image Modal untuk memperbesar gambar -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Detail Gambar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-3">
                        <img :src="modalImage" class="img-fluid" alt="Detail Gambar">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import formatCurrency from '../../../utils/formatCurrency';
import formatDate from '../../../utils/formatDate';

export default {
    components: {
        Link
    },

    props: {
        rental: Object,
        rentalFeeDetails: Object
    },

    setup(props) {
        const modalImage = ref('');
        const modalInstance = ref(null);

        const openImage = (imageUrl) => {
            modalImage.value = imageUrl;
            if (modalInstance.value) {
                modalInstance.value.show();
            }
        };

        const hasPayment = computed(() => {
            return props.rental && props.rental.payments && props.rental.payments.length > 0;
        });

        const getPaymentType = computed(() => {
            if (hasPayment.value) {
                return props.rental.payments[0].payment_type ||
                    props.rental.payment_type ||
                    'manual';
            }
            return 'manual';
        });

        const isPaid = computed(() => {
            if (hasPayment.value) {
                return !!props.rental.payments[0].paid_at;
            }
            return false;
        });

        const isExpired = computed(() => {
            if (hasPayment.value && props.rental.payments[0].expired_at) {
                return new Date(props.rental.payments[0].expired_at) < new Date();
            }
            return false;
        });

        const getPaymentStatus = computed(() => {
            if (!hasPayment.value) {
                return 'unknown';
            }

            if (props.rental.payments[0].payment_status) {
                return props.rental.payments[0].payment_status;
            }

            if (isPaid.value) {
                return 'paid';
            } else if (isExpired.value) {
                return 'expired';
            } else {
                return 'pending';
            }
        });

        onMounted(() => {
            if (typeof bootstrap !== 'undefined') {
                modalInstance.value = new bootstrap.Modal(document.getElementById('imageModal'));
            }
        });

        return {
            modalImage,
            hasPayment,
            getPaymentType,
            isPaid,
            isExpired,
            formatCurrency,
            formatDate,
            openImage,
            getPaymentStatus
        };
    }
};
</script>
