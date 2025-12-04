document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Inisialisasi Flowbite
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }

    // 2. Logika Timer Checkout (Floating Alert) - AKTIF
    if (typeof CI_TIME_LEFT !== 'undefined' && CI_TIME_LEFT > 0) {
        startFloatingTimer(CI_TIME_LEFT);
    }

    // 3. Inisialisasi TinyMCE (Admin)
    if (document.getElementById('description') && typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: 'textarea#description',
            promotion: false,
            branding: false,
            plugins: 'lists link code table autoresize',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist | link | table | code',
            menubar: false,
            content_style: 'body { font-family:Poppins,sans-serif; font-size:14px }'
        });
    }

    // 4. Logika Hitung Total Harga (Pilih Tiket)
    const ticketInputs = document.querySelectorAll('.ticket-input');
    if (ticketInputs.length > 0) {
        ticketInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
            input.addEventListener('change', calculateTotal);
        });
    }

    // 5. Datepicker (Tanggal Lahir)
    const birthDateInput = document.getElementById('birth_date');
    if (birthDateInput && typeof Datepicker !== 'undefined') {
        const dateLimit = new Date();
        dateLimit.setFullYear(dateLimit.getFullYear() - 17);
        new Datepicker(birthDateInput, {
            autohide: true,
            format: 'dd/mm/yyyy',
            orientation: 'top',
            todayBtn: true,
            clearBtn: true,
            maxDate: dateLimit, 
            title: 'Tanggal Lahir'
        });
    }

    // 6. Tombol Salin Nomor VA
    const btnCopyVa = document.getElementById('btn-copy-va');
    if (btnCopyVa) {
        btnCopyVa.addEventListener('click', function() {
            const vaTextElement = document.getElementById('va-number');
            if(vaTextElement) {
                const vaText = vaTextElement.innerText.replace(/\s/g, '');
                copyToClipboard(vaText);
            }
        });
    }

    // 7. Modal Active Session
    if (typeof HAS_ACTIVE_SESSION !== 'undefined' && HAS_ACTIVE_SESSION === true) {
        const currentPath = window.location.pathname;
        if (!currentPath.includes('/checkout')) {
            const sessionModal = document.getElementById('active-session-modal');
            if (sessionModal) {
                sessionModal.classList.remove('hidden');
                sessionModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }
    }

    // 8. Flash Messages (Toast)
    if (typeof CI_FLASH_MESSAGES !== 'undefined') {
        if (CI_FLASH_MESSAGES.success) showToast('success', CI_FLASH_MESSAGES.success);
        if (CI_FLASH_MESSAGES.error) showToast('error', CI_FLASH_MESSAGES.error);
        if (CI_FLASH_MESSAGES.warning) showToast('warning', CI_FLASH_MESSAGES.warning);
        if (CI_FLASH_MESSAGES.errors) {
            Object.values(CI_FLASH_MESSAGES.errors).forEach(msg => showToast('error', msg));
        }
    }

    // 9. Password Toggle (Login & Register)
    // Fungsi ini sekarang sudah ada definisinya di bawah, jadi tidak akan error lagi
    setupPasswordToggle('toggle-password-btn', 'password');             
    setupPasswordToggle('toggle-password-confirm-btn', 'password_confirm');

    // 10. Logika OTP 6 Digit
    const otpContainer = document.getElementById('otp-container');
    if (otpContainer) {
        const inputs = otpContainer.querySelectorAll('.otp-box');
        const hiddenToken = document.getElementById('real-token');

        const updateHiddenToken = () => {
            let tokenValue = '';
            inputs.forEach(input => { tokenValue += input.value; });
            if (hiddenToken) hiddenToken.value = tokenValue;
        };

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value;
                if (isNaN(val)) { e.target.value = ""; return; }
                if (val !== "") {
                    updateHiddenToken();
                    if (index < inputs.length - 1) inputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (input.value === '' && index > 0) inputs[index - 1].focus();
                    setTimeout(updateHiddenToken, 10);
                }
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const data = e.clipboardData.getData('text').slice(0, 6);
                if (/^\d+$/.test(data)) {
                    data.split('').forEach((char, i) => { if (inputs[i]) inputs[i].value = char; });
                    updateHiddenToken();
                    if (inputs[Math.min(data.length, inputs.length) - 1]) {
                        inputs[Math.min(data.length, inputs.length) - 1].focus();
                    }
                }
            });
            input.addEventListener('focus', () => input.select());
        });
        if (inputs[0]) inputs[0].focus();
    }

    // 11. Init Chart Admin (Safe Check)
    if (typeof initDashboardCharts === 'function') {
        initDashboardCharts();
    }

    // 12. Responsive Search Placeholder (Main Page)
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        const updatePlaceholder = () => {
            if (window.innerWidth < 768) { // Breakpoint Mobile (md di Tailwind)
                searchInput.placeholder = "Cari Event...";
            } else {
                searchInput.placeholder = "Cari berdasarkan artis, lokasi, atau event...";
            }
        };

        // Jalankan saat halaman dimuat
        updatePlaceholder();

        // Jalankan saat layar di-resize (misal rotasi HP)
        window.addEventListener('resize', updatePlaceholder);
    }
});


/* =========================================================
   ZONA FUNGSI GLOBAL (DEFINISI FUNGSI)
   ========================================================= */

// 1. Fungsi Password Toggle (INI YANG SEBELUMNYA HILANG)
function setupPasswordToggle(buttonId, inputId) {
    const btn = document.getElementById(buttonId);
    const input = document.getElementById(inputId);

    if (btn && input) {
        btn.addEventListener('click', () => {
            // Cek icon pakai ID atau Class
            const eyeOpen = btn.querySelector('#eye-open') || btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('#eye-closed') || btn.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text'; // Show
                if(eyeOpen) eyeOpen.classList.remove('hidden');
                if(eyeClosed) eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password'; // Hide
                if(eyeOpen) eyeOpen.classList.add('hidden');
                if(eyeClosed) eyeClosed.classList.remove('hidden');
            }
        });
    }
}

// 2. Fungsi Modal Batal Pesan (Checkout)
window.showCancelModal = function() {
    const modal = document.getElementById('cancel-modal');
    const timerAlert = document.getElementById('checkout-timer-alert');

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Sembunyikan timer sementara biar gak numpuk
        if (timerAlert) timerAlert.classList.add('hidden');
    }
}

window.closeCancelModal = function() {
    const modal = document.getElementById('cancel-modal');
    const timerAlert = document.getElementById('checkout-timer-alert');

    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        
        // Munculkan timer lagi
        if (timerAlert) timerAlert.classList.remove('hidden');
    }
}

// 3. Fungsi Timer (Floating)
function startFloatingTimer(duration) {
    let timer = duration;
    const timerAlert = document.getElementById('checkout-timer-alert');
    const headerText = document.getElementById('timer-countdown');
    const bodyText = document.getElementById('body-timer-text');
    const timeoutModal = document.getElementById('timeout-modal');
 
    if (timerAlert) timerAlert.classList.remove('hidden');

    const interval = setInterval(function () {
        const minutes = parseInt(timer / 60, 10);
        const seconds = parseInt(timer % 60, 10);
        const displayMin = minutes < 10 ? "0" + minutes : minutes;
        const displaySec = seconds < 10 ? "0" + seconds : seconds;
        const formattedTime = displayMin + ":" + displaySec;
        
        if (headerText) headerText.textContent = formattedTime;
        if (bodyText) bodyText.textContent = formattedTime;

        if (timer < 120) {
            if (timerAlert) {
                const alertBox = timerAlert.querySelector('div');
                if(alertBox) {
                    alertBox.classList.remove('text-blue-800', 'bg-blue-50', 'border-blue-200');
                    alertBox.classList.add('text-red-800', 'bg-red-50', 'border-red-200');
                }
            }
            if (bodyText) bodyText.classList.add('text-red-600', 'animate-pulse');
        }

        if (--timer < 0) {
            clearInterval(interval);
            if (timerAlert) timerAlert.classList.add('hidden');
            if (timeoutModal) {
                timeoutModal.classList.remove('hidden');
                timeoutModal.classList.add('flex');
            }
            setTimeout(() => { window.location.href = "/checkout/cancel"; }, 3000);
        }
    }, 1000);
}

// 4. Fungsi Copy Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const popup = document.getElementById('custom-copy-popup');
        if (popup) {
            popup.classList.remove('hidden'); popup.classList.add('flex');
            setTimeout(() => { popup.classList.add('hidden'); popup.classList.remove('flex'); }, 1500);
        } else { alert('Disalin: ' + text); }
    });
}

// 5. Fungsi Hitung Total
function calculateTotal() {
    const inputs = document.querySelectorAll('.ticket-input');
    const cartContainer = document.getElementById('cartItems');
    const totalLabel = document.getElementById('totalPrice');
    const btnCheckout = document.getElementById('btnCheckout');
    
    let grandTotal = 0;
    let totalQty = 0;
    let cartHtml = '';

    inputs.forEach(input => {
        const qty = parseInt(input.value) || 0;
        const price = parseInt(input.dataset.price);
        const name = input.dataset.name;

        if (qty > 0) {
            const subtotal = qty * price;
            grandTotal += subtotal;
            totalQty += qty;
            cartHtml += `<div class="flex justify-between items-center text-sm mb-2 animate-fade-in"><span class="text-gray-700">${qty}x ${name}</span><span class="font-bold text-gray-900">Rp ${subtotal.toLocaleString('id-ID')}</span></div>`;
        }
    });

    if (totalQty === 0) cartContainer.innerHTML = '<div class="flex flex-col items-center justify-center h-full py-4 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200"><p class="text-xs">Belum ada tiket dipilih</p></div>';
    else cartContainer.innerHTML = cartHtml;

    if (totalLabel) totalLabel.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

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

// 6. Fungsi Toast
function showToast(type, message) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    let iconSvg = '';
    let colorClass = '';

    if (type === 'success') {
        colorClass = 'text-green-500 bg-green-100';
        iconSvg = '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/>';
    } else if (type === 'error') {
        colorClass = 'text-red-500 bg-red-100';
        iconSvg = '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>';
    } else {
        colorClass = 'text-orange-500 bg-orange-100';
        iconSvg = '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
    }

    const toastHtml = `
        <div class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow border border-gray-200 animate-fade-in-down" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 ${colorClass} rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">${iconSvg}</svg>
            </div>
            <div class="ms-3 text-sm font-normal">${message}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>`;

    const toastElement = document.createElement('div');
    toastElement.innerHTML = toastHtml;
    container.appendChild(toastElement.firstElementChild);
    setTimeout(() => { if (container.firstElementChild) container.firstElementChild.remove(); }, 4000);
}

// 7. Fungsi Admin Modal
function toggleAdminModal(show, title = '', message = '', onConfirm = null) {
    const modal = document.getElementById('custom-confirm-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    const titleEl = document.getElementById('modal-title');
    const msgEl = document.getElementById('modal-message');
    const btnConfirm = document.getElementById('btn-confirm');
    const btnCancel = document.getElementById('btn-cancel');

    if (!modal) return;

    if (show) {
        if(titleEl) titleEl.textContent = title;
        if(msgEl) msgEl.textContent = message;

        const newBtn = btnConfirm.cloneNode(true);
        btnConfirm.parentNode.replaceChild(newBtn, btnConfirm);
        newBtn.addEventListener('click', () => {
            if (onConfirm) onConfirm();
            toggleAdminModal(false);
        });

        const newBtnCancel = btnCancel.cloneNode(true);
        btnCancel.parentNode.replaceChild(newBtnCancel, btnCancel);
        newBtnCancel.addEventListener('click', () => toggleAdminModal(false));

        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
            panel.classList.add('opacity-100', 'scale-100');
        }, 10);
    } else {
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'scale-100');
        panel.classList.add('opacity-0', 'scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
}

window.deleteEvent = function(id) {
    toggleAdminModal(true, 'Hapus Event Ini?', 'Data yang dihapus tidak bisa dikembalikan.', () => {
        fetch('/admin/events/' + id, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json()).then(d => {
            if (d.status === 'success') {
                const row = document.getElementById(`row-event-${id}`);
                if (row) row.remove();
                showToast('success', 'Event berhasil dihapus.');
            } else { showToast('error', d.message); }
        }).catch(e => showToast('error', 'Koneksi gagal.'));
    });
};

window.deleteTicket = function(eventId, ticketId) {
    toggleAdminModal(true, 'Hapus Tiket Ini?', 'Data penjualan terkait mungkin terpengaruh.', () => {
        fetch(`/admin/events/${eventId}/tickets/${ticketId}`, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json()).then(d => {
            if(d.status === 'success') {
                const row = document.getElementById(`row-ticket-${ticketId}`);
                if (row) row.remove();
                showToast('success', 'Tiket berhasil dihapus.');
            } else { showToast('error', d.message); }
        }).catch(e => showToast('error', 'Koneksi gagal.'));
    });
};

// 8. Init Dashboard Chart
function initDashboardCharts() {
    const ctxRev = document.getElementById('revenueChart');
    const ctxCat = document.getElementById('categoryChart');

    if (ctxRev && ctxCat && typeof window.dashboardData !== 'undefined' && typeof Chart !== 'undefined') {
        const { revenue, categories } = window.dashboardData;
        
        const revLabels = revenue.map(item => {
            const d = new Date(item.date);
            return d.toLocaleDateString('id-ID', {day: 'numeric', month: 'short'});
        });
        const revTotals = revenue.map(item => item.total);

        new Chart(ctxRev.getContext('2d'), {
            type: 'line',
            data: {
                labels: revLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revTotals,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } }
            }
        });

        const catLabels = categories.map(item => item.category.charAt(0).toUpperCase() + item.category.slice(1));
        const catTotals = categories.map(item => item.total_sold);

        new Chart(ctxCat.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: catLabels.length ? catLabels : ['Belum ada data'],
                datasets: [{
                    data: catTotals.length ? catTotals : [1],
                    backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#10b981', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } },
                cutout: '70%'
            }
        });
    }
}

// 1. Buka Modal Konfirmasi
window.showPaymentConfirmModal = function() {
    const modal = document.getElementById('payment-confirm-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

// 2. Tutup Semua Modal Pembayaran
window.closePaymentModals = function() {
    ['payment-confirm-modal', 'payment-success-modal', 'payment-error-modal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
            el.classList.remove('flex');
        }
    });
    document.body.style.overflow = 'auto';
}

// 3. Proses AJAX ke Server
window.processPaymentAjax = function(orderId) {
    // 1. Ambil Elemen
    const btn = document.getElementById('btn-process-ajax'); // ID tombol di dalam modal (bukan trigger)
    const btnText = document.getElementById('btn-ajax-text');
    const btnSpinner = document.getElementById('btn-ajax-spinner');
    
    // 2. Ambil Token dari Input Hidden
    const csrfInput = document.getElementById('csrf_security');
    
    if (!csrfInput) {
        alert("Error: Token keamanan tidak ditemukan. Coba refresh halaman.");
        return;
    }

    const csrfName = csrfInput.name; // biasanya 'csrf_test_name'
    const csrfHash = csrfInput.value;

    // 3. Ubah Tampilan Tombol (Loading)
    if(btn) {
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    }
    if(btnText) btnText.textContent = 'Memproses...';
    if(btnSpinner) btnSpinner.classList.remove('hidden');

    // 4. Susun Data Body (Termasuk Token CSRF sebagai data POST biasa)
    // Kita kirim sebagai FormData atau JSON. Di sini kita pakai JSON.
    // PENTING: Masukkan token CSRF ke dalam body JSON juga, kadang CI4 membacanya dari sana.
    const postData = {
        [csrfName]: csrfHash 
    };

    // 5. Kirim Request Fetch
    fetch('/checkout/confirm/' + orderId, {
        method: 'POST',
        
        // [WAJIB] Agar cookie session & csrf ikut terkirim
        credentials: 'include', 
        
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            // Kirim token di header juga (Double protection)
            'X-CSRF-TOKEN': csrfHash 
        },
        body: JSON.stringify(postData)
    })
    .then(response => {
        if (!response.ok) {
            // Jika masih 403 Forbidden, berarti CSRF masih gagal
            throw new Error('Server menolak request (Status: ' + response.status + ')');
        }
        return response.json();
    })
    .then(data => {
        // Tutup modal konfirmasi
        closePaymentModals(); 

        if (data.status === 'success') {
            // SUKSES
            if(document.getElementById('success-email')) 
                document.getElementById('success-email').textContent = data.email;
            
            if(document.getElementById('success-trx'))
                document.getElementById('success-trx').textContent = data.trx_id;
            
            const successModal = document.getElementById('payment-success-modal');
            if(successModal) {
                successModal.classList.remove('hidden');
                successModal.classList.add('flex');
            }
            
            // Hapus timer
            const timerEl = document.getElementById('checkout-timer-alert');
            if(timerEl) timerEl.remove();

        } else {
            // GAGAL (Logic Error dari Controller)
            if(document.getElementById('error-message'))
                document.getElementById('error-message').textContent = data.message;
            
            const errorModal = document.getElementById('payment-error-modal');
            if(errorModal) {
                errorModal.classList.remove('hidden');
                errorModal.classList.add('flex');
            }
            
            // Reset Tombol
            resetBtnState();
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        closePaymentModals();
        alert('Gagal memproses: ' + error.message);
        resetBtnState();
    });

    // Helper reset tombol
    function resetBtnState() {
        if(btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
        if(btnText) btnText.textContent = 'Ya, Sudah Bayar';
        if(btnSpinner) btnSpinner.classList.add('hidden');
    }
}