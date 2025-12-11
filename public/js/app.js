document.addEventListener('DOMContentLoaded', () => {

    // Inisialisasi Flowbite
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }

    // Logika Timer Checkout (Floating Alert) - AKTIF
    if (typeof CI_TIME_LEFT !== 'undefined' && CI_TIME_LEFT > 0) {
        startFloatingTimer(CI_TIME_LEFT);
    }

    // Inisialisasi TinyMCE (Admin)
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

    // Logika Hitung Total Harga (Pilih Tiket)
    const ticketInputs = document.querySelectorAll('.ticket-input');
    if (ticketInputs.length > 0) {
        ticketInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
            input.addEventListener('change', calculateTotal);
        });
    }

    // Datepicker (Tanggal Lahir)
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

    // Salin Nomor VA
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

    // Modal Active Session
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

    // Flash Messages (Toast)
    if (typeof CI_FLASH_MESSAGES !== 'undefined') {
        if (CI_FLASH_MESSAGES.success) showToast('success', CI_FLASH_MESSAGES.success);
        if (CI_FLASH_MESSAGES.error) showToast('error', CI_FLASH_MESSAGES.error);
        if (CI_FLASH_MESSAGES.warning) showToast('warning', CI_FLASH_MESSAGES.warning);
        if (CI_FLASH_MESSAGES.errors) {
            Object.values(CI_FLASH_MESSAGES.errors).forEach(msg => showToast('error', msg));
        }
    }

    // Password Toggle (Login & Register)
    setupPasswordToggle('toggle-password-btn', 'password');             
    setupPasswordToggle('toggle-password-confirm-btn', 'password_confirm');

    // Logika OTP 6 Digit
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

    // Init Chart Admin (Safe Check)
    if (typeof initDashboardCharts === 'function') {
        initDashboardCharts();
    }

    // Responsive Search Placeholder (Main Page)
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        const updatePlaceholder = () => {
            if (window.innerWidth < 768) {
                searchInput.placeholder = "Cari Event...";
            } else {
                searchInput.placeholder = "Cari berdasarkan artis, lokasi, atau event...";
            }
        };

        updatePlaceholder();

        window.addEventListener('resize', updatePlaceholder);
    }

    // Logika Tanggal Event (Start & End Date)
    const eventStartInput = document.getElementById('event_date');
    const eventEndInput = document.getElementById('event_end_date');

    if (eventStartInput && eventEndInput) {
        
        const updateEndDateConstraint = () => {
            const startDateVal = eventStartInput.value;

            if (startDateVal) {
                eventEndInput.min = startDateVal;

                if (eventEndInput.value && eventEndInput.value < startDateVal) {
                    eventEndInput.value = '';
                }
            }
        };

        eventStartInput.addEventListener('change', updateEndDateConstraint);
        eventStartInput.addEventListener('blur', updateEndDateConstraint);

        updateEndDateConstraint();
    }

    // Scroll Reveal
    if (typeof initScrollReveal === 'function') {
        initScrollReveal();
    }

    // Logika Ticket Category
    const stockInput = document.getElementById('quantity_total');
    const generatorBox = document.getElementById('seat-generator-box');
    const radioStanding = document.querySelector('input[name="ticket_category"][value="Standing"]');
    const radioSeating = document.querySelector('input[name="ticket_category"][value="Seating"]');
    const radios = document.querySelectorAll('input[name="ticket_category"]');

    // Fungsi Ganti Mode
    function toggleCategoryMode() {
        if (!stockInput || !generatorBox) return;

        const isSeating = radioSeating.checked;

        if (isSeating) {
            stockInput.setAttribute('readonly', true);
            stockInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');
            stockInput.classList.remove('bg-gray-50', 'text-gray-900');
            stockInput.placeholder = "Otomatis dari Generator...";
            stockInput.value = "";

            generatorBox.classList.remove('hidden');
        } else {
            stockInput.removeAttribute('readonly');
            stockInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');
            stockInput.classList.add('bg-gray-50', 'text-gray-900');
            stockInput.placeholder = "Contoh: 100";

            generatorBox.classList.add('hidden');
            document.getElementById('seat_row_start').value = '';
            document.getElementById('seat_row_end').value = '';
            document.getElementById('seats_per_row').value = '';
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', toggleCategoryMode);
    });

    toggleCategoryMode();
    
});


// FUNGSI GLOBAL 

// Password Toggle
function setupPasswordToggle(buttonId, inputId) {
    const btn = document.getElementById(buttonId);
    const input = document.getElementById(inputId);

    if (btn && input) {
        btn.addEventListener('click', () => {
            const eyeOpen = btn.querySelector('#eye-open') || btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('#eye-closed') || btn.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                if(eyeOpen) eyeOpen.classList.remove('hidden');
                if(eyeClosed) eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password';
                if(eyeOpen) eyeOpen.classList.add('hidden');
                if(eyeClosed) eyeClosed.classList.remove('hidden');
            }
        });
    }
}

// Modal Batal Pesan
window.showCancelModal = function() {
    const modal = document.getElementById('cancel-modal');
    const timerAlert = document.getElementById('checkout-timer-alert');

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
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
        
        if (timerAlert) timerAlert.classList.remove('hidden');
    }
}

// Timer (Floating)
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

// Copy Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const popup = document.getElementById('custom-copy-popup');
        if (popup) {
            popup.classList.remove('hidden'); popup.classList.add('flex');
            setTimeout(() => { popup.classList.add('hidden'); popup.classList.remove('flex'); }, 1500);
        } else { alert('Disalin: ' + text); }
    });
}

// Hitung Total
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

// Toast
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

// Admin Modal
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

// Dashboard Chart
function initDashboardCharts() {
    if (typeof window.dashboardData === 'undefined' || typeof ApexCharts === 'undefined') return;
    
    const { revenue, categories, payments, statuses } = window.dashboardData;

    // Revenue Chart
    if (document.getElementById("revenue-chart")) {
        const optionsRev = {
            chart: {
                height: 320,
                type: "bar",
                fontFamily: "Inter, sans-serif",
                toolbar: { show: false },
                animations: { enabled: true }
            },
            series: [{
                name: "Pendapatan",
                data: revenue.map(d => parseInt(d.total)),
                color: "#1A56DB", 
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "50%",
                    borderRadius: 6, 
                    borderRadiusApplication: 'end',
                },
            },
            dataLabels: { enabled: false },
            tooltip: {
                shared: true,
                intersect: false,
                style: { fontFamily: "Inter, sans-serif" },
                y: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString('id-ID');
                    }
                }
            },
            xaxis: {
                categories: revenue.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                }),
                labels: {
                    style: { fontFamily: "Inter, sans-serif", cssClass: 'text-xs font-normal fill-gray-500' }
                },
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: {
                show: true,
                labels: {
                    style: { fontFamily: "Inter, sans-serif", cssClass: 'text-xs font-normal fill-gray-500' },
                    formatter: function (value) {
                        if (value >= 1000000) return (value / 1000000) + "Jt";
                        if (value >= 1000) return (value / 1000) + "Rb";
                        return value;
                    }
                }
            },
            grid: {
                show: true,
                strokeDashArray: 4,
                padding: { left: 10, right: 10, top: -20 }
            },
            fill: { opacity: 1 }
        };

        const chartRev = new ApexCharts(document.getElementById("revenue-chart"), optionsRev);
        chartRev.render();
    }

    // Category Chart
    if (document.getElementById("category-chart")) {
        const optionsCat = {
            series: categories.map(c => parseInt(c.total_sold) || 0),
            colors: ["#1C64F2", "#16BDCA", "#FDBA8C", "#E74694", "#F59E0B"],
            chart: {
                height: 300,
                type: "donut",
                fontFamily: "Inter, sans-serif",
            },
            stroke: { colors: ["transparent"] },
            plotOptions: {
                pie: {
                    donut: {
                        size: "75%",
                        labels: {
                            show: true,
                            name: { show: true, fontFamily: "Inter, sans-serif", offsetY: -10 },
                            total: {
                                show: true,
                                showAlways: true,
                                label: "Total Tiket",
                                fontFamily: "Inter, sans-serif",
                                formatter: function (w) {
                                    const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return sum;
                                }
                            }
                        }
                    }
                }
            },
            labels: categories.map(c => c.category),
            dataLabels: { enabled: false },
            legend: {
                position: "bottom",
                fontFamily: "Inter, sans-serif",
            },
        };
        const chartCat = new ApexCharts(document.getElementById("category-chart"), optionsCat);
        chartCat.render();
    }

    // Payment Chart
    if (document.getElementById("payment-chart")) {
        const optionsPay = {
            series: payments.map(p => parseInt(p.total_usage)),
            labels: payments.map(p => p.payment_method.toUpperCase()),
            colors: ["#31C48D", "#F98080", "#8DA2FB", "#FACA15"],
            chart: {
                height: 300,
                type: "donut",
                fontFamily: "Inter, sans-serif",
            },
            stroke: { colors: ["transparent"] },
            plotOptions: {
                pie: {
                    donut: {
                        size: "65%",
                        labels: { show: false }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { position: "bottom", fontFamily: "Inter, sans-serif" },
        };
        const chartPay = new ApexCharts(document.getElementById("payment-chart"), optionsPay);
        chartPay.render();
    }

    // Status Chart
    if (document.getElementById("status-chart")) {

        const statusColors = {
            'completed': '#0E9F6E', 
            'pending': '#FACA15',   
            'expired': '#9CA3AF',   
            'cancelled': '#F05252'  
        };
        
        const labels = statuses.map(s => s.status.charAt(0).toUpperCase() + s.status.slice(1));
        const series = statuses.map(s => parseInt(s.total));
        const colors = statuses.map(s => statusColors[s.status] || '#3F83F8');

        const optionsStat = {
            series: series,
            labels: labels,
            colors: colors,
            chart: {
                height: 300,
                type: "pie",
                fontFamily: "Inter, sans-serif",
            },
            stroke: { colors: ["transparent"] },
            dataLabels: { 
                enabled: true,
                style: { fontFamily: "Inter, sans-serif" },
                dropShadow: { enabled: false }
            },
            legend: { position: "bottom", fontFamily: "Inter, sans-serif" },
        };
        const chartStat = new ApexCharts(document.getElementById("status-chart"), optionsStat);
        chartStat.render();
    }
}


// Payment Modal

window.showPaymentConfirmModal = function() {
    const modal = document.getElementById('payment-confirm-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

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

// Proses AJAX ke Server
window.processPaymentAjax = function(orderId) {
    const btn = document.getElementById('btn-process-ajax');
    const btnText = document.getElementById('btn-ajax-text');
    const btnSpinner = document.getElementById('btn-ajax-spinner');
    
    const csrfInput = document.getElementById('csrf_security');
    
    if (!csrfInput) {
        alert("Error: Token keamanan tidak ditemukan. Coba refresh halaman.");
        return;
    }

    const csrfName = csrfInput.name;
    const csrfHash = csrfInput.value;

    if(btn) {
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    }
    if(btnText) btnText.textContent = 'Memproses...';
    if(btnSpinner) btnSpinner.classList.remove('hidden');

    const postData = {
        [csrfName]: csrfHash 
    };

    fetch('/checkout/confirm/' + orderId, {
        method: 'POST',
        credentials: 'include', 
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfHash 
        },
        body: JSON.stringify(postData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server menolak request (Status: ' + response.status + ')');
        }
        return response.json();
    })
    .then(data => {
        closePaymentModals(); 

        if (data.status === 'success') {
            if(document.getElementById('success-email')) 
                document.getElementById('success-email').textContent = data.email;
            
            if(document.getElementById('success-trx'))
                document.getElementById('success-trx').textContent = data.trx_id;
            
            const successModal = document.getElementById('payment-success-modal');
            if(successModal) {
                successModal.classList.remove('hidden');
                successModal.classList.add('flex');
            }
            
            const timerEl = document.getElementById('checkout-timer-alert');
            if(timerEl) timerEl.remove();

        } else {
            if(document.getElementById('error-message'))
                document.getElementById('error-message').textContent = data.message;
            
            const errorModal = document.getElementById('payment-error-modal');
            if(errorModal) {
                errorModal.classList.remove('hidden');
                errorModal.classList.add('flex');
            }
            
            resetBtnState();
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        closePaymentModals();
        alert('Gagal memproses: ' + error.message);
        resetBtnState();
    });

    function resetBtnState() {
        if(btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
        if(btnText) btnText.textContent = 'Ya, Sudah Bayar';
        if(btnSpinner) btnSpinner.classList.add('hidden');
    }
}

// Fungsi Animasi Scroll
function initScrollReveal() {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1 
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    const elements = document.querySelectorAll('.reveal-on-scroll');
    elements.forEach((el) => observer.observe(el));
}

window.autoCalculateStock = function() {
    const stockInput  = document.getElementById('quantity_total');
    const inputStart  = document.getElementById('seat_row_start');
    const inputEnd    = document.getElementById('seat_row_end');
    const inputPerRow = document.getElementById('seats_per_row');

    if (!stockInput || !inputStart || !inputEnd || !inputPerRow) return;

    const startChar = inputStart.value.toUpperCase().trim();
    const endChar   = inputEnd.value.toUpperCase().trim();
    const perRow    = parseInt(inputPerRow.value) || 0;

    if (startChar && endChar && perRow > 0) {
        const startCode = startChar.charCodeAt(0);
        const endCode   = endChar.charCodeAt(0);

        if (endCode >= startCode && startCode >= 65 && startCode <= 90) { 
            const totalRows = (endCode - startCode) + 1;
            stockInput.value = totalRows * perRow;
        }
    }
}

// Dropdown Filter
function selectStatus(value, label) {
    const input = document.getElementById('statusInput');
    const btnText = document.getElementById('btnText');
    const dropdown = document.getElementById('dropdownStatus');
    
    if (input && btnText) {
        input.value = value;
        btnText.innerText = label;
    }
    
    if (dropdown) {
        dropdown.classList.add('hidden');
    }
}

// Update Status Dropdown
function setUpdateStatus(value, label) {
    const input = document.getElementById('updateStatusInput');
    const btnText = document.getElementById('btnUpdateText');
    const dropdown = document.getElementById('dropdownUpdateStatus');
    if (input && btnText) {
        input.value = value;
        btnText.innerText = label;
    }

    if (dropdown) {
        dropdown.classList.add('hidden');
    }
}

document.addEventListener('click', function(event) {

    const dropdowns = [
        { btn: 'dropdownStatusBtn', menu: 'dropdownStatus' },       
        { btn: 'updateStatusBtn', menu: 'dropdownUpdateStatus' }    
    ];

    dropdowns.forEach(function(item) {
        const button = document.getElementById(item.btn);
        const menu = document.getElementById(item.menu);
i
        if (button && menu) {
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        }
    });
});