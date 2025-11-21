<h1 class="text-3xl font-bold text-black mb-6">
    <?= esc($title) ?>
</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500 uppercase">Total Pendapatan</h3>
        <p class="text-2xl font-bold text-black mt-2">
            Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500 uppercase">Tiket Terjual</h3>
        <p class="text-2xl font-bold text-black mt-2">
            <?= number_format($totalTickets) ?>
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500 uppercase">Total Pesanan</h3>
        <p class="text-2xl font-bold text-black mt-2">
            <?= number_format($totalOrders) ?>
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-sm font-medium text-gray-500 uppercase">Total Event</h3>
        <p class="text-2xl font-bold text-black mt-2">
            <?= number_format($totalEvents) ?>
        </p>
    </div>
</div>
<h2 class="text-2xl font-bold text-black mb-4">Pesanan Terbaru</h2>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 w-58">
                    Order ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama Pembeli
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                <th scope="col" class="px-6 py-3">
                    Total
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentOrders)): ?>
                <tr class="bg-white border-b">
                    <td colspan="5" class="px-6 py-4 text-center">
                        Belum ada pesanan.
                    </td>
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