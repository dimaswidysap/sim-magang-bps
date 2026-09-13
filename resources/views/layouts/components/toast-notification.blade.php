<!-- Toast Container (Melayang di Atas Tengah dengan Glassmorphic & Progress Bar) -->
<div id="toast-notification"
    role="alert"
    aria-live="polite"
    class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999] flex flex-col w-[92%] sm:w-full max-w-md bg-surface/90 backdrop-blur-md text-text rounded-2xl shadow-2xl border border-accent-dark transition-all duration-300 ease-out transform -translate-y-28 opacity-0 pointer-events-none overflow-hidden font-montserrat">

    <div class="flex items-center p-4 gap-3.5">
        <!-- Icon Status -->
        <div id="toast-icon-bg" class="inline-flex items-center justify-center shrink-0 w-10 h-10 text-accent-dark bg-accent-dark/10 rounded-xl transition-colors">
            <!-- Icon Success -->
            <svg id="toast-icon-success" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <!-- Icon Error -->
            <svg id="toast-icon-error" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <!-- Icon Info -->
            <svg id="toast-icon-info" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Content (Label & Message) -->
        <div class="flex-1 min-w-0">
            <p id="toast-title" class="text-[10px] font-bold uppercase tracking-wider text-text-light mb-0.5">
                Notifikasi
            </p>
            <p id="toast-message" class="text-xs sm:text-sm font-semibold text-text leading-snug break-words">
                {{ session('success') ?? session('error') ?? session('info') }}
            </p>
        </div>

        <!-- Button Close -->
        <button type="button"
                onclick="dismissToast()"
                class="shrink-0 text-text-light hover:text-text hover:bg-border/40 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 transition-colors"
                aria-label="Tutup">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Progress Bar Timer -->
    <div class="w-full bg-border/20 h-1 overflow-hidden">
        <div id="toast-progress-bar" class="h-full bg-accent-dark w-full transition-all ease-linear"></div>
    </div>
</div>

<script>
    let toastTimeout = null;
    let progressInterval = null;

    /**
     * Tampilkan Toast Notification
     * @param {string} message - Pesan Notifikasi
     * @param {string} type - 'success' | 'error' | 'info'
     * @param {number} duration - Waktu tampil dalam milidetik (default: 4000ms)
     */
    function showToast(message, type = 'success', duration = 10000) {
        const toast = document.getElementById('toast-notification');
        const toastMsg = document.getElementById('toast-message');
        const toastTitle = document.getElementById('toast-title');
        const iconBg = document.getElementById('toast-icon-bg');
        const iconSuccess = document.getElementById('toast-icon-success');
        const iconError = document.getElementById('toast-icon-error');
        const iconInfo = document.getElementById('toast-icon-info');
        const progressBar = document.getElementById('toast-progress-bar');

        if (!toast || !toastMsg) return;

        // Reset timer dan animasi progress bar yang sedang berjalan
        clearTimeout(toastTimeout);
        if (progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = '100%';
        }

        // Set isi pesan
        toastMsg.textContent = message;

        // Reset visibilitas ikon
        iconSuccess.classList.add('hidden');
        iconError.classList.add('hidden');
        iconInfo.classList.add('hidden');

        // Penyesuaian Style berdasarkan tipe notifikasi
        if (type === 'error') {
            toastTitle.textContent = 'Gagal';
            iconBg.className = 'inline-flex items-center justify-center shrink-0 w-10 h-10 text-danger bg-danger/10 rounded-xl';
            if (progressBar) progressBar.className = 'h-full bg-danger w-full';
            iconError.classList.remove('hidden');
        } else if (type === 'info') {
            toastTitle.textContent = 'Informasi';
            iconBg.className = 'inline-flex items-center justify-center shrink-0 w-10 h-10 text-primary bg-primary/10 rounded-xl';
            if (progressBar) progressBar.className = 'h-full bg-primary w-full';
            iconInfo.classList.remove('hidden');
        } else {
            toastTitle.textContent = 'Berhasil';
            iconBg.className = 'inline-flex items-center justify-center shrink-0 w-10 h-10 text-accent-dark bg-accent-dark/10 rounded-xl';
            if (progressBar) progressBar.className = 'h-full bg-accent-dark w-full';
            iconSuccess.classList.remove('hidden');
        }

        // Tampilkan Toast dengan Animasi Meluncur
        toast.classList.remove('-translate-y-28', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

        // Jalankan Animasi Progress Bar secara mulus
        setTimeout(() => {
            if (progressBar) {
                progressBar.style.transition = `width ${duration}ms linear`;
                progressBar.style.width = '0%';
            }
        }, 10);

        // Otomatis tutup setelah durasi selesai
        toastTimeout = setTimeout(() => {
            dismissToast();
        }, duration);
    }

    /**
     * Sembunyikan Toast Notification
     */
    function dismissToast() {
        const toast = document.getElementById('toast-notification');
        if (!toast) return;

        toast.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
        toast.classList.add('-translate-y-28', 'opacity-0', 'pointer-events-none');

        clearTimeout(toastTimeout);
    }

    // Jalankan otomatis jika ada Session Flash dari Laravel
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            showToast(@json(session('success')), 'success');
        @elseif (session('error'))
            showToast(@json(session('error')), 'error');
        @elseif (session('info'))
            showToast(@json(session('info')), 'info');
        @endif
    });
</script>
