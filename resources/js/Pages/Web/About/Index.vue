<template>

    <Head>
        <title>About us - {{ siteSetting.site_name }}</title>
    </Head>


    <MainLayout :siteSetting="siteSetting">
        <!-- Company Profile -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Profil Perusahaan</h4>
                <p>{{ siteSetting.description || 'Deskripsi perusahaan belum tersedia.' }}</p>
            </div>
        </div>

        <!-- Social Media -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Media Sosial</h4>
                <div class="d-flex gap-3">
                    <a v-if="siteSetting.facebook_url" :href="siteSetting.facebook_url" target="_blank"
                        class="btn d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                        style="width: 40px; height: 40px; background-color: #3b5998; color: #fff;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a v-if="siteSetting.twitter_url" :href="siteSetting.twitter_url" target="_blank"
                        class="btn d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                        style="width: 40px; height: 40px; background-color: #1da1f2; color: #fff;">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a v-if="siteSetting.instagram_url" :href="siteSetting.instagram_url" target="_blank"
                        class="btn d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                        style="width: 40px; height: 40px; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); color: #fff;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a v-if="siteSetting.whatsapp_url" :href="`https://wa.me/${siteSetting.whatsapp_url}`"
                        target="_blank"
                        class="btn d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                        style="width: 40px; height: 40px; background-color: #25d366; color: #fff;">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <div v-if="!hasSocialMedia" class="text-muted">Belum ada media sosial yang tersedia.</div>
                </div>
            </div>
        </div>

        <!-- Locations -->
        <div v-if="locationLinks.length" class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Lokasi Kami</h4>
                <div class="row g-4">
                    <div v-for="(map, index) in locationLinks" :key="index" class="col-md-12">
                        <div class="rounded overflow-hidden h-100 map-container">
                            <iframe :src="map" width="100%" height="400" style="border:0;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import MainLayout from '../../../Layouts/MainLayout.vue';
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        MainLayout,
        Head
    },

    props: {
        siteSetting: { type: Object, required: true }
    },

    setup(props) {
        const hasSocialMedia = computed(() =>
            props.siteSetting.facebook_url ||
            props.siteSetting.twitter_url ||
            props.siteSetting.instagram_url ||
            props.siteSetting.whatsapp_url
        );

        const locationLinks = computed(() => {
            const links = props.siteSetting.location_embed_links;

            if (!links) return [];
            if (Array.isArray(links)) return links;

            try {
                return JSON.parse(links);
            } catch (error) {
                return links.includes('http') ? [links] : [];
            }
        });

        return { locationLinks, hasSocialMedia };
    }
};
</script>
