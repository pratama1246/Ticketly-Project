<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-black">
        <?= esc($title) ?>
    </h1>
    <a href="/admin/events/new" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
        + Tambah Event Baru
    </a>
</div>

<?php if (session()->getFlashdata('message')): ?>
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            <span class="font-medium">Sukses!</span> <?= session()->getFlashdata('message') ?>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <span class="font-medium">Error!</span> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Nama Event
                </th>
                <th scope="col" class="px-6 py-3">
                    Tanggal
                </th>
                <th scope="col" class="px-6 py-3">
                    Venue
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
                <th scope="col" class="px-6 py-3">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($events)): ?>
                <tr class="bg-white border-b">
                    <td colspan="5" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="p-3 bg-gray-100 rounded-full mb-3">
                                <svg class="w-10 h-10 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 13h16M4 17h16M4 21h16M4 9h16M4 5h16"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Belum ada event</h3>
                            <p class="text-gray-500 mb-4">Mulai buat event pertamamu untuk ditampilkan di website.</p>
                            
                            <a href="/admin/events/new" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                + Buat Event Sekarang
                            </a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                <tr id="row-event-<?= $event['id'] ?>" class="bg-white border-b hover:bg-gray-50">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <?= esc($event['name']) ?>
                    </th>
                    <td class="px-6 py-4">
                        <?= (new \DateTime(esc($event['event_date'])))->format('d F Y') ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= esc($event['venue']) ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($event['status'] === 'published'): ?>
                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-green-400">
                                Tayang (Published)
                            </span>
                        <?php else: ?>
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-gray-500">
                                Draft (Sembunyi)
                            </span>
                        <?php endif; ?>

                        <?php if ($event['is_featured']): ?>
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded border border-purple-400">
                                Featured
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <a href="/admin/events/edit/<?= $event['id'] ?>" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Edit</a>
                        <button type="button" 
                                onclick="deleteEvent(<?= $event['id'] ?>)" 
                                class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            Hapus
                        </button>   
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

