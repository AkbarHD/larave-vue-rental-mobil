<template>

    <Head>
        <title>Create Car - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/cars" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i
                    class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5 class="mb-3">Create a New Car</h5>
                        <form @submit.prevent="submit">
                            <div class="row">
                                <!-- Column 1: Car Details -->
                                <div class="col-md-6">
                                    <!-- Category Selection -->
                                    <div class="form-group mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select id="category_id" v-model="formData.category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            <option v-for="category in categories" :key="category.id"
                                                :value="category.id">
                                                {{ category.name }}
                                            </option>
                                        </select>
                                        <div v-if="errors.category_id" class="alert alert-danger mt-2">
                                            {{ errors.category_id }}
                                        </div>
                                    </div>

                                    <!-- Other fields for Car (brand, model, etc.) -->
                                    <div class="form-group mb-3">
                                        <label for="brand" class="form-label">Brand</label>
                                        <input type="text" id="brand" v-model="formData.brand" class="form-control" />
                                        <div v-if="errors.brand" class="alert alert-danger mt-2">
                                            {{ errors.brand }}
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="model" class="form-label">Model</label>
                                        <input type="text" id="model" v-model="formData.model" class="form-control" />
                                        <div v-if="errors.model" class="alert alert-danger mt-2">
                                            {{ errors.model }}
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="license_plate" class="form-label">License Plate</label>
                                        <input type="text" id="license_plate" v-model="formData.license_plate"
                                            class="form-control" />
                                        <div v-if="errors.license_plate" class="alert alert-danger mt-2">
                                            {{ errors.license_plate }}
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="year" class="form-label">Year</label>
                                        <input type="number" id="year" v-model="formData.year" class="form-control" />
                                        <div v-if="errors.year" class="alert alert-danger mt-2">
                                            {{ errors.year }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 2: Fuel and Rate Details -->
                                <div class="col-md-6">
                                    <!-- Fuel Type, Transmission, etc. -->
                                    <div class="form-group mb-3">
                                        <label for="fuel_type" class="form-label">Fuel Type</label>
                                        <select id="fuel_type" v-model="formData.fuel_type" class="form-control">
                                            <option value="">Pilih Fuel Type</option>
                                            <option value="gasoline">Gasoline</option>
                                            <option value="diesel">Diesel</option>
                                            <option value="electric">Electric</option>
                                        </select>
                                        <div v-if="errors.fuel_type" class="alert alert-danger mt-2">
                                            {{ errors.fuel_type }}
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="transmission" class="form-label">Transmission</label>
                                        <select id="transmission" v-model="formData.transmission" class="form-control">
                                            <option value="">Pilih Transmission</option>
                                            <option value="manual">Manual</option>
                                            <option value="automatic">Automatic</option>
                                        </select>
                                        <div v-if="errors.transmission" class="alert alert-danger mt-2">
                                            {{ errors.transmission }}
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="daily_rate" class="form-label">Daily Rate</label>
                                        <input type="number" id="daily_rate" v-model="formData.daily_rate"
                                            class="form-control" />
                                        <div v-if="errors.daily_rate" class="alert alert-danger mt-2">
                                            {{ errors.daily_rate }}
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="passenger_capacity" class="form-label">Passenger Capacity</label>
                                        <input type="number" id="passenger_capacity"
                                            v-model="formData.passenger_capacity" class="form-control" />
                                        <div v-if="errors.passenger_capacity" class="alert alert-danger mt-2">
                                            {{ errors.passenger_capacity }}
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="penalty_rate_per_day" class="form-label">Penalty Rate Per
                                            Day</label>
                                        <input type="number" id="penalty_rate_per_day"
                                            v-model="formData.penalty_rate_per_day" class="form-control" />
                                        <div v-if="errors.penalty_rate_per_day" class="alert alert-danger mt-2">
                                            {{ errors.penalty_rate_per_day }}
                                        </div>
                                    </div>

                                    <!-- Car Image -->
                                    <div class="form-group mb-3">
                                        <label for="image" class="form-label">Car Image</label>
                                        <input type="file" id="image" class="form-control" @change="handleFileUpload" />
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue';
import LayoutAdmin from "../../../Layouts/Admin.vue";
import { router, Link } from '@inertiajs/vue3';
import Swal from "sweetalert2";

export default {
    layout: LayoutAdmin,
    components: {
        Link,
    },

    props: {
        categories: Array,
        errors: Object,
    },

    setup() {
        const formData = reactive({
            category_id: '',
            brand: '',
            model: '',
            license_plate: '',
            year: '',
            fuel_type: '',
            transmission: '',
            passenger_capacity: '',
            daily_rate: '',
            penalty_rate_per_day: '',
            image: null
        });

        const handleFileUpload = (event) => {
            formData.image = event.target.files[0];
        };

        const submit = () => {
            const payload = new FormData();
            for (const key in formData) {
                payload.append(key, formData[key]);
            }

            router.post('/admin/cars', payload, {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Car has been added successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                    });
                },
            });
        };

        return {
            formData,
            handleFileUpload,
            submit,
        };
    }
};
</script>
