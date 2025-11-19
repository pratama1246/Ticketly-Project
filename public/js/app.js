initFlowbite();
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
    // LOGIKA 2: INISIALISASI CUSTOM MODAL (PENTING!)
    // Sekarang dijalankan SETELAH HTML siap
    // ----------------------------------------------------
    customModal = {
        modal: document.getElementById('custom-confirm-modal'),
        backdrop: document.getElementById('modal-backdrop'), // Baru
        panel: document.getElementById('modal-panel'),       // Baru
        titleEl: document.getElementById('modal-title'),
        messageEl: document.getElementById('modal-message'),
        confirmBtn: document.getElementById('btn-confirm'),
        cancelBtn: document.getElementById('btn-cancel'),
        
        show: function(title, message, confirmText, confirmColorClass, callback) {
            if (!this.modal) return;

            // 1. Set Konten
            this.titleEl.textContent = title;
            this.messageEl.textContent = message;
            this.confirmBtn.textContent = confirmText;
            this.confirmBtn.className = "inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto transition-all " + confirmColorClass;

            // 2. Hapus 'hidden' dulu biar dirender browser
            this.modal.classList.remove('hidden');

            // 3. Kasih delay dikit (10ms) biar browser sadar elemennya ada
            // Baru kita kasih class untuk memicu animasi CSS (Opacity 100, Scale 100)
            setTimeout(() => {
                this.backdrop.classList.remove('opacity-0');
                this.panel.classList.remove('opacity-0', 'scale-95');
                
                this.panel.classList.add('opacity-100', 'scale-100');
            }, 10);

            // --- Event Listeners ---
            let newConfirmBtn = this.confirmBtn.cloneNode(true);
            this.confirmBtn.parentNode.replaceChild(newConfirmBtn, this.confirmBtn);
            this.confirmBtn = newConfirmBtn;

            this.confirmBtn.addEventListener('click', () => {
                this.hide();
                if (callback) setTimeout(callback, 300); // Tunggu animasi tutup baru callback
            });

            this.cancelBtn.onclick = () => {
                this.hide();
            };
        },

        hide: function() {
            if (!this.modal) return;

            // 1. Memicu animasi keluar (Balikin ke Opacity 0, Scale 95)
            this.backdrop.classList.add('opacity-0');
            this.panel.classList.remove('opacity-100', 'scale-100');
            this.panel.classList.add('opacity-0', 'scale-95');

            // 2. Tunggu 300ms (sesuai duration-300 di CSS)
            // Baru sembunyikan total (hidden)
            setTimeout(() => {
                this.modal.classList.add('hidden');
            }, 300);
        }
    };

});

// ----------------------------------------------------
// FUNGSI GLOBAL (Bisa dipanggil dari onclick HTML)
// ----------------------------------------------------

function deleteEvent(id) {
    // Pastikan customModal sudah siap
    if (!customModal) {
        console.error("Modal belum siap!");
        return;
    }

    customModal.show(
        'Hapus Event Ini?',
        'Data akan hilang selamanya dari database.',
        'Ya, Hapus',
        'bg-red-600 hover:bg-red-500',
        () => {
            fetch('/admin/events/' + id, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const row = document.getElementById(`row-event-${id}`);
                    if (row) row.remove();
                    alert('Berhasil dihapus!'); 
                } else {
                    alert('Gagal menghapus: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan server.');
            });
        }
    );
}