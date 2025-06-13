<template>

    <Head>
        <title>Riwayat Rental - Rental Mobil</title>
    </Head>
    <MainLayout :siteSetting="siteSetting">
        <div v-if="rentals && rentals.data.length > 0">
            <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Mobil</th>
                            <th scope="col">Tanggal Mulai</th>
                            <th scope="col">Tanggal Selesai</th>
                            <th scope="col">Jumlah Pembayaran</th>
                            <th scope="col">Status</th>
                            <th scope="col">Detail</th>
                            <th scope="col">Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rental, index) in rentals.data" :key="rental.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ rental.car.brand }} {{ rental.car.model }}</td>
                            <td>{{ rental.rental_start_date }}</td>
                            <td>{{ rental.rental_end_date }}</td>
                            <td>{{ formatCurrency(rental.total_fee) }}</td>
                            <td
                                :class="{ 'text-success': rental.status === 'approved', 'text-warning': rental.status !== 'approved' }">
                                {{ rental.status }}
                            </td>
                            <td>
                                <Link :href="`/rentals/${rental.id}`" class="btn btn-info">Lihat Detail</Link>
                            </td>
                            <td>
                                <button v-if="rental.status === 'approved'" type="button" class="btn btn-primary"
                                    data-bs-toggle="modal" :data-bs-target="'#reviewModal' + rental.id">
                                    {{ rental.user_review ? 'Edit Review' : 'Tulis Review' }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="rentals.total > rentals.per_page">
                <pagination :links="rentals.links" />
            </div>
        </div>

        <!-- If no rentals are available -->
        <div v-else>
            <p>Belum ada rental yang tersedia.</p>
        </div>

        <!-- Review Modal (for each rental) -->
        <div v-for="rental in rentals.data" :key="'modal' + rental.id" class="modal fade"
            :id="'reviewModal' + rental.id" tabindex="-1" aria-labelledby="'reviewModalLabel' + rental.id"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" :id="'reviewModalLabel' + rental.id">
                            {{ rental.user_review ? 'Edit Review untuk' : 'Tulis Review untuk' }} {{ rental.car.brand }}
                            {{ rental.car.model }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitReview(rental)">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <div class="stars">
                                    <span v-for="star in 5" :key="star"
                                        :class="{ 'star-filled': star <= (rentalRatings[rental.id] || 0) }"
                                        @click="setRating(rental.id, star)" class="star">&#9733;</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Komentar</label>
                                <textarea v-model="rentalComments[rental.id]" id="comment" class="form-control" rows="4"
                                    placeholder="Tulis komentar Anda"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </MainLayout>
</template>

<script>
import { onMounted, reactive } from 'vue';
import { Link, router, Head } from '@inertiajs/vue3';
import MainLayout from '../../../Layouts/MainLayout.vue';
import Swal from "sweetalert2";
import formatCurrency from '../../../utils/formatCurrency';
import Pagination from "@/Components/Pagination.vue";

export default {
    components: {
        Head,
        Link,
        MainLayout,
        Pagination
    },

    props: {
        rentals: Object,
        isAdmin: Boolean,
        siteSetting: {
            type: Object,
            required: true
        },
    },

    setup(props) {
        const rentalRatings = reactive({});
        const rentalComments = reactive({});

        const setRating = (rentalId, star) => {
            rentalRatings[rentalId] = star;
        };

        const submitReview = (rental) => {
            const reviewData = {
                rental_id: rental.id,
                rating: rentalRatings[rental.id],
                comment: rentalComments[rental.id],
                review_id: rental.user_review ? rental.user_review.id : null
            };

            const modalId = `reviewModal${rental.id}`;

            router.post(`/rentals/${rental.id}/review`, reviewData, {
                onSuccess: () => {
                    Swal.fire("Berhasil!", "Review berhasil disimpan.", "success").then(() => {
                        const modalElement = document.getElementById(modalId);
                        if (modalElement) {
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        }
                    });
                },
            });
        };

        onMounted(() => {
            if (props.rentals && props.rentals.data) {
                props.rentals.data.forEach(rental => {
                    if (rental.user_review) {
                        rentalRatings[rental.id] = rental.user_review.rating;
                        rentalComments[rental.id] = rental.user_review.comment;
                    } else {
                        rentalRatings[rental.id] = 0;
                        rentalComments[rental.id] = '';
                    }
                });
            }
        });

        return {
            formatCurrency,
            setRating,
            submitReview,
            rentalRatings,
            rentalComments
        };
    }
};
</script>
