<main class="w-full pt-16 grow bg-yellow-accent-light min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="/" class="bg-yellow-accent-normal hover:bg-yellow-accent-normal-hover text-gray-700 hover:text-gray-900 flex items-center gap-3 w-max px-3 py-2 mt-4 mb-4 rounded-base">
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <h1 class="text-xs md:text-sm font-bold text-gray-900 m-0">Kembali</h1>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
                <p class="text-gray-500 mt-1">Daftar semua pembelian riwayat tiketmu.</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100 overflow-x-auto">
                        <tr>
                            <th scope="col" class="px-6 py-4">No. Order</th>
                            <th scope="col" class="px-6 py-4">Tanggal</th>
                            <th scope="col" class="px-6 py-4">Total</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(empty($orders)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">Belum ada transaksi</h3>
                                        <p class="mb-4">Kamu belum membeli tiket apapun.</p>
                                        <a href="/events" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                            Cari Event Seru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    #<?= esc($order['trx_id']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= \CodeIgniter\I18n\Time::parse($order['created_at'])->toLocalizedString('d MMM yyyy, HH:mm') ?>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    Rp <?= number_format($order['order_total'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($order['status'] == 'completed'): ?>
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Berhasil</span>
                                    <?php elseif ($order['status'] == 'pending'): ?>
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Menunggu</span>
                                    <?php elseif ($order['status'] == 'expired'): ?>
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Kadaluarsa</span>
                                    <?php else: ?>
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><?= ucfirst($order['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                        <a href="/profile/transactions/<?= $order['id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium text-xs border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                            Detail
                                        </a>
                                        <?php if ($order['status'] == 'pending'): ?>
                                            <a href="/checkout/pay/<?= $order['id'] ?>" class="ml-2 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-xs px-3 py-1.5">Bayar</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>