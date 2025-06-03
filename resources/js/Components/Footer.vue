<template>
    <footer class="mt-5 pt-3 border-top">
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted small">© {{ currentYear }} {{ siteSetting.site_name || 'Rental Mobil' }}. Semua hak
                    dilindungi.</p>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'FooterVue',
    props: {
        siteSetting: {
            type: Object,
            default: () => ({}) // Mengatur default ke objek kosong jika tidak ada data yang diteruskan
        }
    },
    setup() {
        // Mengambil tahun saat ini
        const currentYear = new Date().getFullYear();

        return {
            currentYear
        };
    }
}
</script>
