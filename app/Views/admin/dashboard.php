<div class="p-4">
        
        <h1 class="text-2xl font-bold text-black mb-6">
            Statistik Penjualan
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-blue-soft-light-hover p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">
                        Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
                    </h3>
                    <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +12% <span class="text-gray-400 font-normal ml-1">vs bulan lalu</span>
                    </span>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-palette-purple-light-hover p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tiket Terjual</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">
                        <?= number_format($totalTickets) ?>
                    </h3>
                    <span class="text-xs text-gray-400 mt-2 block">Tiket Terkonfirmasi</span>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
            </div>

            <div class="bg-yellow-bright-light-hover p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Total Pesanan</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">
                        <?= number_format($totalOrders) ?>
                    </h3>
                    <span class="text-xs text-gray-400 mt-2 block">Transaksi berhasil</span>
                </div>
                <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>

            <div class="bg-palette-orange-light p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Event Aktif</p>
                    <h3 class="text-2xl font-extrabold text-gray-900">
                        <?= number_format($totalEvents) ?>
                    </h3>
                    <span class="text-xs text-gray-400 mt-2 block">Siap dipasarkan</span>
                </div>
                <div class="p-3 bg-pink-50 text-pink-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
        </div>

       <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 lg:col-span-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tren Pendapatan</h3>
                <div class="relative h-80 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex flex-col items-center">
                <h3 class="text-lg font-bold text-gray-900 mb-4 w-full text-left">Event Terlaris</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex flex-col items-center">
                <h3 class="text-lg font-bold text-gray-900 mb-4 w-full text-left">Metode Bayar</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex flex-col items-center">
                <h3 class="text-lg font-bold text-gray-900 mb-4 w-full text-left">Status Order</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

        </div>

        <h2 class="text-2xl font-bold text-black mb-4">Pesanan Terbaru</h2>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-8">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-yellow-accent-light">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-58">Order ID</th>
                        <th scope="col" class="px-6 py-3">Nama Pembeli</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($recentOrders)): ?>
                        <tr class="bg-white border-b">
                            <td colspan="5" class="px-6 py-4 text-center">Belum ada pesanan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900">
                                #<?= esc($order['trx_id']) ?>
                            </th>
                            <td class="px-6 py-4">
                                <?= esc($order['first_name'] . ' ' . $order['last_name']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= esc($order['email']) ?>
                            </td>
                            <td class="px-6 py-4">
                                Rp <?= number_format($order['order_total'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($order['status'] === 'completed'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">
                                        Completed
                                    </span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">
                                        <?= esc($order['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
</div>

<script>
    window.dashboardData = <?= json_encode($chart_data ?? []) ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>