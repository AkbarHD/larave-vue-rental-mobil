<template>

  <Head>
    <title>Homepage - {{ siteSetting.site_name }}</title>
  </Head>

  <MainLayout :siteSetting="siteSetting">
    <!-- Main Carousel: Cleaner with better proportions -->
    <div id="mainCarousel" class="carousel slide rounded-4 overflow-hidden shadow-sm mb-4">
      <div class="carousel-inner">
        <div v-for="(slider, index) in sliders" :key="slider.id"
          :class="['carousel-item', index === 0 ? 'active' : '']">
          <img :src="slider.image" class="d-block w-100" style="object-fit: cover; height: 240px;border-radius: 10px;"
            :alt="'Slider ' + (index + 1)" />
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <!-- Category Filter: More minimal and compact -->
    <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
      <button v-for="category in categories" :key="category.id" :class="[
        'btn',
        selectedCategory === category.id ? 'btn-primary' : 'btn-outline-secondary',
        'rounded-pill px-3 py-1 btn-sm'
      ]" @click="filterCarsByCategory(category.id)">
        {{ category.name }}
      </button>
    </div>

    <!-- Car Listings: Cleaner cards with better spacing -->
    <section class="mb-4">
      <h3 class="fw-medium mb-3 fs-5">Daftar Mobil</h3>

      <div v-if="filteredCars.length > 0">
        <div v-for="car in filteredCars" :key="car.id" class="card border-1 shadow mb-3">
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
                  <Link :href="`/car/${car.slug}`" class="btn btn-primary w-100 btn-sm rounded-pill">Lihat Detail</Link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-4 bg-light rounded-3">
        <i class="fas fa-comment-slash text-muted opacity-50 fs-4 mb-3 d-block"></i>
        <p class="text-muted">Tidak ada mobil tersedia untuk kategori ini.</p>
      </div>
    </section>

    <section class="mb-4">
      <h3 class="fw-medium mb-3 fs-5">Review Customer</h3>

      <div v-if="reviews && reviews.length > 0">
        <div id="reviewCarousel" class="carousel slide">
          <div class="carousel-inner">
            <div v-for="n in Math.ceil(reviews.length / 2)" :key="n"
              :class="['carousel-item', n === 1 ? 'active' : '']">
              <div class="row g-4">
                <!-- First review -->
                <div class="col-md-6">
                  <div class="card border-1 shadow rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                      <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                          <div
                            class="bg-primary bg-opacity-10 rounded-pill p-2 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-primary fs-5"></i>
                          </div>
                        </div>
                        <div>
                          <h5 class="mb-1 fw-semibold text-dark">{{ reviews[(n - 1) * 2].user.name }}</h5>
                          <p class="text-muted small mb-0">{{ formatDate(reviews[(n - 1) * 2].created_at) }}</p>
                        </div>
                      </div>

                      <!-- Rating stars -->
                      <div class="mb-3">
                        <template v-for="i in 5" :key="i">
                          <i
                            :class="['fas fa-star', i <= getNumericRating(reviews[(n - 1) * 2].rating) ? 'text-warning' : 'text-secondary opacity-25']"></i>
                        </template>
                      </div>

                      <p class="text-muted mb-3 small">{{ reviews[(n - 1) * 2].comment }}</p>

                      <div class="small bg-light py-2 rounded-pill d-inline-block">
                        <span class="text-primary fw-medium">Mobil: </span>
                        <span class="text-dark">{{ reviews[(n - 1) * 2].rental.car.brand }}}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Second review -->
                <div v-if="(n - 1) * 2 + 1 < reviews.length" class="col-md-6">
                  <div class="card border-1 shadow rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                      <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                          <div
                            class="bg-primary bg-opacity-10 rounded-pill p-2 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-primary fs-5"></i>
                          </div>
                        </div>
                        <div>
                          <h5 class="mb-1 fw-semibold text-dark">{{ reviews[(n - 1) * 2 + 1].user.name }}</h5>
                          <p class="text-muted small mb-0">{{ formatDate(reviews[(n - 1) * 2 + 1].created_at) }}</p>
                        </div>
                      </div>

                      <!-- Rating stars -->
                      <div class="mb-3">
                        <template v-for="i in 5" :key="i">
                          <i
                            :class="['fas fa-star', i <= getNumericRating(reviews[(n - 1) * 2 + 1].rating) ? 'text-warning' : 'text-secondary opacity-25']"></i>
                        </template>
                      </div>

                      <p class="text-muted mb-3 small">{{ reviews[(n - 1) * 2 + 1].comment }}</p>

                      <div class="small bg-light py-2  rounded-pill d-inline-block">
                        <span class="text-primary fw-medium">Mobil: </span>
                        <span class="text-dark">{{ reviews[(n - 1) * 2 + 1].rental.car.brand }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Carousel Controls -->
          <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-outline-primary mx-2 rounded-pill" type="button" data-bs-target="#reviewCarousel"
              data-bs-slide="prev" style="width: 42px; height: 42px;">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button class="btn btn-outline-primary mx-2 rounded-pill" type="button" data-bs-target="#reviewCarousel"
              data-bs-slide="next" style="width: 42px; height: 42px;">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-5 bg-light rounded-4 shadow-sm">
        <i class="fas fa-comment-slash text-muted opacity-50 fs-4 mb-3 d-block"></i>
        <p class="text-muted">Belum ada review saat ini.</p>
      </div>
    </section>

    <!-- Banner Section: Call to Action -->
    <section class="mb-4">
      <div class="bg-info rounded-3">
        <div class="row card-body p-3 position-relative">
          <!-- Action box -->
          <div class="col-11 mx-auto position-relative">
            <div class="row align-items-center">
              <div class="col-lg-7">
                <h4 class="text-white">Ingin Perjalanan yang Sempurna?</h4>
                <p class="text-white mb-3 mb-lg-0">Konsultasikan dengan Kami sekarang!</p>
              </div>
              <div class="col-lg-5 text-lg-end">
                <a href="#" class="btn btn-outline-warning mb-0">Hubungi Kami</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </MainLayout>
</template>

<script>
import { ref, computed } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import formatDate from '../../../utils/formatDate';
import formatCurrency from '../../../utils/formatCurrency';
import MainLayout from '../../../Layouts/MainLayout.vue';

export default {
  components: {
    Link,
    Head,
    MainLayout
  },
  props: {
    cars: {
      type: Array,
      required: true
    },
    sliders: {
      type: Array,
      required: true
    },
    reviews: {
      type: Array,
      required: true
    },
    siteSetting: {
      type: Object,
      required: true
    },
    categories: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  setup(props) {
    const selectedCategory = ref(props.categories.length > 0 ? props.categories[0].id : null);
    const filteredCars = computed(() => {
      if (selectedCategory.value) {
        return props.cars.filter(car => car.category_id === selectedCategory.value);
      }
      return props.cars;
    });

    const filterCarsByCategory = (categoryId) => {
      selectedCategory.value = categoryId;
    };

    // Fungsi untuk mengkonversi rating ke angka
    const getNumericRating = (rating) => {
      if (rating === undefined || rating === null) return 0;
      const numRating = parseFloat(rating);
      return isNaN(numRating) ? 0 : Math.round(numRating);
    };

    return {
      formatDate,
      formatCurrency,
      filteredCars,
      selectedCategory,
      filterCarsByCategory,
      getNumericRating
    };
  }
};
</script>
