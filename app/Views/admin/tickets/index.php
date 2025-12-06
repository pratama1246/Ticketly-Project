<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-black max-w-[600px] mb-4"><?= esc($title) ?></h1>
        <a href="/admin/events/<?= $event['id'] ?>" class="text-sm text-gray-600 hover:underline">&larr; Kembali ke Detail Event</a>
    </div>
    <a href="/admin/events/<?= $event['id'] ?>/tickets/new" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
        + Tambah Tiket
    </a>
</div>

<!-- TABEL TIKET -->
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 min-w-[800px]">
        <thead class="text-xs text-gray-700 uppercase bg-yellow-accent-light">
            <tr>
                <th class="px-6 py-3">Nama Tiket</th>
                <th class="px-6 py-3">Warna UI</th>
                <th class="px-6 py-3">Harga</th>
                <th class="px-6 py-3">Kuota</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr class="bg-white border-b">
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        Belum ada tiket untuk event ini.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                <tr id="row-ticket-<?= $ticket['id'] ?>" class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900"><?= esc($ticket['name']) ?></td>
                    <td class="px-6 py-4">
                        <?php
                            $colors = [
                                'green' => 'bg-green-100 text-green-800 border-green-200',
                                'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'red' => 'bg-red-100 text-red-800 border-red-200',
                                'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
                            ];
                            $class = $colors[$ticket['ui_color']] ?? 'bg-gray-100';
                        ?>
                        <span class="px-3 py-1 rounded text-xs font-bold uppercase border <?= $class ?>">
                            <?= esc($ticket['ui_color']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">Rp <?= number_format($ticket['price'], 0, ',', '.') ?></td>
                    <td class="px-6 py-4">
                        <?= $ticket['quantity_sold'] ?> / <strong><?= $ticket['quantity_total'] ?></strong>
                    </td>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <a href="/admin/events/<?= $event['id'] ?>/tickets/<?= $ticket['id'] ?>/edit" 
                        class="font-medium text-blue-600 hover:underline">
                        Edit
                        </a>
                        
                        <button onclick="deleteTicket(<?= $event['id'] ?>, <?= $ticket['id'] ?>)" 
                                class="font-medium text-red-600 hover:underline">
                            Hapus
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Skrip Hapus Tiket (Bakal di ganti) -->
<script>
function deleteTicket(eventId, ticketId) {
    if(typeof customModal !== 'undefined') {
        customModal.show(
            'Hapus Tiket?', 
            'Data tiket akan dihapus permanen.', 
            'Hapus', 
            'bg-red-600', 
            () => {
                fetch(`/admin/events/${eventId}/tickets/${ticketId}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        document.getElementById(`row-ticket-${ticketId}`).remove();
                        alert('Tiket dihapus!'); // Bisa ganti toast/alert lain
                    } else {
                        alert('Gagal.');
                    }
                });
            }
        );
    } else {
        if(confirm('Hapus tiket?')) {
            // Fallback logic fetch sama...
        }
    }
}
</script>