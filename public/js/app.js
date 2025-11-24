document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Inisialisasi Flowbite (Dropdown, Carousel bawaan, dll)
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }

    // 2. Inisialisasi TinyMCE (Editor Deskripsi di Admin)
    // Hanya jalan kalau ada elemen textarea dengan id="description"
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

    // 3. Logika Hitung Total Harga (Halaman Pilih Tiket)
    const ticketInputs = document.querySelectorAll('.ticket-input');
    if (ticketInputs.length > 0) {
        // Pasang "kuping" (event listener) ke setiap input jumlah
        ticketInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
            input.addEventListener('change', calculateTotal);
        });
    }

    const birthDateInput = document.getElementById('birth_date');
    if (birthDateInput && typeof Datepicker !== 'undefined') {
        new Datepicker(birthDateInput, {
            autohide: true,
            format: 'dd/mm/yyyy',
            orientation: 'top',
            todayBtn: true,
            clearBtn: true,
            maxDate: new Date(), // <--- INI LOGIKANYA (Maksimal Hari Ini)
        });
    }

    if (typeof CI_TIME_LEFT !== 'undefined' && CI_TIME_LEFT > 0) {
        startCheckoutTimer(CI_TIME_LEFT);
    }

});


// ============================================================
// ZONA FUNGSI (DEFINISI LOGIKA)
// ============================================================

/**
 * Fungsi: Menghitung Total Belanja & Update Sidebar Kanan
 * Digunakan di halaman select_tickets.php
 */
function calculateTotal() {
    const inputs = document.querySelectorAll('.ticket-input');
    const cartContainer = document.getElementById('cartItems');
    const totalLabel = document.getElementById('totalPrice');
    const btnCheckout = document.getElementById('btnCheckout');
    
    let grandTotal = 0;
    let totalQty = 0;
    let cartHtml = '';

    inputs.forEach(input => {
        // Ambil nilai dari input dan data-attribute HTML
        const qty = parseInt(input.value) || 0;
        const price = parseInt(input.dataset.price);
        const name = input.dataset.name;

        if (qty > 0) {
            const subtotal = qty * price;
            grandTotal += subtotal;
            totalQty += qty;

            // Buat HTML list item untuk sidebar
            cartHtml += `
                <div class="flex justify-between items-center text-sm mb-2 animate-fade-in">
                    <span class="text-gray-700">${qty}x ${name}</span>
                    <span class="font-bold text-gray-900">Rp ${subtotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        }
    });

    // Update Tampilan Sidebar
    if (totalQty === 0) {
        cartContainer.innerHTML = '<div class="flex flex-col items-center justify-center h-full py-4 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200"><p class="text-xs">Belum ada tiket dipilih</p></div>';
    } else {
        cartContainer.innerHTML = cartHtml;
    }

    // Update Label Total Harga
    if (totalLabel) {
        totalLabel.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Update Status Tombol Checkout (Disable jika 0)
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

/**
 * Fungsi Global: Hapus Event (Admin)
 * Menggunakan Confirm bawaan browser yang simpel
 */
function deleteEvent(id) {
    if (confirm('Apakah Anda yakin ingin menghapus event ini? Data tidak bisa dikembalikan.')) {
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
                alert('Event berhasil dihapus.');
            } else {
                alert('Gagal menghapus: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan server.');
        });
    }
}

/**
 * Fungsi Global: Hapus Tiket (Admin)
 */
function deleteTicket(eventId, ticketId) {
    if (confirm('Hapus tiket ini?')) {
        fetch(`/admin/events/${eventId}/tickets/${ticketId}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                const row = document.getElementById(`row-ticket-${ticketId}`);
                if (row) row.remove();
                alert('Tiket berhasil dihapus!');
            } else {
                alert('Gagal menghapus tiket.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus tiket.');
        });
    }
}

function setupPasswordToggle(buttonId, inputId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);

        if (btn && input) {
            btn.addEventListener('click', () => {
                const eyeOpen = btn.querySelector('.eye-open');
                const eyeClosed = btn.querySelector('.eye-closed');

                if (input.type === 'password') {
                    // Show
                    input.type = 'text';
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                } else {
                    // Hide
                    input.type = 'password';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                }
            });
        }
    }

    // Panggil fungsi untuk masing-masing input
    setupPasswordToggle('toggle-password-btn', 'password');             // Login & Register (Utama)
    setupPasswordToggle('toggle-password-confirm-btn', 'password_confirm'); // Register (Konfirmasi)

    function startCheckoutTimer(duration) {
    let timer = duration;
    const headerTimerDisplay = document.getElementById('header-timer');
    const modalTimerDisplay = document.getElementById('modal-timer');
    const modal = document.getElementById('timeout-modal');
    let hasWarned = false;

    const interval = setInterval(function () {
        const minutes = parseInt(timer / 60, 10);
        const seconds = parseInt(timer % 60, 10);

        // Format 00:00
        const displayMin = minutes < 10 ? "0" + minutes : minutes;
        const displaySec = seconds < 10 ? "0" + seconds : seconds;
        const textTime = displayMin + ":" + displaySec;

        // Update Tampilan Header
        if (headerTimerDisplay) headerTimerDisplay.textContent = textTime;
        
        // Update Tampilan Modal
        if (modalTimerDisplay) modalTimerDisplay.textContent = textTime;

        // LOGIKA PERINGATAN (Muncul di 2 Menit Terakhir)
        if (timer <= 120 && !hasWarned) { 
            if(modal) {
                modal.classList.remove('hidden'); // Munculkan Modal
                modal.classList.add('flex');
            }
            hasWarned = true; // Biar gak muncul terus-terusan
        }

        // LOGIKA WAKTU HABIS
        if (--timer < 0) {
            clearInterval(interval);
            alert("Waktu pemesanan habis! Anda akan diarahkan ke halaman utama.");
            window.location.href = "/"; // Redirect ke Home
        }
    }, 1000);
}

// Fungsi Tutup Modal
function closeTimeoutModal() {
    const modal = document.getElementById('timeout-modal');
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}