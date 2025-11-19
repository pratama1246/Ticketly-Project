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
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($events)): ?>
                <tr class="bg-white border-b">
                    <td colspan="4" class="px-6 py-4 text-center">
                        Belum ada event.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                <tr class="bg-white border-b hover:bg-gray-50">
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
                        <a href="/admin/events/edit/<?= $event['id'] ?>" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Edit</a>
                    <form action="/admin/events/<?= $event['id'] ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini? Data yang dihapus tidak bisa dikembalikan.');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                Hapus
                            </button>
                        </form>    
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

