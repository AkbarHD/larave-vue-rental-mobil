<template>
    <header class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img v-if="siteSetting.logo" :src="siteSetting.logo" alt="Logo" class="rounded me-2" style="height: 40px;">
            <span class="fw-bold">{{ siteSetting.site_name }}</span>
        </div>
    </header>
</template>

<script>
export default {
    name: 'HeaderVue',
    props: {
        siteSetting: {
            type: Object,
            required: true
        }
    }
}
</script>
