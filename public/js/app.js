document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Inisialisasi Flowbite (Wajib untuk dropdown/carousel)
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }

    // 2. Inisialisasi TinyMCE (Editor Teks Admin)
    if (document.getElementById('description')) {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: 'textarea#description',
                promotion: false,
                branding: false,
                plugins: 'lists link code table autoresize',
                toolbar: 'undo redo | blocks | bold italic | bullist numlist | link | table | code',
                menubar: false,
                formats: {
                    ul: { selector: 'ul', classes: 'list-disc ml-5' },
                    ol: { selector: 'ol', classes: 'list-decimal ml-5' },
                    p: { selector: 'p', classes: 'mb-4' },
                    h3: { selector: 'h3', classes: 'text-xl font-bold mb-2' }
                },
                content_style: `
                    body { font-family: 'Poppins', sans-serif; font-size: 14px; color: #000000; }
                    ul { list-style-type: disc; margin-left: 1.25rem; }
                    ol { list-style-type: decimal; margin-left: 1.25rem; }
                    .list-disc { list-style-type: disc; }
                    .list-decimal { list-style-type: decimal; }
                    .ml-5 { margin-left: 1.25rem; }
                    .mb-4 { margin-bottom: 1rem; }
                    .text-xl { font-size: 1.25rem; }
                    .font-bold { font-weight: 700; }
                `
            });
        }
    }

    // 3. Inisialisasi Swiper (Carousel Homepage)
    if (document.querySelector('.mySwiper')) {
        new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
            effect: 'slide',
            speed: 800,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    }

    // 4. Logika Hitung Total di Halaman Pilih Tiket
    const ticketInputs = document.querySelectorAll('.ticket-input');
    if (ticketInputs.length > 0) {
        const cartContainer = document.getElementById('cartItems');
        const totalLabel = document.getElementById('totalPrice');
        const btnCheckout = document.getElementById('btnCheckout');

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

                    cartHtml += `
                        <div class="flex justify-between items-center">
                            <span class="text-gray-800 font-medium">${qty}x <span class="text-gray-600 font-normal">${name}</span></span>
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
                    btnCheckout.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                    btnCheckout.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-lg');
                } else {
                    btnCheckout.disabled = true;
                    btnCheckout.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                    btnCheckout.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-lg');
                }
            }
        }

        ticketInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
            input.addEventListener('change', calculateTotal);
        });
    }
});

// --- FUNGSI GLOBAL: Delete Event (Pakai Confirm Biasa) ---
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

// --- FUNGSI GLOBAL: Delete Tiket (Pakai Confirm Biasa) ---
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
                alert('Tiket dihapus!');
            } else {
                alert('Gagal menghapus.');
            }
        });
    }
}