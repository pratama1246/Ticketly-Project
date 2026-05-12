<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-black max-w-[600px] mb-4"><?= esc($title) ?></h1>
            <a href="/admin/events/<?= $event['id'] ?>" class="text-sm text-gray-600 hover:underline">&larr; Kembali ke Detail Event</a>
        </div>
        <a href="/admin/events/<?= $event['id'] ?>/tickets/new" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
            + Tambah Tiket
        </a>
    </div>

    <?php if (empty($tickets)): ?>
        <div class="p-10 text-center bg-white rounded-lg shadow border border-gray-200">
            <p class="text-gray-500">Belum ada tiket untuk event ini.</p>
        </div>
    <?php else: ?>

        <?php
        $groupedTickets = [
            'pass' => [],
            'daily' => []
        ];

        foreach ($tickets as $t) {
            if (empty($t['ticket_date'])) {
                $groupedTickets['pass'][] = $t;
            } else {
                $groupedTickets['daily'][$t['ticket_date']][] = $t;
            }
        }

        ksort($groupedTickets['daily']);
        ?>

        <div class="space-y-8">
            
            <?php if (!empty($groupedTickets['pass'])): ?>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
                <div class="bg-purple-100 px-6 py-3 border-b border-purple-200">
                    <h3 class="font-bold text-purple-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        ALL DAYS PASS
                    </h3>
                </div>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Nama Tiket</th>
                            <th class="px-6 py-3">Warna UI</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Kuota</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupedTickets['pass'] as $ticket): ?>
                            <?= view('admin/tickets/_row_ticket', ['ticket' => $ticket, 'event' => $event]) ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php foreach ($groupedTickets['daily'] as $dateKey => $dailyTickets): ?>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
                <div class="bg-yellow-accent-light px-6 py-3 border-b border-yellow-accent-normal">
                    <h3 class="font-bold text-yellow-accent-dark flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <?= date('d F Y', strtotime($dateKey)) ?> 
                        <span class="text-xs font-normal text-yellow-accent-normal ml-2 bg-white px-2 py-0.5 rounded-full border border-yellow-accent-normal">Daily Pass</span>
                    </h3>
                </div>
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Nama Tiket</th>
                            <th class="px-6 py-3">Warna UI</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Kuota</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dailyTickets as $ticket): ?>
                            <?= view('admin/tickets/_row_ticket', ['ticket' => $ticket, 'event' => $event]) ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>