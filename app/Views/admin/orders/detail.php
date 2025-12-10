
<div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
    
    <div class="col-span-2">
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Item Pesanan</h3>
                <a href="/admin/orders/pdf/<?= $order['id'] ?>" target="_blank" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-3 py-2">
                    🖨️ Cetak Tiket PDF
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Event</th>
                            <th class="px-4 py-3">Tiket</th>
                            <th class="px-4 py-3">Kursi</th>
                            <th class="px-4 py-3">Kode Tiket</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): 
                            $seat = $item['label'] ? $item['label'] : ($item['seat_row'] ? $item['seat_row'].'-'.$item['seat_number'] : '-');
                        ?>
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white"><?= esc($item['event_name']) ?></td>
                            <td class="px-4 py-3"><?= esc($item['ticket_name']) ?></td>
                            <td class="px-4 py-3 font-bold text-blue-600"><?= esc($seat) ?></td>
                            <td class="px-4 py-3 font-mono text-xs"><?= esc($item['ticket_code']) ?></td>
                            <td class="px-4 py-3 text-right">Rp <?= number_format($item['price_per_ticket'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold text-gray-900 dark:text-white">
                            <td colspan="4" class="px-4 py-3 text-right">Total Order</td>
                            <td class="px-4 py-3 text-right">Rp <?= number_format($order['order_total'], 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-span-1">
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Informasi Pembeli</h3>
            <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                <li class="flex justify-between">
                    <span class="font-medium">Nama:</span>
                    <span class="text-gray-900 dark:text-white"><?= esc($order['first_name'].' '.$order['last_name']) ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="font-medium">Email:</span>
                    <span class="text-gray-900 dark:text-white"><?= esc($order['email']) ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="font-medium">No. HP:</span>
                    <span class="text-gray-900 dark:text-white"><?= esc($order['phone_number']) ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="font-medium">NIK:</span>
                    <span class="text-gray-900 dark:text-white"><?= esc($order['identity_number']) ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="font-medium">Metode Bayar:</span>
                    <span class="uppercase text-gray-900 dark:text-white"><?= esc($order['payment_method']) ?></span>
                </li>
                <li class="flex justify-between items-center">
                    <span class="font-medium">Status Saat Ini:</span>
                    <span class="px-2 py-1 text-xs font-bold rounded 
                        <?= $order['status'] == 'completed' ? 'bg-green-100 text-green-800' : 
                           ($order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                        <?= strtoupper($order['status']) ?>
                    </span>
                </li>
            </ul>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Update Status Manual</h3>
            <form action="/admin/orders/update-status" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                
                <div class="mb-3">
                    <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed (Lunas)</option>
                        <option value="expired" <?= $order['status'] == 'expired' ? 'selected' : '' ?>>Expired (Hangus)</option>
                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled (Batal)</option>
                    </select>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>