<<template>

    <Head>
        <title>Detail Car - {{ siteSetting.site_name }}</title>
    </Head>

    <MainLayout :siteSetting="siteSetting">
        <!-- Car Details Card -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <!-- Image Gallery with Status Indicator -->
            <div class="position-relative">
                <img :src="car.image" class="w-100" style="height: 280px; object-fit: cover" :alt="car.model" />
                <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="text-white mb-0 fw-bold">{{ car.brand }} {{ car.model }}</h3>
                        <span class="badge" :class="statusBadgeClasses">{{ car.status }}</span>
                    </div>
                </div>
            </div>

            <!-- Car Quick Info Bar -->
            <div class="d-flex justify-content-between bg-light p-3 border-bottom">
                <div v-for="(item, index) in quickInfoItems" :key="index" class="text-center">
                    <i :class="`fas ${item.icon}`"></i>
                    <span class="d-block fw-bold">{{ item.value }}</span>
                </div>
            </div>

            <!-- Price & Booking Button -->
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-muted">Harga Sewa</span>
                        <h4 class="text-danger fw-bold mb-0">
                            {{ formatCurrency(car.daily_rate) }}<span class="text-muted fs-6">/hari</span>
                        </h4>
                    </div>
                    <button class="btn btn-primary px-4 py-2" @click="scrollToBooking" :disabled="isCarUnavailable">
                        <i class="fas fa-calendar-check me-2"></i>Pesan Sekarang
                    </button>
                </div>

                <!-- Car Specifications -->
                <h5 class="fw-bold mb-3">Spesifikasi Kendaraan</h5>
                <div class="row row-cols-2 g-3">
                    <div class="col" v-for="(spec, index) in carDetails" :key="index">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-light p-2 rounded-circle me-2">
                                <i :class="specIcons[spec.label] || 'fas fa-car'" class="text-primary"></i>
                            </div>
                            <div>
                                <span class="text-muted small d-block">{{ spec.label }}</span>
                                <span class="fw-medium">{{ spec.value || 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form Card -->
        <div id="booking-section" class="card border-0 shadow-sm">
            <div class="card-header bg-primary bg-opacity-10 p-3 border-0">
                <h5 class="mb-0 fw-bold">Form Pemesanan</h5>
            </div>
            <div class="card-body p-3">
                <form @submit.prevent="">
                    <!-- Rental Dates Section -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6" v-for="(field, index) in dateFields" :key="index">
                            <label class="form-label fw-medium">{{ field.label }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar"></i></span>
                                <input type="date" v-model="rentalForm[field.model]" class="form-control"
                                    :min="field.min || today" :class="{ 'is-invalid': errors?.[field.model] }" />
                            </div>
                            <div v-if="errors?.[field.model]" class="text-danger small mt-1">
                                {{ errors[field.model] }}
                            </div>
                        </div>
                    </div>

                    <!-- Usage Type Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Jenis Penggunaan</label>
                        <div class="d-flex">
                            <div class="form-check form-check-inline flex-fill" v-for="option in usageOptions"
                                :key="option.value">
                                <input class="form-check-input" type="radio" :value="option.value"
                                    v-model="rentalForm.usage_type" :id="option.id">
                                <label class="form-check-label" :for="option.id">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded-circle me-2">
                                            <i :class="option.icon" class="text-primary"></i>
                                        </div>
                                        <span>{{ option.label }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div v-if="errors?.usage_type" class="text-danger small mt-1">
                            {{ errors.usage_type }}
                        </div>
                    </div>

                    <!-- Visual Separator -->
                    <hr class="my-4">

                    <!-- Addon Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Layanan Tambahan</label>
                        <div class="row g-2">
                            <div v-for="addon in addons" :key="addon.id" class="col-md-6">
                                <div class="card border p-2 cursor-pointer" :class="{
                                    'border-primary bg-gradient-blue-professional': rentalForm.addons === addon.id,
                                    'border-danger': errors?.addons
                                }" @click="toggleAddon(addon.id)">
                                    <div class="card-body p-2">
                                        <div class="form-check">
                                            <label class="form-check-label d-flex justify-content-between w-100"
                                                :for="'addon-' + addon.id">
                                                <span>{{ addon.name }}</span>
                                                <span class="text-dark fw-bold">{{ formatCurrency(addon.price) }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="errors?.addons" class="text-danger small mt-1">
                            {{ errors.addons }}
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                @click="rentalForm.addons = null">
                                <i class="fas fa-times me-1"></i>Tanpa Layanan Tambahan
                            </button>
                        </div>
                    </div>

                    <!-- Payment Method Tabs -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Metode Pembayaran</label>

                        <!-- Payment Tab Navigation -->
                        <ul class="nav nav-tabs nav-fill" id="paymentTabs" role="tablist">
                            <li class="nav-item" role="presentation" v-for="method in paymentTypes" :key="method.id">
                                <button
                                    :class="`nav-link d-flex align-items-center justify-content-center gap-2 ${rentalForm.payment_type === method.value ? 'active' : ''}`"
                                    :id="`${method.id}-tab`" data-bs-toggle="tab" :data-bs-target="`#${method.id}`"
                                    type="button" role="tab" @click="handlePaymentTypeChange(method.value)">
                                    <i :class="method.icon"></i> {{ method.label }}
                                </button>
                            </li>
                        </ul>

                        <!-- Payment Tab Content -->
                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="paymentTabsContent"
                            :class="{ 'border-danger': errors?.payment_method_id || errors?.payment_method }">
                            <!-- Manual Transfer Tab -->
                            <div :class="`tab-pane fade ${rentalForm.payment_type === 'manual' ? 'show active' : ''}`"
                                id="manual" role="tabpanel">
                                <select v-model="rentalForm.payment_method_id" class="form-select mb-3"
                                    aria-label="Pilih rekening bank" :class="{ 'is-invalid': errors?.payment_method_id }">
                                    <option disabled selected value="">Pilih rekening bank</option>
                                    <option v-for="method in paymentMethods" :key="method.id" :value="method.id">
                                        {{ method.bank_name }}
                                    </option>
                                </select>
                                <div v-if="errors?.payment_method_id" class="text-danger small mt-1">
                                    {{ errors.payment_method_id }}
                                </div>

                                <!-- Bank Account Details Card -->
                                <div v-if="selectedPaymentMethod" class="card bg-light border-0 mt-3 p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold">Detail Rekening</span>
                                        <span class="badge bg-primary">{{ selectedPaymentMethod.bank_name }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        <span>{{ selectedPaymentMethod.account_name }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-credit-card me-2 text-muted"></i>
                                        <span class="fw-medium">{{ selectedPaymentMethod.account_number }}</span>
                                        <button type="button" class="btn btn-sm ms-2 p-0 text-primary"
                                            @click="copyToClipboard(selectedPaymentMethod.account_number)"
                                            title="Salin nomor rekening">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Payment Proof Upload -->
                                <div class="mt-3">
                                    <label class="form-label">Bukti Pembayaran</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-upload"></i></span>
                                        <input type="file" @change="handlePaymentProof" class="form-control"
                                            accept="image/*" :class="{ 'is-invalid': errors?.payment_proof }" />
                                    </div>
                                    <div class="form-text">Upload bukti transfer pembayaran Anda</div>
                                    <div v-if="errors?.payment_proof" class="text-danger small mt-1">
                                        {{ errors.payment_proof }}
                                    </div>
                                </div>
                            </div>

                            <!-- Tripay Online Payment Tab -->
                            <div :class="`tab-pane fade ${rentalForm.payment_type === 'tripay' ? 'show active' : ''}`"
                                id="tripay" role="tabpanel">
                                <div v-if="loadingTripay" class="d-flex justify-content-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                                <div v-else class="row g-3">
                                    <div v-for="channel in tripayChannels" :key="channel.code" class="col-md-6">
                                        <div class="card border h-100 cursor-pointer" :class="{
                                            'border-primary bg-gradient-blue-professional': rentalForm.payment_method === channel.code,
                                            'border-danger': errors?.payment_method
                                        }" @click="rentalForm.payment_method = channel.code">
                                            <div class="card-body p-3">
                                                <div class="form-check">
                                                    <input type="radio" :value="channel.code"
                                                        v-model="rentalForm.payment_method" class="form-check-input"
                                                        :id="'payment-' + channel.code"
                                                        :class="{ 'is-invalid': errors?.payment_method }" @click.stop />
                                                    <label class="form-check-label d-flex align-items-center w-100"
                                                        :for="'payment-' + channel.code">
                                                        <img :src="channel.icon_url" alt="Payment icon" class="me-2"
                                                            style="height: 24px;">
                                                        <span class="text-dark">{{ channel.name }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="errors?.payment_method" class="text-danger small mt-1">
                                    {{ errors.payment_method }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Summary Card -->
                    <div class="card bg-primary bg-opacity-10 border-0 p-3 mb-4" v-if="hasRentalDates">
                        <h6 class="fw-bold mb-3">Ringkasan Biaya</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Biaya Sewa ({{ calculatedRental.totalDays }} hari)</span>
                            <span>{{ formatCurrency(calculatedRental.baseRentalFee) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" v-if="rentalForm.usage_type === 'out_of_city'">
                            <span>Biaya Luar Kota (20%)</span>
                            <span>{{ formatCurrency(calculatedRental.additionalFee) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" v-if="rentalForm.addons">
                            <span>Layanan Tambahan</span>
                            <span>{{ formatCurrency(calculatedRental.addonsTotalFee) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Biaya</span>
                            <span class="text-primary fs-5">{{ formatCurrency(calculatedRental.totalFee) }}</span>
                        </div>
                    </div>

                    <!-- Validasi Error Summary -->
                    <div v-if="Object.keys(errors || {}).length > 0" class="alert alert-danger mb-4">
                        <strong><i class="fas fa-exclamation-circle me-2"></i>Form belum lengkap!</strong>
                        <p class="mb-0 mt-1">Mohon lengkapi semua data yang diperlukan.</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-medium"
                        :disabled="isCarUnavailable || isSubmitting">
                        <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status"
                            aria-hidden="true"></span>
                        <i v-else class="fas fa-check-circle me-2"></i>
                        Konfirmasi Pesanan
                    </button>
                </form>
            </div>
        </div>
        <div class="pb-3 mb-3"></div>

    </MainLayout>
</template>

    <script>
    import { reactive, computed, ref } from "vue";
    import formatCurrency from "../../../utils/formatCurrency";
    import MainLayout from '../../../Layouts/MainLayout.vue';
    import Swal from "sweetalert2";
    import axios from "axios";

    export default {
        components: {
            MainLayout,
        },
        props: {
            car: Object,
            paymentMethods: Array,
            addons: Array,
            errors: { type: Object, default: () => ({}) },
            siteSetting: {
                type: Object,
                required: true
            },
        },
        setup(props) {
            const today = new Date().toISOString().split("T")[0];
            const tripayChannels = ref([]);
            const loadingTripay = ref(false);
            const isSubmitting = ref(false);

            // Reactive form state
            const rentalForm = reactive({
                car_id: props.car.id,
                rental_start_date: null,
                rental_end_date: null,
                usage_type: "in_city",
                payment_type: "manual",
                payment_method_id: null,
                payment_method: null,
                payment_proof: null,
                addons: null,
            });

            // Static data
            const dateFields = [
                { label: "Tanggal Mulai Sewa", model: "rental_start_date", min: today },
                { label: "Tanggal Selesai Sewa", model: "rental_end_date", min: today },
            ];

            const usageOptions = [
                { id: "in-city", value: "in_city", label: "Dalam Kota", icon: "fas fa-city" },
                { id: "out-city", value: "out_of_city", label: "Luar Kota", icon: "fas fa-road" }
            ];

            const paymentTypes = [
                { id: "manual", value: "manual", label: "Transfer Manual", icon: "fas fa-university" },
                { id: "tripay", value: "tripay", label: "Pembayaran Online", icon: "fas fa-credit-card" }
            ];

            const specIcons = {
                'Transmisi': 'fas fa-cog',
                'Jenis Bahan Bakar': 'fas fa-gas-pump',
                'Kapasitas Penumpang': 'fas fa-users',
                'Tahun': 'fas fa-calendar',
                'Status': 'fas fa-info-circle'
            };

            // Computed properties
            const isCarUnavailable = computed(() =>
                props.car.status === 'rented' || props.car.status === 'maintenance'
            );

            const statusBadgeClasses = computed(() => {
                const baseClass = 'badge ';
                switch (props.car.status) {
                    case 'available': return baseClass + 'bg-success';
                    case 'rented': return baseClass + 'bg-danger';
                    case 'maintenance': return baseClass + 'bg-warning';
                    default: return baseClass + 'bg-secondary';
                }
            });

            const quickInfoItems = computed(() => [
                { icon: "fa-calendar", value: props.car.year || "N/A" },
                { icon: "fa-gas-pump", value: props.car.fuel_type || "Bensin" },
                { icon: "fa-cog", value: props.car.transmission || "N/A" },
                { icon: "fa-users", value: `${props.car.passenger_capacity || 0} Orang` },
            ]);

            const carDetails = computed(() => [
                { label: "Transmisi", value: props.car.transmission },
                { label: "Jenis Bahan Bakar", value: props.car.fuel_type },
                { label: "Kapasitas Penumpang", value: props.car.passenger_capacity },
                { label: "Tahun", value: props.car.year },
                { label: "Status", value: props.car.status },
            ]);

            const selectedPaymentMethod = computed(() =>
                props.paymentMethods.find(method => method.id === rentalForm.payment_method_id)
            );

            const hasRentalDates = computed(() =>
                rentalForm.rental_start_date && rentalForm.rental_end_date
            );

            // Methods
            const handlePaymentTypeChange = (type) => {
                rentalForm.payment_type = type;
                if (type === 'tripay' && tripayChannels.value.length === 0) {
                    loadTripayChannels();
                }
            };

            const loadTripayChannels = async () => {
                if (tripayChannels.value.length > 0) return;

                loadingTripay.value = true;
                try {
                    const response = await axios.get('/payment-channels');
                    if (response.data && response.data.data) {
                        tripayChannels.value = response.data.data;
                    } else {
                        console.error('Invalid Tripay response format');
                    }
                } catch (error) {
                    console.error('Failed to load Tripay payment channels:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal memuat metode pembayaran online. Silakan coba lagi nanti.',
                        icon: 'error',
                    });
                } finally {
                    loadingTripay.value = false;
                }
            };

            const calculateRentalFee = () => {
                if (!hasRentalDates.value) {
                    return { totalDays: 0, baseRentalFee: 0, additionalFee: 0, addonsTotalFee: 0, totalFee: 0 };
                }

                const startDate = new Date(rentalForm.rental_start_date);
                const endDate = new Date(rentalForm.rental_end_date);

                // Calculate days difference (adding 1 to include the start day)
                const diffTime = Math.abs(endDate - startDate);
                const totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                // Calculate base rental fee
                const baseRentalFee = Number(props.car.daily_rate) * totalDays;

                // Calculate additional fee for out-of-city usage
                const additionalFee = rentalForm.usage_type === "out_of_city" ? baseRentalFee * 0.2 : 0;

                // Find selected addon and get its price
                const addon = props.addons.find((a) => a.id === rentalForm.addons);
                const addonsTotalFee = addon ? Number(addon.price) : 0;

                // Calculate the total fee
                const totalFee = baseRentalFee + additionalFee + addonsTotalFee;

                return {
                    totalDays,
                    baseRentalFee,
                    additionalFee,
                    addonsTotalFee,
                    totalFee
                };
            };

            const calculatedRental = computed(() => calculateRentalFee());

            const handlePaymentProof = (event) => {
                const file = event.target.files[0];
                if (file) {
                    rentalForm.payment_proof = file;
                }
            };

            const toggleAddon = (addonId) => {
                rentalForm.addons = rentalForm.addons === addonId ? null : addonId;
            };

            const copyToClipboard = (text) => {
                navigator.clipboard.writeText(text);
                Swal.fire({
                    title: 'Disalin!',
                    text: 'Nomor rekening berhasil disalin ke clipboard',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            };

            const scrollToBooking = () => {
                document.getElementById('booking-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            };


            // Add CSS for cursor pointer
            const style = document.createElement('style');
            style.textContent = '.cursor-pointer { cursor: pointer; }';
            document.head.appendChild(style);

            return {
                today,
                rentalForm,
                dateFields,
                usageOptions,
                paymentTypes,
                carDetails,
                quickInfoItems,
                specIcons,
                selectedPaymentMethod,
                calculatedRental,
                handlePaymentProof,
                formatCurrency,
                scrollToBooking,
                tripayChannels,
                loadingTripay,
                isSubmitting,
                isCarUnavailable,
                statusBadgeClasses,
                hasRentalDates,
                handlePaymentTypeChange,
                copyToClipboard,
                toggleAddon,
            };
        },
    };
</script>
