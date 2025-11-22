document.addEventListener('DOMContentLoaded', () => {
    if (typeof initFlowbite === 'function') initFlowbite();

    if (document.querySelector('.mySwiper')) {
        new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
            effect: 'slide',
            speed: 800,
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            pagination: { el: ".swiper-pagination", clickable: true },
        });
    }

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

});
/**
 * Fungsi: Hitung Total Harga & Update Sidebar
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
        const qty = parseInt(input.value) || 0;
        const price = parseInt(input.dataset.price);
        const name = input.dataset.name;

        if (qty > 0) {
            const subtotal = qty * price;
            grandTotal += subtotal;
            totalQty += qty;

            cartHtml += `
                <div class="flex justify-between items-center text-sm mb-2">
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

/**
 * Fungsi: Hapus Event (Admin)
 */
function deleteEvent(id) {
    if (confirm('Hapus event ini?')) {
        fetch('/admin/events/' + id, { method: 'DELETE', headers: {'X-Requested-With': 'XMLHttpRequest'} })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') { document.getElementById(`row-event-${id}`).remove(); alert('Terhapus!'); }
        });
    }
}

/**
 * Fungsi: Hapus Tiket (Admin)
 */
function deleteTicket(eventId, ticketId) {
    if (confirm('Hapus tiket ini?')) {
        fetch(`/admin/events/${eventId}/tickets/${ticketId}`, { method: 'DELETE', headers: {'X-Requested-With': 'XMLHttpRequest'} })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') { document.getElementById(`row-ticket-${ticketId}`).remove(); alert('Terhapus!'); }
        });
    }
}