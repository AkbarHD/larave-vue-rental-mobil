<template>
    <Head>
        <title>Register - Rental Mobil</title>
    </Head>
    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
        <div class="text-center text-md-center mb-4 mt-md-0">
            <h3>Register</h3>
        </div>
        <form @submit.prevent="submit" class="mt-4">
            <!-- Name Input -->
            <div class="form-group mb-4">
                <label for="name">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-user"></i>
                    </span>
                    <input type="text" class="form-control" v-model="form.name" placeholder="Full Name">
                </div>
                <div v-if="errors.name" class="alert alert-danger mt-2">
                    {{ errors.name }}
                </div>
            </div>


            <!-- Email Input -->
            <div class="form-group mb-4">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input type="email" class="form-control" v-model="form.email" placeholder="Email Address">
                </div>
                <div v-if="errors.email" class="alert alert-danger mt-2">
                    {{ errors.email }}
                </div>
            </div>

            <!-- Password Input -->
            <div class="form-group mb-4">
                <label for="password">Password</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon2">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" placeholder="Password" class="form-control" v-model="form.password">
                </div>
                <div v-if="errors.password" class="alert alert-danger mt-2">
                    {{ errors.password }}
                </div>
            </div>

            <!-- Password Confirmation Input -->
            <div class="form-group mb-4">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon3">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" placeholder="Confirm Password" class="form-control" v-model="form.password_confirmation">
                </div>
                <div v-if="errors.password_confirmation" class="alert alert-danger mt-2">
                    {{ errors.password_confirmation }}
                </div>
            </div>

            <!-- Gender Input -->
            <div class="form-group mb-4">
                <label for="gender">Gender</label>
                <select class="form-control" v-model="form.gender">
                    <option value="">Select Gender</option>
                    <option value="Pria">Pria</option>
                    <option value="Wanita">Wanita</option>
                </select>
                <div v-if="errors.gender" class="alert alert-danger mt-2">
                    {{ errors.gender }}
                </div>
            </div>

            <!-- Address Input -->
            <div class="form-group mb-4">
                <label for="address">Address</label>
                <textarea class="form-control" v-model="form.address" placeholder="Enter your address"></textarea>
                <div v-if="errors.address" class="alert alert-danger mt-2">
                    {{ errors.address }}
                </div>
            </div>

            <!-- WhatsApp Number Input -->
            <div class="form-group mb-4">
                <label for="whatsapp_number">WhatsApp Number</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon4">
                        <i class="fa fa-phone"></i>
                    </span>
                    <input type="text" class="form-control" v-model="form.whatsapp_number" placeholder="WhatsApp Number">
                </div>
                <div v-if="errors.whatsapp_number" class="alert alert-danger mt-2">
                    {{ errors.whatsapp_number }}
                </div>
            </div>

            <!-- Image Input -->
            <div class="form-group mb-4">
                <label for="image">Upload ID Card</label>
                <input type="file" class="form-control" @change="handleImageChange">
                <div v-if="errors.image" class="alert alert-danger mt-2">
                    {{ errors.image }}
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gray-800">REGISTER</button>
            </div>
        </form>
    </div>
</template>


<script>
    import LayoutAuth from '../../Layouts/Auth.vue';
    import { Head, router } from '@inertiajs/vue3';
    import { reactive } from 'vue';

    export default {
        layout: LayoutAuth,
        components: {
            Head
        },
        props: {
            errors: Object,
        },
        setup() {
            const form = reactive({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                gender: '',
                address: '',
                whatsapp_number: '',
                image: null,
            });

            const handleImageChange = (event) => {
                const file = event.target.files[0];
                if (file) {
                    form.image = file;
                }
            };

            const submit = () => {
                const formData = new FormData();
                formData.append('name', form.name);
                formData.append('email', form.email);
                formData.append('password', form.password);
                formData.append('password_confirmation', form.password_confirmation);
                formData.append('gender', form.gender);
                formData.append('address', form.address);
                formData.append('whatsapp_number', form.whatsapp_number);
                if (form.image) {
                    formData.append('image', form.image);
                }

                router.post('/register', formData);
            };

            return {
                form,
                submit,
                handleImageChange,
            };
        },
    };
</script>
