<template>

    <Head>
        <title>Daftar Mobil - {{ siteSetting.site_name }}</title>
    </Head>

    <MainLayout :siteSetting="siteSetting">
        <section class="mb-4">
            <h3 class="fw-medium mb-3 fs-5">Daftar Mobil</h3>

            <!-- Display Cars -->
            <div v-if="displayedCars.length > 0">
                <div v-for="car in displayedCars" :key="car.id" class="card border-1 shadow mb-3">
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-4">
                                <img :src="car.image" class="rounded img-fluid w-100" :alt="car.model" />
                            </div>
                            <div class="col-12 col-md-5">
                                <h4 class="fw-bold h6 mb-1">{{ car.brand }}</h4>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-secondary me-1 small"></i>
                                        <span class="small">{{ car.year }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-cog text-secondary me-1 small"></i>
                                        <span class="small">{{ car.transmission }}</span>
                                    </div>
                                </div>
                                <p class="text-danger fw-bold mb-0">{{ formatCurrency(car.daily_rate) }}<span
                                        class="text-muted fs-6">/hari</span></p>
                            </div>
                            <div class="col-12 col-md-3">
                                <Link :href="`/car/${car.slug}`" class="btn btn-primary w-100 btn-sm rounded-pill">
                                Lihat Detail</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-4 bg-light rounded-3">
                <i class="fas fa-comment-slash text-muted opacity-50 fs-4 mb-3 d-block"></i>
                <p class="text-muted">Tidak ada mobil tersedia saat ini.</p>
            </div>

            <!-- Load More Button with Spinner -->
            <div v-if="!loading && hasMoreCars" class="mt-3">
                <button @click="loadMoreCars" class="btn btn-primary w-100 btn-sm rounded-pill" :disabled="loadingMore">
                    <span v-if="loadingMore">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Loading...
                    </span>
                    <span v-else>Load More</span>
                </button>
            </div>

            <!-- Loading Indicator -->
            <div v-if="loading" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Loading...</p>
            </div>
        </section>
    </MainLayout>
</template>

<script>
import MainLayout from '../../../Layouts/MainLayout.vue';
import formatCurrency from '../../../utils/formatCurrency';
import { ref, computed, onMounted } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    components: {
        Link,
        Head,
        MainLayout,
    },
    props: {
        cars: {
            type: Object,
            required: true,
        },
        siteSetting: {
            type: Object,
            required: true
        },
    },
    setup(props) {
        const displayedCars = ref([]);
        const loading = ref(true);
        const loadingMore = ref(false);
        const currentPage = ref(1);

        onMounted(() => {
            displayedCars.value = [...props.cars.data];
            loading.value = false;
        });

        const hasMoreCars = computed(() => currentPage.value < props.cars.last_page);

        const loadMoreCars = () => {
            if (loadingMore.value || !hasMoreCars.value) return;
            loadingMore.value = true;
            const nextPage = currentPage.value + 1;

            axios.get('/load-more-cars', { params: { page: nextPage } })
                .then(response => {
                    if (response.data.cars?.data?.length) {
                        displayedCars.value = [...displayedCars.value, ...response.data.cars.data];
                        currentPage.value = nextPage;
                    }
                })
                .finally(() => {
                    setTimeout(() => {
                        loadingMore.value = false;
                    }, 300);
                });
        };

        return {
            displayedCars,
            loading,
            loadingMore,
            hasMoreCars,
            loadMoreCars,
            formatCurrency
        };
    },
};
</script>
