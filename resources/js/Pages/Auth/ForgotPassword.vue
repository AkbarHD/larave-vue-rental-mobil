<template>
    <Head>
        <title>Forgot Password - Rental Mobil</title>
    </Head>
    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
        <div class="text-center text-md-center mb-4 mt-md-0">
            <h3>Forgot Your Password?</h3>
        </div>

        <form @submit.prevent="submit" class="mt-4">
            <div class="form-group mb-4">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input type="email" class="form-control" v-model="form.email" placeholder="Enter your email">
                </div>
                <div v-if="form.errors.email" class="alert alert-danger mt-2">
                    {{ form.errors.email }}
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gray-800" :disabled="form.processing">
                    <span v-if="form.processing">Sending...</span>
                    <span v-else>Send Reset Link</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import LayoutAuth from '../../Layouts/Auth.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Swal from "sweetalert2";

export default {
    layout: LayoutAuth,
    components: {
        Head,
    },
    setup() {
        const form = useForm({
            email: '',
        });

        const submit = () => {
            form.post('/forgot-password', {
                onSuccess: () => {
                    form.reset();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Password reset link has been sent to your email.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
            });
        };

        return {
            form,
            submit,
        };
    },
};
</script>
