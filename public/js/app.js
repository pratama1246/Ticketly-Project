document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Inisialisasi Flowbite
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }

    // 2. Logika Timer Checkout (Floating Alert)
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

    // 5. Datepicker (Tanggal Lahir - Max Hari Ini)
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

    // 6. Tombol Salin Nomor VA (Checkout Pay)
    const btnCopyVa = document.getElementById('btn-copy-va');
    if (btnCopyVa) {
        btnCopyVa.addEventListener('click', function() {
            const vaTextElement = document.getElementById('va-number');
            if(vaTextElement) {
                const vaText = vaTextElement.innerText.replace(/\s/g, ''); // Hapus spasi
                copyToClipboard(vaText);
            }
        });
    }

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

    // 7. Flash Messages (Toast Notifications)
    if (typeof CI_FLASH_MESSAGES !== 'undefined') {
        
        // Cek Success
        if (CI_FLASH_MESSAGES.success) {
            showToast('success', CI_FLASH_MESSAGES.success);
        }

        // Cek Error (Single)
        if (CI_FLASH_MESSAGES.error) {
            showToast('error', CI_FLASH_MESSAGES.error);
        }

        // Cek Warning
        if (CI_FLASH_MESSAGES.warning) {
            showToast('warning', CI_FLASH_MESSAGES.warning);
        }

        // Cek Errors (Validation Array/Object)
        if (CI_FLASH_MESSAGES.errors) {
            // Ubah Object/Array jadi Array murni biar bisa di-loop
            const errorList = Object.values(CI_FLASH_MESSAGES.errors);
            errorList.forEach(msg => {
                showToast('error', msg);
            });
        }
    }

    // 8. Password Toggle (Login & Register)
    setupPasswordToggle('toggle-password-btn', 'password');             
    setupPasswordToggle('toggle-password-confirm-btn', 'password_confirm');

    // 9. LOGIKA OTP 6 DIGIT
    const otpContainer = document.getElementById('otp-container');
    
    // Jalankan hanya jika elemen otp-container ada di halaman ini
    if (otpContainer) {
        const inputs = otpContainer.querySelectorAll('.otp-box');
        const hiddenToken = document.getElementById('real-token');

        const updateHiddenToken = () => {
            let tokenValue = '';
            inputs.forEach(input => {
                tokenValue += input.value;
            });
            if (hiddenToken) hiddenToken.value = tokenValue;
        };

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value;

                if (isNaN(val)) {
                    e.target.value = "";
                    return;
                }

                if (val !== "") {
                    updateHiddenToken();
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (input.value === '' && index > 0) {
                        inputs[index - 1].focus();
                    } 
                    setTimeout(updateHiddenToken, 10);
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').slice(0, 6);
                
                if (!/^\d+$/.test(pasteData)) return;

                pasteData.split('').forEach((char, i) => {
                    if (inputs[i]) {
                        inputs[i].value = char;
                    }
                });
                updateHiddenToken();
                
                // Fokus ke input terakhir yang terisi
                const lastIndex = Math.min(pasteData.length, inputs.length) - 1;
                if (inputs[lastIndex]) {
                    inputs[lastIndex].focus();
                }
            });

            input.addEventListener('focus', () => {
                input.select();
            });
        });

        // Fokus otomatis ke kotak pertama saat halaman dimuat
        if (inputs[0]) inputs[0].focus();
    }

});


// ZONA FUNGSI (DEFINISI LOGIKA)

// 1. Fungsi: Salin ke Clipboard dengan Popup Custom
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        
        const popup = document.getElementById('custom-copy-popup');
        
        if (popup) {
            popup.classList.remove('hidden');
            popup.classList.add('flex');
            
            setTimeout(() => {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
            }, 1500);
        } else {
            alert('Disalin: ' + text); 
        }

    }).catch(err => {
        console.error('Gagal menyalin: ', err);
        alert('Gagal menyalin otomatis. Silakan salin manual.');
    });
}

// 2. Fungsi: Timer Floating Alert di Checkout
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
            if (bodyText) {
                bodyText.classList.remove('text-gray-900', 'text-blue-600');
                bodyText.classList.add('text-red-600', 'animate-pulse');
            }
        }

        // WAKTU HABIS
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

// 3. Fungsi: Hitung Total Harga Tiket (Pilih Tiket)
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

            cartHtml += `
                <div class="flex justify-between items-center text-sm mb-2 animate-fade-in">
                    <span class="text-gray-700">${qty}x ${name}</span>
                    <span class="font-bold text-gray-900">Rp ${subtotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        }
    });

    if (totalQty === 0) {
        cartContainer.innerHTML = '<div class="flex flex-col items-center justify-center h-full py-4 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200"><p class="text-xs">Belum ada tiket dipilih</p></div>';
    } else {
        cartContainer.innerHTML = cartHtml;
    }

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

// 4. Fungsi: Hapus Event (Admin)
function deleteEvent(id) {
    if (confirm('Apakah Anda yakin ingin menghapus event ini? Data tidak bisa dikembalikan.')) {
        fetch('/admin/events/' + id, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
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
        });
    }
}

// 5. Fungsi: Hapus Kategori (Admin)
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
        });
    }
}

// 6. Fungsi: Toggle Tampilkan/Sembunyikan Password
function setupPasswordToggle(buttonId, inputId) {
    const btn = document.getElementById(buttonId);
    const input = document.getElementById(inputId);

    if (btn && input) {
        btn.addEventListener('click', () => {
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text'; // Show
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password'; // Hide
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        });
    }
}

// 7. Fungsi: Tampilkan Toast Notification
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
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    ${iconSvg}
                </svg>
            </div>
            <div class="ms-3 text-sm font-normal">${message}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    `;

    const toastElement = document.createElement('div');
    toastElement.innerHTML = toastHtml;
    container.appendChild(toastElement.firstElementChild);

    // Hapus otomatis setelah 4 detik
    setTimeout(() => {
        if (container.firstElementChild) container.firstElementChild.remove();
    }, 4000);
}