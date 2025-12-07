<tr id="row-ticket-<?= $ticket['id'] ?>" class="bg-white border-b hover:bg-gray-50">
    <td class="px-6 py-4 font-medium text-gray-900">
        <?= esc($ticket['name']) ?>
        <div class="text-xs text-gray-400 mt-1"><?= esc($ticket['ticket_category']) ?></div>
    </td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 rounded text-xs font-bold uppercase border" 
              style="background-color: <?= $ticket['ui_color'] ?>20; color: <?= $ticket['ui_color'] ?>; border-color: <?= $ticket['ui_color'] ?>;">
            <?= esc($ticket['ui_color']) ?>
        </span>
    </td>
    <td class="px-6 py-4">Rp <?= number_format($ticket['price'], 0, ',', '.') ?></td>
    <td class="px-6 py-4">
        <?= $ticket['quantity_sold'] ?> / <strong><?= $ticket['quantity_total'] ?></strong>
    </td>
    <td class="px-6 py-4 flex items-center gap-3">
        <a href="/admin/events/<?= $event['id'] ?>/tickets/<?= $ticket['id'] ?>/duplicate" 
           class="font-medium text-teal-600 hover:underline flex items-center gap-1" title="Duplikasi Tiket">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            Copy
        </a>

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