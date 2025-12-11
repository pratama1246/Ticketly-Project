<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 p-4">
        <div class="lg:col-span-2">
            
            <a href="/admin/orders" class="bg-yellow-accent-normal hover:bg-yellow-accent-normal-hover text-gray-700 hover:text-gray-900 flex items-center gap-3 w-max px-3 py-2 rounded-base mb-4">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <h1 class="text-xs font-bold text-gray-900 m-0">Kembali Ke Halaman Order</h1>
                </a>
                
            <div class="bg-yellow-accent-light border border-gray-200 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Item Pesanan</h3>
                    
                    <a href="/admin/orders/pdf/<?= $order['id'] ?>" target="_blank" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2.5 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Tiket
                    </a>
                </div>

                <div class="relative overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3">Event</th>
                                <th class="px-6 py-3">Jenis Tiket</th>
                                <th class="px-6 py-3">Kursi</th>
                                <th class="px-6 py-3">Kode Tiket</th>
                                <th class="px-6 py-3 text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($items as $item): 
                                $seat = $item['label'] ? $item['label'] : ($item['seat_row'] ? $item['seat_row'].'-'.$item['seat_number'] : '-');
                            ?>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900"><?= esc($item['event_name']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                                        <?= esc($item['ticket_name']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900"><?= esc($seat) ?></td>
                                <td class="px-6 py-4 font-bold text-gray-900 rounded inline-block">
                                    <?= esc($item['ticket_code']) ?>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-gray-900">
                                    Rp <?= number_format($item['price_per_ticket'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr class="font-semibold text-gray-900">
                                <td colspan="4" class="px-6 py-3 text-right uppercase text-xs">Total Transaksi</td>
                                <td class="px-6 py-3 text-right text-lg">Rp <?= number_format($order['order_total'], 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-yellow-accent-light border border-gray-200 rounded-lg shadow-sm p-6">
                <h3 class="mb-4 text-xl font-bold text-gray-900 border-b border-gray-100 pb-2">Data Pembeli</h3>
                
                <ul class="space-y-4 text-sm text-gray-600 mb-6">
                    <li class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase">Nama Lengkap</span>
                        <span class="font-medium text-gray-900 text-base"><?= esc($order['first_name'].' '.$order['last_name']) ?></span>
                    </li>
                    <li class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase">Email</span>
                        <span class="font-medium text-gray-900"><?= esc($order['email']) ?></span>
                    </li>
                    <li class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase">No. Handphone</span>
                        <span class="font-medium text-gray-900"><?= esc($order['phone_number']) ?></span>
                    </li>
                    <li class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase">NIK / Identitas</span>
                        <span class="font-medium text-gray-900"><?= esc($order['identity_number']) ?></span>
                    </li>
                    <li class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase">Tanggal Pembelian</span>
                        <span class="font-medium text-gray-900"><?= \CodeIgniter\I18n\Time::parse($order['created_at'])->toLocalizedString('d MMMM yyyy, HH:mm') ?></span>
                    </li>
                    <li class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase">Pembayaran</span>
                        <span class="font-medium text-gray-900 uppercase"><?= esc($order['payment_method']) ?></span>
                    </li>
                </ul>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="mb-3 text-sm font-semibold text-gray-900 uppercase">Update Status</h4>
                    <form action="/admin/orders/update-status" method="post" class="space-y-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        
                        <input type="hidden" name="status" id="updateStatusInput" value="<?= esc($order['status']) ?>">

                        <div class="relative">
                            <button id="updateStatusBtn" data-dropdown-toggle="dropdownUpdateStatus" class="w-full text-gray-900 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center justify-between" type="button">
                                <span id="btnUpdateText"><?= ucfirst($order['status']) ?></span>
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>

                            <div id="dropdownUpdateStatus" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-full border border-gray-100">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="updateStatusBtn">
                                    <li>
                                        <button type="button" onclick="setUpdateStatus('pending', 'Pending')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700">
                                            <span class="inline-block w-2 h-2 rounded-full bg-yellow-400 mr-2"></span> Pending
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" onclick="setUpdateStatus('completed', 'Completed')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700">
                                            <span class="inline-block w-2 h-2 rounded-full bg-green-400 mr-2"></span> Completed
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" onclick="setUpdateStatus('expired', 'Expired')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700">
                                            <span class="inline-block w-2 h-2 rounded-full bg-gray-400 mr-2"></span> Expired
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" onclick="setUpdateStatus('cancelled', 'Cancelled')" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 text-red-600 hover:text-red-700">
                                            <span class="inline-block w-2 h-2 rounded-full bg-red-400 mr-2"></span> Cancelled
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>