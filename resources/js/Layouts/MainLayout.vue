<template>
    <div class="col-md-8 col-lg-6 col-xxl-4 mx-auto p-3 bg-white shadow-sm rounded-3 mb-5 pb-5">
        <!-- Header Component -->
        <HeaderVue :siteSetting="siteSetting" />

        <!-- Main Content Slot -->
        <slot></slot>

        <!-- Footer Component  -->
        <FooterVue :siteSetting="siteSetting" />

        <div class="pb-5 mb-4"></div>
    </div>

    <Menu />
</template>

<script>
import HeaderVue from '../Components/Header.vue';
import FooterVue from '../Components/Footer.vue';
import Menu from '../Components/Menu.vue';

export default {
    name: 'MainLayout',
    components: {
        HeaderVue,
        FooterVue,
        Menu
    },
    props: {
        siteSetting: {
            type: Object,
            required: true
        }
    }
};
</script>
