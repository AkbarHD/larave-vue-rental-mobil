<template>

    <Head>
        <title>Messages - Rental Mobil</title>
    </Head>

    <div class="container-fluid mb-5 mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5 class="mb-3">Template Pesan</h5>

                        <div class="row">
                            <div class="col-md-4 mb-4" v-for="templateType in templateTypes" :key="templateType.value">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">{{ templateType.label }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="updateTemplate(templateType.value)">
                                            <div class="mb-3">
                                                <label :for="'content-' + templateType.value">Isi Template</label>
                                                <textarea :id="'content-' + templateType.value" class="form-control"
                                                    placeholder="Tulis template pesan Anda"
                                                    v-model="templateForms[templateType.value].content" minlength="10"
                                                    rows="5"></textarea>
                                                <div v-if="errors[templateType.value]?.content"
                                                    class="text-danger mt-2">
                                                    {{ errors[templateType.value]?.content }}
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-save"></i> Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <small>
                                            <strong>Kode Personalisasi:</strong>
                                            <br><code>{car_name}</code> - Nama mobil
                                            <br><code>{user_name}</code> - Nama pengguna
                                            <br><code>{start_date}</code> - Tanggal mulai
                                            <br><code>{end_date}</code> - Tanggal selesai
                                            <br><code>{total_fee}</code> - Total biaya
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from "../../../Layouts/Admin.vue";
import { reactive, onMounted } from "vue";
import Swal from "sweetalert2";
import { router } from "@inertiajs/vue3";

export default {
    layout: LayoutAdmin,
    props: {
        templates: Object,
        errors: Object,
    },
    setup(props) {
        const templateTypes = [
            { value: 'sewa', label: 'Template Pesan Sewa' },
            { value: 'approved', label: 'Template Pesan Disetujui' },
            { value: 'rejected', label: 'Template Pesan Ditolak' }
        ];

        const templateForms = reactive({
            sewa: { content: '' },
            approved: { content: '' },
            rejected: { content: '' }
        });

        const errors = reactive({
            sewa: {},
            approved: {},
            rejected: {}
        });

        onMounted(() => {
            if (props.templates?.data) {
                props.templates.data.forEach(template => {
                    if (templateForms[template.type]) {
                        templateForms[template.type].content = template.content;
                    }
                });
            }
        });

        const updateTemplate = (type) => {
            const existingTemplate = props.templates?.data?.find(t => t.type === type);
            const url = existingTemplate ? `/admin/messages/${existingTemplate.id}` : '/admin/messages';
            const method = existingTemplate ? 'put' : 'post';

            const formData = new FormData();
            formData.append('type', type);
            formData.append('content', templateForms[type].content);

            if (method === 'put') {
                formData.append('_method', 'PUT');
            }

            router.post(url, formData, {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Template pesan berhasil disimpan!",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                },
                onError: (formErrors) => {
                    errors[type] = formErrors;
                }
            });
        };

        return {
            templateTypes,
            templateForms,
            updateTemplate,
            errors
        };
    },
};
</script>
