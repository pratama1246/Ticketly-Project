<main class="w-full pt-60 md:pt-42 mb-20 grow transition-all duration-300">
    <div class="max-w-7xl mx-auto p-4">

        <form action="/checkout/create_order" method="POST">
            <?= csrf_field() ?>

            <!-- GRID UTAMA -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Sisi Kiri -->
                <div class="lg:col-span-7 space-y-6">
                
                    <!-- EVENT + TIKET -->
                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">

                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Konfirmasi Pesanan</h2>

                        <div class="w-full rounded-xl overflow-hidden mb-8 mt-8 flex justify-center items-center">
                            <img src="<?= base_url(esc($event['poster_image'])) ?>"
                                alt="<?= esc($event['name']) ?>"
                                class="w-auto max-w-full h-auto object-contain shadow-sm rounded-lg">
                        </div>

                        <div  class="mb-6 pb-6 border-b border-gray-200">
                            <h1 class="text-xl lg:text-3xl font-bold text-black">
                                <?= esc($event['name']) ?>
                            </h1>

                            <p class="text-gray-500 text-s font-medium flex items-center gap-1 mt-4">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <?php
                                $start = \CodeIgniter\I18n\Time::parse($event['event_date']);

                                if (!empty($event['event_end_date'])) {
                                    $end = \CodeIgniter\I18n\Time::parse($event['event_end_date']);

                                    if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
                                        echo $start->toLocalizedString('d MMMM yyyy') . ' • ' . $start->format('H:i') . ' - ' . $end->format('H:i') . ' WIB';
                                    } else {
                                        if ($start->getMonth() == $end->getMonth()) {
                                            echo $start->format('d') . ' - ' . $end->toLocalizedString('d MMMM yyyy');
                                        } else {
                                            echo $start->toLocalizedString('d MMM') . ' - ' . $end->toLocalizedString('d MMM yyyy');
                                        }
                                    }
                                } else {
                                    echo $start->toLocalizedString('d MMMM yyyy') . ' • ' . $start->format('H:i') . ' WIB';
                                }
                                ?>
                            </p>

                            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <?php 
                                    $s = \CodeIgniter\I18n\Time::parse($event['event_date']);
                                    if (!empty($event['event_end_date'])) {
                                        $e = \CodeIgniter\I18n\Time::parse($event['event_end_date']);
                                        if ($s->format('Y-m-d') === $e->format('Y-m-d')) {
                                            echo $s->toLocalizedString('d F Y') . ' • ' . $s->format('H:i') . ' - ' . $e->format('H:i') . ' WIB';
                                        } else {
                                            echo $s->toLocalizedString('d MMMM') . ' - ' . $e->toLocalizedString('d MMMM Y');
                                        }
                                    } else {
                                        echo $s->toLocalizedString('d F Y') . ' • ' . $s->format('H:i') . ' WIB';
                                    }
                                ?>
                            </p>

                            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <?= esc($event['venue']) ?>
                            </p>

                            <div class="py-4">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3">
                                    Tiket yang Dipesan
                                </h4>

                                <ul class="space-y-2">
                                    <?php foreach ($selected_tickets_details as $ticket): ?>
                                        <li class="flex justify-between items-center text-gray-700">
                                            <span>
                                                <?= esc($ticket['name']) ?> (<?= esc($ticket['quantity']) ?>x)
                                            </span>
                                            <span class="font-medium">
                                                Rp <?= number_format($ticket['subtotal'], 0, ',', '.') ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- DATA DIRI -->
                        <div>

                            <h4 class="text-lg font-semibold text-gray-800 mb-4">
                                Data Diri
                            </h4>

                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-gray-700">
                                <dt class="font-medium">Nama Lengkap</dt>
                                <dd><?= esc($personal['first_name']) ?> <?= esc($personal['last_name']) ?></dd>

                                <dt class="font-medium">Email</dt>
                                <dd><?= esc($personal['email']) ?></dd>

                                <dt class="font-medium">No. Telepon</dt>
                                <dd><?= esc($personal['phone_number']) ?></dd>

                                <dt class="font-medium">No. Identitas</dt>
                                <dd><?= esc($personal['identity_number']) ?></dd>
                            </dl>

                        </div>
                    </div>
                </div>


                    <!-- Sisi Kanan -->
                    <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">

                        <!-- RINCIAN BIAYA -->
                        <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">

                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                Rincian Biaya
                            </h3>

                            <!-- Item tiket -->
                            <div class="space-y-3 mb-4 border-b border-gray-100 pb-4">
                                <?php foreach ($selected_tickets_details as $ticket): ?>
                                    <div class="flex justify-between items-start text-sm text-gray-700">

                                        <div>
                                            <span class="font-bold block">
                                                <?= esc($ticket['quantity']) ?>x <?= esc($ticket['name']) ?>
                                            </span>

                                            <?php if (!empty($ticket['ticket_date'])): ?>
                                                <span
                                                    class="text-xs text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">
                                                    Berlaku:
                                                    <?= date('d M Y', strtotime($ticket['ticket_date'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <span
                                                    class="text-xs text-green-600 bg-green-50 px-1.5 py-0.5 rounded border border-green-100">
                                                    All Days Pass
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <span class="font-medium whitespace-nowrap">
                                            Rp <?= number_format($ticket['subtotal'], 0, ',', '.') ?>
                                        </span>

                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Breakdown -->
                            <div class="space-y-2 text-sm text-gray-500 mb-4">

                                <div class="flex justify-between">
                                    <span>Subtotal Tiket</span>
                                    <span>Rp <?= number_format($sub_total, 0, ',', '.') ?></span>
                                </div>

                                <div class="flex justify-between items-center text-orange-600">
                                    <span class="flex items-center gap-1">
                                        PPN (11%)
                                        <svg data-popover-target="taxppn-info" data-popover-placement="bottom" class="w-3 h-3 cursor-pointer ms-1" title="Pajak PBJT"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>    
                                        <div data-popover id="taxppn-info" role="tooltip" class="absolute z-10 p-3 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72">
                                                <h3 class="font-semibold text-gray-900 mb-2">Tentang PPN</h3>
                                                <p>PPN ditetapkan sesuai dengan peraturan perpajakan yang berlaku di Indonesia.</p>
                                                <div data-popper-arrow></div>
                                            </div>
                                    </span>
                                    <span>+ Rp <?= number_format($tax_amount, 0, ',', '.') ?></span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Biaya Platform</span>
                                    <span>+ Rp <?= number_format($platform_fee, 0, ',', '.') ?></span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Biaya Admin</span>
                                    <span>+ Rp <?= number_format($admin_fee, 0, ',', '.') ?></span>
                                </div>

                            </div>

                            <!-- Total -->
                            <div
                                class="flex justify-between items-center pt-4 border-t-2 border-dashed border-gray-200">
                                <span class="text-base font-bold text-gray-900">Total Bayar</span>
                                <span class="text-xl font-extrabold text-blue-600">
                                    Rp <?= number_format($grand_total, 0, ',', '.') ?>
                                </span>
                            </div>

                        </div>


                        <!-- METODE BAYAR -->
                        <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                Metode Pembayaran
                            </h4>

                            <p class="text-lg text-blue-600 font-bold">
                                <?= esc($payment_method_name) ?>
                            </p>
                        </div>


                        <!-- ACTION -->
                        <div class="flex justify-between items-center">

                            <a href="/checkout/payment_method"
                                class="text-gray-600 bg-gray-100 hover:bg-gray-200
                                  focus:ring-4 focus:ring-gray-100
                                  font-medium rounded-lg text-sm px-5 py-2.5
                                  focus:outline-none flex items-center gap-2">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>

                                Kembali
                            </a>

                            <div class="flex gap-2">
                                <button type="button" onclick="showCancelModal()"
                                    class="text-white bg-danger hover:bg-danger-strong
                                       focus:ring-4 focus:ring-danger-medium
                                       font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                                    Batal
                                </button>

                                <button type="submit"
                                    class="text-white bg-brand hover:bg-brand-strong
                                       focus:ring-4 focus:ring-brand-medium
                                       font-medium rounded-lg text-sm px-6 py-2.5 focus:outline-none">
                                    Bayar Sekarang
                                </button>
                            </div>

                        </div>

                    </div>

                </div>

        </form>
    </div>
</main>