<template>

    <Head>
        <title>Reviews - Rental Mobil</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>List of Reviews</h5>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th>No</th>
                                        <th>Rental</th>
                                        <th>User</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(review, index) in reviews.data" :key="review.id">
                                        <td class="fw-bold text-center">{{ index + 1 + (reviews.current_page - 1) *
                                            reviews.per_page }}</td>
                                        <td>{{ review.rental?.car?.brand || 'N/A' }}</td>
                                        <td>{{ review.user.name }}</td>
                                        <td>{{ review.rating }}</td>
                                        <td>{{ review.comment || 'No comment' }}</td>
                                    </tr>
                                    <tr v-if="reviews.data.length === 0">
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <Pagination :links="reviews.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from "../../../Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/vue3";

export default {
    layout: LayoutAdmin,

    components: {
        Pagination,
        Link,
    },
    props: {
        reviews: Object,
    },
};
</script>
