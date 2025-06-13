<template>
    <div>
        <div class="search-container position-relative">
            <input type="text" class="form-control form-control-sm rounded-pill" placeholder="Cari Mobil..."
                @click="openSearchModal" />
            <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted small"></i>
        </div>

        <!-- Bootstrap Modal -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cari Mobil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Search Input -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control search-input" placeholder="Ketik untuk mencari..."
                                v-model="searchQuery" ref="searchInputRef" />
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>

                        <!-- Results/Loading/Empty State -->
                        <div v-if="isLoading" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>
                        <div v-else-if="searchQuery && !searchResults.length" class="text-center text-muted py-3">
                            Tidak ada hasil yang ditemukan
                        </div>
                        <div v-else-if="searchResults.length" class="search-results list-group">
                            <a v-for="car in searchResults" :key="car.id" href="#"
                                class="list-group-item list-group-item-action" @click.prevent="selectCar(car)">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ car.brand }} {{ car.model }}</strong>
                                        <div class="small text-muted">{{ car.year }} | {{ car.transmission }} | {{
                                            car.fuel_type }}</div>
                                    </div>
                                    <div class="badge bg-primary">{{ formatCurrency(car.daily_rate) }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import formatCurrency from '../utils/formatCurrency';

const searchQuery = ref('');
const searchResults = ref([]);
const isLoading = ref(false);
const searchInputRef = ref(null);
let modal = null;
let searchTimeout = null;

const openSearchModal = () => {
    modal.show();
    document.getElementById('searchModal').addEventListener('shown.bs.modal',
        () => searchInputRef.value?.focus(), { once: true }
    );
};

const searchCars = () => {
    clearTimeout(searchTimeout);
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        isLoading.value = false;
        return;
    }

    isLoading.value = true;
    searchTimeout = setTimeout(() => {
        axios.get('/api/search', { params: { q: searchQuery.value } })
            .then(response => searchResults.value = response.data.cars)
            .catch(error => {
                searchResults.value = [];
            })
            .finally(() => isLoading.value = false);
    }, 300);
};

const selectCar = (car) => {
    window.location.href = `/car/${car.slug}`;
    modal.hide();
};

onMounted(() => {
    modal = new bootstrap.Modal(document.getElementById('searchModal'));
    document.getElementById('searchModal').addEventListener('hidden.bs.modal', () => {
        searchQuery.value = '';
        searchResults.value = [];
    });
});

watch(searchQuery, searchCars);
</script>
