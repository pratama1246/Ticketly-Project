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

       <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

            <div class="xl:col-span-3 bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-6">
                <div class="flex justify-between items-start w-full mb-4">
                    <div class="flex-col items-center">
                        <div class="flex items-center mb-1">
                            <h5 class="text-xl font-bold text-gray-900 me-1">Statistik Pendapatan</h5>
                            <svg data-popover-target="revenue-info" data-popover-placement="bottom" class="w-4 h-4 text-gray-400 hover:text-gray-900 cursor-pointer ms-1" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div data-popover id="revenue-info" role="tooltip" class="absolute z-10 p-3 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72">
                                <h3 class="font-semibold text-gray-900 mb-2">Tentang Grafik</h3>
                                <p>Menampilkan total pemasukan kotor dari transaksi yang berstatus 'Completed' dalam 7 hari terakhir.</p>
                                <div data-popper-arrow></div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">Omzet Harian</p>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input name="start" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="7 Hari Terakhir" disabled>
                        </div>
                    </div>
                </div>
                
                <div id="revenue-chart" class="w-full"></div>
                
                <div class="grid grid-cols-1 items-center border-t border-gray-200 justify-between mt-4">
                    <div class="flex justify-between items-center pt-4">
                        <button class="text-sm font-medium text-gray-500 hover:text-gray-900 text-center inline-flex items-center" type="button">
                            Last 7 days
                            <svg class="w-4 h-4 ms-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                        </button>
                        <a href="#" class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 hover:bg-gray-100 px-3 py-2">
                            Laporan Lengkap
                            <svg class="w-4 h-4 ms-1.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-6">
                <div class="flex justify-between mb-3">
                    <div class="flex justify-center items-center">
                        <h5 class="text-xl font-bold text-gray-900 me-1">Event Terlaris</h5>
                        <svg data-popover-target="event-info" data-popover-placement="bottom" class="w-4 h-4 text-gray-400 hover:text-gray-900 cursor-pointer ms-1" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div data-popover id="event-info" role="tooltip" class="absolute z-10 p-3 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72">
                            <p>Distribusi penjualan tiket berdasarkan kategori event.</p>
                            <div data-popper-arrow></div>
                        </div>
                    </div>
                </div>

                <div class="py-2" id="category-chart"></div>

                <div class="grid grid-cols-1 items-center border-t border-gray-200 justify-between mt-4">
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-sm font-medium text-gray-500">Top 5 Events</span>
                        <a href="/admin/events" class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 hover:bg-gray-100 px-3 py-2">
                            Kelola Event
                            <svg class="w-4 h-4 ms-1.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-6">
                <div class="flex justify-between pb-4 mb-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center me-3">
                            <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900">Pembayaran</h5>
                            <p class="text-sm text-gray-500">Metode Favorit</p>
                        </div>
                    </div>
                    <div>
                        <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded border border-green-400">
                            Completed
                        </span>
                    </div>
                </div>

                <div id="payment-chart" class="py-2"></div>

                <div class="grid grid-cols-1 items-center border-t border-gray-200 justify-between mt-4">
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-sm font-medium text-gray-500">Semua Transaksi</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h5 class="text-xl font-bold text-gray-900">Status Order</h5>
                        <p class="text-sm text-gray-500">Tingkat Keberhasilan</p>
                    </div>
                    <div class="flex items-center px-2.5 py-0.5 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                        Live Data
                    </div>
                </div>
                
                <div id="status-chart"></div>

                <div class="grid grid-cols-1 items-center border-t border-gray-200 justify-between mt-4">
                    <div class="flex justify-between items-center pt-4">
                        <button class="text-sm font-medium text-gray-500 hover:text-gray-900 text-center inline-flex items-center" type="button">
                            All Time
                        </button>
                        <a href="/admin/orders" class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 hover:bg-gray-100 px-3 py-2">
                            Cek Order
                            <svg class="w-4 h-4 ms-1.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
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