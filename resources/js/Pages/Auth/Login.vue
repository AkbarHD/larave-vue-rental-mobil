<template>

    <Head>
        <title>Login - Rental Mobil</title>
    </Head>
    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
        <div class="text-center text-md-center mb-4 mt-md-0">
            <h3>Login</h3>
        </div>
        <form @submit.prevent="submit" class="mt-4">
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

            <div class="form-group">
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

                <div class="d-flex justify-content-between align-items-top mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="remember">
                        <label class="form-check-label mb-0" for="remember">
                            Remember me
                        </label>
                    </div>
                </div>

            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-gray-800">LOGIN</button>
            </div>
        </form>

        <!-- Link Forgot Password -->
        <div class="text-center mt-3">
            <a href="/forgot-password">Forgot Password?</a>
        </div>

        <div class="text-center mt-3">
            <p>Don't have an account? <a href="/register" class="text-primary">Register here</a></p>
        </div>

    </div>
</template>

<script>
//import layout
import LayoutAuth from '../../Layouts/Auth.vue';

//import Inertia
import {
    Head,
    router
} from '@inertiajs/vue3';

//import reactive
import {
    reactive
} from 'vue';

export default {

    //layout
    layout: LayoutAuth,

    //register component
    components: {
        Head
    },

    //props
    props: {
        errors: Object,
    },

    //define composition API
    setup() {

        //define form state
        const form = reactive({
            email: '',
            password: '',
        });

        //submit method
        const submit = () => {

            //send data to server
            router.post('/login', {

                //data
                email: form.email,
                password: form.password,
            });
        }

        //return form state and submit method
        return {
            form,
            submit,
        };

    }

}

</script>
