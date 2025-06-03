<template>
    <nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
        <div class="sidebar-inner px-4 pt-3">
            <div
                class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
                <div class="collapse-close d-md-none">
                    <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                        <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <ul class="nav flex-column pt-3 pt-md-0">
                <!-- Sidebar Header -->
                <li class="nav-item">
                    <span class="mt-2 d-flex justify-content-between">
                        <span>
                            <span class="sidebar-text fw-bold">CepatCar</span>
                        </span>
                    </span>
                </li>

                <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

                <!-- Dashboard -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/dashboard') }">
                    <Link href="/admin/dashboard" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-tachometer-alt icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Dashboard</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-2 mb-2 border-gray-700"></li>

                <!-- Sliders -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/sliders') }">
                    <Link href="/admin/sliders" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-sliders-h icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Sliders</span>
                    </span>
                    </Link>
                </li>

                <!-- catgories -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/categories') }">
                    <Link href="/admin/categories" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-tags icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Category</span>
                    </span>
                    </Link>
                </li>

                <!-- Cars -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/cars') }">
                    <Link href="/admin/cars" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-car icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Cars</span>
                    </span>
                    </Link>
                </li>

                <!-- Rentals -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/rentals') }">
                    <Link href="/admin/rentals" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-file-alt icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Daftar Rentals</span>
                    </span>
                    </Link>
                </li>

                <li role="separator" class="dropdown-divider mt-2 mb-2 border-gray-700"></li>

                <!-- Addons -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/addons') }">
                    <Link href="/admin/addons" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-cogs icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Addons</span>
                    </span>
                    </Link>
                </li>

                <!-- Payments -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('/admin/payments') }">
                    <Link href="/admin/payments" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-dollar-sign icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Payments</span>
                    </span>
                    </Link>
                </li>

                <!-- Reviews -->
                <li class="nav-item" :class="{ 'active': $page.url.startsWith('') }">
                    <Link href="" class="nav-link d-flex justify-content-between align-items-center">
                    <span>
                        <span class="sidebar-icon">
                            <i class="fa fa-star icon icon-xs me-2"></i>
                        </span>
                        <span class="sidebar-text">Reviews</span>
                    </span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link
    }
}
</script>
