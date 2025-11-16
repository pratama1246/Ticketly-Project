document.addEventListener('DOMContentLoaded', () => {
    // ----------------------------------------------------
    // LOGIKA 1: TIMER CHECKOUT
    // (Menggunakan variabel global CI_TIME_LEFT yang disuntikkan dari PHP)
    // ----------------------------------------------------
    if (typeof CI_TIME_LEFT !== 'undefined' && CI_TIME_LEFT > 0) {
        const timerElement = document.getElementById('checkout-timer');
        const popupTimerDisplay = document.getElementById('popup-timer');
        let totalSeconds = CI_TIME_LEFT;

        const timerInterval = setInterval(() => {
            totalSeconds--;

            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                window.location.reload(); // Filter akan menangani redirect ke timeout
                return;
            }

            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            const timeString = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            // Update timer di view utama (hanya jika elemennya ada)
            if (timerElement) {
                timerElement.textContent = timeString;
            }
            // Update timer di pop-up modal
            if (popupTimerDisplay) {
                popupTimerDisplay.textContent = timeString;
            }

            // Opsional: Ganti warna timer jika waktu hampir habis (UX)
            if (totalSeconds < 120 && timerElement) { // 2 menit terakhir
                timerElement.classList.add('font-extrabold', 'text-red-900', 'animate-pulse');
            }
        }, 1000);
    }
    
    // ----------------------------------------------------
    // LOGIKA 2: POP-UP MODAL CHECKOUT
    // (Hanya muncul jika session aktif dan TIDAK sedang berada di alur checkout)
    // ----------------------------------------------------
    if (typeof CI_SHOW_MODAL !== 'undefined' && CI_SHOW_MODAL) {
        const checkoutModalEl = document.getElementById('checkout-modal');
        if (checkoutModalEl) {
            // Asumsi library Flowbite sudah dimuat dan class Modal sudah tersedia
            const checkoutModal = new Modal(checkoutModalEl, {
                backdrop: 'static', 
                closable: false 
            });
            checkoutModal.show();
        }
    }

    // ----------------------------------------------------
    // LOGIKA 3: INISIALISASI FLOWBITE (Wajib)
    // ----------------------------------------------------
    // Flowbite perlu diinisialisasi untuk Dropdown, Carousel, dll.
    // Jika kamu menggunakan Vite, ini mungkin sudah dihandle, tapi ini cara manualnya.
    initFlowbite();
});