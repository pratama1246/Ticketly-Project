<div class="p-4">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-black mb-1">Manajemen Order</h1>
            </div>
            
            <form action="" method="get" class="flex gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" name="keyword" value="<?= esc($keyword) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-4 focus:outline-none focus:ring-blue-300 block w-full pl-10 p-2.5" placeholder="Cari Order ID / Email...">
                </div>
                
                <input type="hidden" name="status" id="statusInput" value="<?= esc($status) ?>">
                        <div class="relative">
                            <button id="dropdownStatusBtn" data-dropdown-toggle="dropdownStatus" class="w-full sm:w-auto text-gray-900 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center justify-between" type="button">
                                <span id="btnText"><?= $status ? ucfirst($status) : 'Semua Status' ?></span>
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>

                            <div id="dropdownStatus" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 border border-gray-100">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownStatusBtn">
                                    <li>
                                        <button type="button" onclick="selectStatus('', 'Semua Status')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700">Semua Status</button>
                                    </li>
                                    <li>
                                        <button type="button" onclick="selectStatus('pending', 'Pending')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700">Menunggu</button>
                                    </li>
                                    <li>
                                        <button type="button" onclick="selectStatus('completed', 'Completed')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700">Selesai</button>
                                    </li>
                                    <li>
                                        <button type="button" onclick="selectStatus('expired', 'Expired')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700">Kadaluarsa</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                
                <button type="submit" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Cari
                </button>
            </form>
        </div>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
            <table class="w-full text-sm text-left text-gray-500 min-w-[1200px]">
                <thead class="text-xs text-gray-700 uppercase bg-yellow-accent-light border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">TRX ID</th>
                        <th scope="col" class="px-6 py-3">Pembeli</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                        <tr class="bg-white border-b">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data order ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                #<?= esc($o['trx_id']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-base font-semibold text-gray-900"><?= esc($o['first_name'] . ' ' . $o['last_name']) ?></div>
                                <div class="font-normal text-gray-500"><?= esc($o['email']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?= \CodeIgniter\I18n\Time::parse($o['created_at'])->toLocalizedString('d MMMM yyyy, HH:mm') ?>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                Rp <?= number_format($o['order_total'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($o['status'] == 'completed'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Selesai</span>
                                <?php elseif($o['status'] == 'pending'): ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-300">Menunggu</span>
                                <?php else: ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400"><?= esc(ucfirst($o['status'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="/admin/orders/detail/<?= $o['id'] ?>" class="text-black bg-yellow-accent-normal box-border border border-transparent hover:bg-yellow-accent-normal-hover focus:ring-4 focus:ring-yellow-accent-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 p-4">
    <?= $pager->links('default', 'flowbite_pager') ?>
</div>