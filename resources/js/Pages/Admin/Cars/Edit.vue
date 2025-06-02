<template>
    <Head>
        <title>Edit Car - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/cars" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-edit"></i> Edit Car</h5>
                        <hr />
                        <form @submit.prevent="submit">
                            <div class="row">
                                <!-- Column 1: Car Details -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Brand</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Car Brand"
                                            v-model="form.brand"
                                        />
                                        <div v-if="errors.brand" class="alert alert-danger mt-2">
                                            {{ errors.brand }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Model</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Car Model"
                                            v-model="form.model"
                                        />
                                        <div v-if="errors.model" class="alert alert-danger mt-2">
                                            {{ errors.model }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>License Plate</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter License Plate"
                                            v-model="form.license_plate"
                                        />
                                        <div v-if="errors.license_plate" class="alert alert-danger mt-2">
                                            {{ errors.license_plate }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Transmission</label>
                                        <select class="form-select" v-model="form.transmission">
                                            <option value="manual">Manual</option>
                                            <option value="automatic">Automatic</option>
                                        </select>
                                        <div v-if="errors.transmission" class="alert alert-danger mt-2">
                                            {{ errors.transmission }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Year</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            placeholder="Enter Car Year"
                                            v-model="form.year"
                                        />
                                        <div v-if="errors.year" class="alert alert-danger mt-2">
                                            {{ errors.year }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 2: Fuel and Rate Details -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Category</label>
                                        <select
                                            class="form-select"
                                            v-model="form.category_id"
                                            required
                                        >
                                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                                {{ category.name }}
                                            </option>
                                        </select>
                                        <div v-if="errors.category_id" class="alert alert-danger mt-2">
                                            {{ errors.category_id }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Daily Rate</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            placeholder="Enter Daily Rate"
                                            v-model="form.daily_rate"
                                        />
                                        <div v-if="errors.daily_rate" class="alert alert-danger mt-2">
                                            {{ errors.daily_rate }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Penalty per Day</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            placeholder="Enter Penalty per Day"
                                            v-model="form.penalty_rate_per_day"
                                        />
                                        <div v-if="errors.penalty_rate_per_day" class="alert alert-danger mt-2">
                                            {{ errors.penalty_rate_per_day }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Passenger Capacity</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            placeholder="Enter Passenger Capacity"
                                            v-model="form.passenger_capacity"
                                        />
                                        <div v-if="errors.passenger_capacity" class="alert alert-danger mt-2">
                                            {{ errors.passenger_capacity }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Fuel Type</label>
                                        <select class="form-select" v-model="form.fuel_type">
                                            <option value="gasoline">Gasoline</option>
                                            <option value="diesel">Diesel</option>
                                            <option value="electric">Electric</option>
                                        </select>
                                        <div v-if="errors.fuel_type" class="alert alert-danger mt-2">
                                            {{ errors.fuel_type }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea
                                            class="form-control"
                                            rows="5"
                                            placeholder="Enter Car Description"
                                            v-model="form.description"
                                        ></textarea>
                                        <div v-if="errors.description" class="alert alert-danger mt-2">
                                            {{ errors.description }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label>Car Image</label>
                                        <input
                                            type="file"
                                            class="form-control"
                                            @change="handleImageUpload"
                                        />
                                        <div v-if="errors.image" class="alert alert-danger mt-2">
                                            {{ errors.image }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from "vue";
import LayoutAdmin from "../../../Layouts/Admin.vue";
import Swal from "sweetalert2";
import { router, Link } from "@inertiajs/vue3";

export default {
    layout: LayoutAdmin,

    props: {
        errors: Object,
        car: Object,
        categories: Array, // Pass the categories list to the form
    },
    components: {
        Link,
    },
    setup(props) {
        const form = reactive({
            brand: props.car.brand || "",
            model: props.car.model || "",
            license_plate: props.car.license_plate || "",
            transmission: props.car.transmission || "",
            year: props.car.year || "",
            daily_rate: props.car.daily_rate || "",
            penalty_rate_per_day: props.car.penalty_rate_per_day || "",
            passenger_capacity: props.car.passenger_capacity || "",
            fuel_type: props.car.fuel_type || "",
            description: props.car.description || "",
            category_id: props.car.category_id || "", // Add category_id to the form
            image: null,
        });

        const handleImageUpload = (event) => {
            const file = event.target.files[0];
            if (file && file.type.match("image.*")) {
                form.image = file;
            } else {
                Swal.fire({
                    title: "Error",
                    text: "File must be an image!",
                    icon: "error",
                });
            }
        };

        const submit = () => {
            const formData = new FormData();
            formData.append("brand", form.brand || "");
            formData.append("model", form.model || "");
            formData.append("license_plate", form.license_plate || "");
            formData.append("transmission", form.transmission || "");
            formData.append("year", form.year || "");
            formData.append("daily_rate", form.daily_rate || "");
            formData.append("penalty_rate_per_day", form.penalty_rate_per_day || "");
            formData.append("passenger_capacity", form.passenger_capacity || "");
            formData.append("fuel_type", form.fuel_type || "");
            formData.append("description", form.description || "");
            formData.append("category_id", form.category_id || ""); // Add category_id to formData

            if (form.image) {
                formData.append("image", form.image);
            }

            formData.append("_method", "PUT");

            router.post(`/admin/cars/${props.car.id}`, formData, {
                forceFormData: true,
                onSuccess: () => {
                    Swal.fire({
                        title: "Success!",
                        text: "Car berhasil diperbarui!",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                },
            });
        };

        return {
            form,
            handleImageUpload,
            submit,
        };
    },
};
</script>
