<template>

    <Head>
        <title>Profile - {{ siteSetting.site_name }}</title>
    </Head>

    <MainLayout :siteSetting="siteSetting">
        <div class="row">
            <h3 class="text-center mb-4">Profile</h3>

            <div class="profile-detail">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>
                                    {{ user.name }}
                                    <button class="btn btn-sm btn-link" @click="openEditModal('name')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ user.email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin:</strong></td>
                                <td>{{ user.gender || 'Tidak Ditentukan' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>
                                    {{ user.address || 'Tidak Ada Alamat' }}
                                    <button class="btn btn-sm btn-link" @click="openEditModal('address')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Nomor WhatsApp:</strong></td>
                                <td>
                                    {{ user.whatsapp_number || 'Tidak Ada Nomor' }}
                                    <button class="btn btn-sm btn-link"
                                        @click="openEditModal('whatsapp_number')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>ID CARD:</strong></td>
                                <td>
                                    <img v-if="user.image" :src="`${user.image}`" alt="Profile Image" class="img-fluid"
                                        style="max-width: 150px; max-height: 150px; object-fit: cover;" />
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Upload ID card:</strong></td>
                                <td>
                                    <input type="file" @change="handleFileChange" class="form-control"
                                        accept="image/*" />
                                    <button class="btn btn-primary mt-3" @click="uploadImage" :disabled="!selectedFile">
                                        Upload
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <Link :href="backToDashboardUrl" class="btn btn-outline-secondary">
                Kembali ke Dashboard
                </Link>
                <button class="btn btn-outline-danger" @click="logout">Logout</button>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit {{ editField }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="editField === 'name'">
                            <label for="name">Nama:</label>
                            <input v-model="editedData.name" type="text" id="name" class="form-control" />
                        </div>
                        <div v-if="editField === 'address'">
                            <label for="address">Alamat:</label>
                            <input v-model="editedData.address" type="text" id="address" class="form-control" />
                        </div>
                        <div v-if="editField === 'whatsapp_number'">
                            <label for="whatsapp_number">Nomor WhatsApp:</label>
                            <input v-model="editedData.whatsapp_number" type="text" id="whatsapp_number"
                                class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updateProfile">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import { ref, computed } from 'vue';
import { Link, router, Head } from '@inertiajs/vue3';
import MainLayout from '../../../Layouts/MainLayout.vue';
import Swal from "sweetalert2";

export default {
    components: {
        Head,
        Link,
        MainLayout
    },
    props: {
        user: Object,
        isAdmin: Boolean,
        siteSetting: {
            type: Object,
            required: true
        },
    },
    setup(props) {
        const selectedFile = ref(null);
        const editField = ref('');
        const editedData = ref({
            name: '',
            address: '',
            whatsapp_number: '',
        });

        const backToDashboardUrl = computed(() => {
            return props.isAdmin ? '/admin/dashboard' : '/';
        });

        const openEditModal = (field) => {
            editField.value = field;
            editedData.value[field] = props.user[field];
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        };

        const updateProfile = async () => {
            const dataToUpdate = { [editField.value]: editedData.value[editField.value] };

            try {
                await router.post('/profile/update', dataToUpdate, {
                    onSuccess: () => {
                        const modalElement = document.getElementById('editModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) {
                            modalInstance.hide();
                        }

                        Swal.fire({
                            title: 'Success!',
                            text: 'Profile has been updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                        });
                    },
                    onFinish: () => { },
                    preserveState: true,
                    replace: true,
                });
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update profile. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }
        };

        const handleFileChange = (event) => {
            selectedFile.value = event.target.files[0];
        };

        const uploadImage = async () => {
            const formData = new FormData();
            formData.append('image', selectedFile.value);

            try {
                await router.post('/profile/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onSuccess: () => {
                        selectedFile.value = null;
                        Swal.fire({
                            title: 'Success!',
                            text: 'Profile image has been updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                        });
                    },
                    onFinish: () => {
                        selectedFile.value = null;
                    },
                    preserveState: true,
                    replace: true,
                });
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to upload image. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }
        };

        const logout = async () => {
            try {
                await router.post('/logout');
                window.location.href = '/';
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to log out. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }
        };

        // Return the variables and methods
        return {
            selectedFile,
            editField,
            editedData,
            backToDashboardUrl,
            openEditModal,
            updateProfile,
            handleFileChange,
            uploadImage,
            logout,
        };
    },
};
</script>
