<template>

    <Head>
        <title>Verifikasi Email - Rental Mobil</title>
    </Head>
    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
        <div class="text-center text-md-center mb-4 mt-md-0">
            <h3>Verifikasi Email</h3>
        </div>

        <div class="mb-4">
            Silakan verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan.
            Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang baru.
        </div>

        <div v-if="status === 'verification-link-sent'" class="alert alert-success mb-4">
            Link verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.
        </div>

        <div class="d-flex justify-content-between mt-4">
            <form @submit.prevent="sendVerification">
                <button type="submit" class="btn btn-gray-800">
                    <i class="fa fa-envelope me-2"></i>Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form @submit.prevent="logout">
                <button type="submit" class="btn btn-link text-gray-600">
                    <i class="fa fa-sign-out me-2"></i>Keluar
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import LayoutAuth from '../../Layouts/Auth.vue';
import { Head, router } from '@inertiajs/vue3';

export default {
    layout: LayoutAuth,
    components: {
        Head,
    },
    props: {
        status: String,
    },
    methods: {
        sendVerification() { 
            router.post('/email/verification-notification');
        },
        logout() {
            router.post('/logout');
        }
    }
};
</script>
