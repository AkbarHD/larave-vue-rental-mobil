<template>

    <Head>
        <title>Pengaturan Situs - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Pengaturan Situs</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submitForm">
                            <div class="row">
                                <!-- Kolom Kiri - Informasi Utama -->
                                <div class="col-lg-6 mb-4 mb-lg-0">
                                    <div class="card h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Informasi Utama</h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Nama Situs -->
                                            <div class="form-group mb-3">
                                                <label for="site_name" class="form-label">Nama Situs</label>
                                                <input type="text" class="form-control" id="site_name"
                                                    v-model="form.site_name" placeholder="Masukkan nama situs" />
                                                <div v-if="errors.site_name" class="text-danger mt-1 small">
                                                    {{ errors.site_name }}
                                                </div>
                                            </div>

                                            <!-- Deskripsi -->
                                            <div class="form-group mb-3">
                                                <label for="description" class="form-label">Deskripsi Situs</label>
                                                <textarea class="form-control" id="description"
                                                    v-model="form.description" rows="3"
                                                    placeholder="Masukkan deskripsi situs"></textarea>
                                                <div v-if="errors.description" class="text-danger mt-1 small">
                                                    {{ errors.description }}
                                                </div>
                                            </div>

                                            <!-- Logo -->
                                            <div class="form-group mb-3">
                                                <label for="logo" class="form-label">Logo</label>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img v-if="previewLogo || logoUrl" :src="previewLogo || logoUrl"
                                                        alt="Logo Preview" class="me-3 border rounded"
                                                        style="height: 60px; width: auto; object-fit: contain;" />

                                                    <div v-else
                                                        class="bg-light border rounded d-flex align-items-center justify-content-center"
                                                        style="height: 60px; width: 120px;">
                                                        <span class="text-muted small">No Logo</span>
                                                    </div>
                                                </div>
                                                <input id="logo" type="file" ref="logoInput" @change="handleLogoChange"
                                                    class="form-control" accept="image/*" />
                                                <div v-if="errors.logo" class="text-danger mt-1 small">
                                                    {{ errors.logo }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kolom Kanan - Media Sosial -->
                                <div class="col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Media Sosial</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div v-for="(social, index) in socialLinks" :key="index"
                                                    class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label :for="social.id" class="form-label">
                                                            <i :class="social.icon"></i> {{ social.name }} URL
                                                        </label>
                                                        <input type="text" class="form-control" :id="social.id"
                                                            v-model="form[social.id]"
                                                            :placeholder="`https://${social.id}.com/...`" />
                                                        <div v-if="errors[social.id]" class="text-danger mt-1 small">
                                                            {{ errors[social.id] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi Embed Links - Row Baru -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Lokasi Embed Links</h6>
                                            <button type="button" @click="addLocationLink"
                                                class="btn btn-sm btn-success">
                                                <i class="fas fa-plus-circle me-1"></i> Tambah Lokasi
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div v-for="(link, index) in locationLinks" :key="index"
                                                    class="col-md-6">
                                                    <div class="input-group">
                                                        <input v-model="locationLinks[index]" type="text"
                                                            class="form-control"
                                                            placeholder="Masukkan URL embed lokasi" />
                                                        <button type="button" @click="removeLocationLink(index)"
                                                            class="btn btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" :disabled="processing">
                                    <i v-if="processing" class="fas fa-spinner fa-spin me-2"></i>
                                    {{ processing ? 'Menyimpan...' : 'Simpan Pengaturan' }}
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
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import LayoutAdmin from "../../../Layouts/Admin.vue";

export default {
    layout: LayoutAdmin,
    props: {
        siteSetting: Object,
        errors: Object,
    },
    setup(props) {
        const previewLogo = ref(null);
        const processing = ref(false);
        const locationLinks = ref([]);

        const socialLinks = [
            { id: 'facebook_url', name: 'Facebook', icon: 'fas fa-facebook' },
            { id: 'twitter_url', name: 'Twitter', icon: 'fas fa-twitter' },
            { id: 'instagram_url', name: 'Instagram', icon: 'fas fa-instagram' },
            { id: 'whatsapp_url', name: 'WhatsApp', icon: 'fas fa-whatsapp' }
        ];

        const form = ref({
            site_name: props.siteSetting.site_name || "",
            logo: null,
            description: props.siteSetting.description || "",
            facebook_url: props.siteSetting.facebook_url || "",
            twitter_url: props.siteSetting.twitter_url || "",
            instagram_url: props.siteSetting.instagram_url || "",
            whatsapp_url: props.siteSetting.whatsapp_url || "",
            location_embed_links: [],
        });

        const logoUrl = computed(() => props.siteSetting.logo);

        onMounted(() => {
            try {
                if (props.siteSetting.location_embed_links) {
                    locationLinks.value = Array.isArray(props.siteSetting.location_embed_links)
                        ? props.siteSetting.location_embed_links
                        : JSON.parse(props.siteSetting.location_embed_links);
                }
                if (locationLinks.value.length === 0) locationLinks.value.push("");
            } catch (e) {
                locationLinks.value = [""];
            }
        });

        const handleLogoChange = (e) => {
            const file = e.target.files[0];
            if (file) {
                form.value.logo = file;
                previewLogo.value = URL.createObjectURL(file);
            }
        };

        const addLocationLink = () => locationLinks.value.push("");
        const removeLocationLink = (index) => {
            locationLinks.value.splice(index, 1);
            if (locationLinks.value.length === 0) locationLinks.value.push("");
        };

        const submitForm = () => {
            processing.value = true;

            const formData = new FormData();
            formData.append('site_name', form.value.site_name);
            formData.append('description', form.value.description);

            if (form.value.logo) formData.append('logo', form.value.logo);

            socialLinks.forEach(social => {
                formData.append(social.id, form.value[social.id]);
            });

            locationLinks.value.forEach((link, index) => {
                if (link.trim()) formData.append(`location_embed_links[${index}]`, link);
            });

            router.post("/admin/site-settings", formData, {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Pengaturan situs berhasil diperbarui.",
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then(() => {
                        processing.value = false;
                    });
                },
                onError: (errors) => {
                    console.error("Validation errors:", errors);
                    Swal.fire({
                        title: "Error!",
                        text: "Terjadi kesalahan. Silakan periksa input Anda.",
                        icon: "error",
                        confirmButtonText: "OK",
                    }).then(() => {
                        processing.value = false;
                    });
                },
            });
        };

        return {
            form,
            previewLogo,
            logoUrl,
            processing,
            locationLinks,
            socialLinks,
            handleLogoChange,
            addLocationLink,
            removeLocationLink,
            submitForm,
        };
    },
};
</script>
