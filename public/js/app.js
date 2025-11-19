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

    const ticketInputs = document.querySelectorAll('.ticket-input');
    
    if (ticketInputs.length > 0) {
        const cartContainer = document.getElementById('cartItems');
        const totalLabel = document.getElementById('totalPrice');
        const btnCheckout = document.getElementById('btnCheckout');

        // Fungsi Hitung Total
        function calculateTotal() {
            let grandTotal = 0;
            let totalQty = 0;
            let cartHtml = '';

            ticketInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseInt(input.dataset.price);
                const name = input.dataset.name;

                if (qty > 0) {
                    const subtotal = qty * price;
                    grandTotal += subtotal;
                    totalQty += qty;

                    // Buat HTML ringkas untuk sidebar
                    cartHtml += `
                        <div class="flex justify-between items-center animate-fade-in">
                            <span class="text-gray-800 font-medium">${qty}x <span class="text-gray-600 font-normal">${name}</span></span>
                            <span class="font-bold text-gray-900">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        </div>
                    `;
                }
            });

            // Update Tampilan Sidebar
            if (totalQty === 0) {
                cartContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full py-4 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                        <svg class="w-6 h-6 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        <p class="text-xs">Belum ada tiket dipilih</p>
                    </div>
                `;
            } else {
                cartContainer.innerHTML = cartHtml;
            }

            // Update Label Total
            if (totalLabel) {
                totalLabel.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
            }

            // Update Tombol Checkout
            if (btnCheckout) {
                if (totalQty > 0) {
                    btnCheckout.disabled = false;
                    btnCheckout.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                    btnCheckout.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-lg');
                } else {
                    btnCheckout.disabled = true;
                    btnCheckout.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                    btnCheckout.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-lg');
                }
            }
        }

        // Pasang Event Listener ke semua input
        ticketInputs.forEach(input => {
            // Saat angka diketik/diubah
            input.addEventListener('input', calculateTotal);
            // Saat panah up/down diklik (browser support)
            input.addEventListener('change', calculateTotal);
        });
    }
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