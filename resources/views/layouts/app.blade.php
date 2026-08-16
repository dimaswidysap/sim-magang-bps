<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIM MAGANG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/sidebar-mobile.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-montserrat bg-background">
    @yield('content')
    @include('components.modal-alert')
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('custom-confirm-modal');
        const modalContent = document.getElementById('modal-content');
        const messageEl = document.getElementById('confirm-modal-message');
        const btnCancel = document.getElementById('btn-cancel-confirm');
        const btnContinue = document.getElementById('btn-continue-confirm');

        let currentForm = null;

        // Fungsi untuk membuka modal
        function openModal(message, form) {
            currentForm = form;
            messageEl.textContent = message;

            modal.classList.remove('hidden', 'pointer-events-none');
            // Sedikit delay agar animasi transisi Tailwind berjalan mulus
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden', 'pointer-events-none');
                currentForm = null;
            }, 300); // Sesuaikan dengan durasi transisi (300ms)
        }

        // 1. Tangkap semua form yang memiliki atribut 'data-confirm'
        const confirmForms = document.querySelectorAll('form[data-confirm]');

        confirmForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Hentikan form agar tidak langsung submit
                const message = this.getAttribute('data-confirm');
                openModal(message, this);
            });
        });

        // 2. Aksi jika tombol Batal diklik
        btnCancel.addEventListener('click', closeModal);

        // 3. Aksi jika tombol Lanjutkan diklik
        btnContinue.addEventListener('click', function() {
            if (currentForm) {
                currentForm.submit(); // Lanjutkan submit form via JS
            }
        });

        // 4. (Opsional) Tutup modal jika area luar (backdrop) diklik
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
</script>

</html>
