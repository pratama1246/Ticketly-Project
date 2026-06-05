<?= $this->extend('layout/checkout') ?>
<?= $this->section('content') ?>

<main class="w-full pt-[136px] md:pt-[108px] mb-20 grow transition-all duration-300">
    <div class="max-w-6xl mx-auto p-4">

        <form action="/checkout/create_order" method="POST" id="reviewOrderForm">
            <?= csrf_field() ?>

            <!-- GRID UTAMA -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Sisi Kiri -->
                <div class="lg:col-span-7 space-y-6">
                
                    <!-- EVENT + TIKET -->
                    <div class="card-flat relative overflow-hidden">
                        <!-- Cutout circles on the left/right edge for ticket stub effect -->
                        <div class="absolute -left-3 top-[50%] w-6 h-6 rounded-full bg-slate-50 border border-slate-200 z-10 hidden sm:block"></div>
                        <div class="absolute -right-3 top-[50%] w-6 h-6 rounded-full bg-slate-50 border border-slate-200 z-10 hidden sm:block"></div>

                        <h2 class="text-2xl font-extrabold text-slate-900 mb-6 border-b-2 border-slate-100 pb-3">Konfirmasi Pesanan</h2>

                        <div class="w-full border border-slate-200 rounded-2xl overflow-hidden mb-6 flex justify-center items-center bg-slate-50">
                            <img src="<?= base_url(esc($event['poster_image'])) ?>"
                                alt="<?= esc($event['name']) ?>"
                                class="w-auto max-w-full h-auto max-h-[350px] object-contain">
                        </div>

                        <div class="mb-6 pb-6 border-b-2 border-slate-100">
                            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 leading-tight">
                                <?= esc($event['name']) ?>
                            </h1>

                            <div class="mt-4 space-y-2">
                                <p class="text-slate-600 text-sm font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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

                                <p class="text-slate-600 text-sm font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <?= esc($event['venue']) ?>
                                </p>
                            </div>

                            <div class="mt-6 pt-6 border-t-2 border-dashed border-slate-200">
                                <h4 class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-3">
                                    Tiket yang Dipesan
                                </h4>

                                <ul class="space-y-3">
                                    <?php foreach ($selected_tickets_details as $ticket): ?>
                                        <li class="flex justify-between items-center text-slate-700 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-flat-sm">
                                            <span class="font-bold text-slate-800 text-sm">
                                                <?= esc($ticket['name']) ?> (<?= esc($ticket['quantity']) ?>x)
                                            </span>
                                            <span class="font-extrabold text-slate-900">
                                                Rp <?= number_format($ticket['subtotal'], 0, ',', '.') ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- DATA DIRI -->
                        <div>
                            <h4 class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-4">
                                Data Diri Pemesan
                            </h4>

                            <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl shadow-flat-sm">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm text-slate-700">
                                    <div>
                                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</dt>
                                        <dd class="font-extrabold text-slate-800 text-base"><?= esc($personal['first_name']) ?> <?= esc($personal['last_name']) ?></dd>
                                    </div>

                                    <div>
                                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email</dt>
                                        <dd class="font-extrabold text-slate-800 text-base break-all"><?= esc($personal['email']) ?></dd>
                                    </div>

                                    <div>
                                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. Telepon</dt>
                                        <dd class="font-extrabold text-slate-800 text-base"><?= esc($personal['phone_number']) ?></dd>
                                    </div>

                                    <div>
                                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. Identitas</dt>
                                        <dd class="font-extrabold text-slate-800 text-base"><?= esc($personal['identity_number']) ?></dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan -->
                <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">

                    <!-- RINCIAN BIAYA -->
                    <div class="card-flat">
                        <h3 class="text-lg font-extrabold text-slate-900 mb-4 border-b-2 border-slate-100 pb-2">
                            Rincian Biaya
                        </h3>

                        <!-- Item tiket -->
                        <div class="space-y-3 mb-4 border-b-2 border-slate-100 pb-4">
                            <?php foreach ($selected_tickets_details as $ticket): ?>
                                <div class="flex justify-between items-start text-sm text-slate-700">
                                    <div>
                                        <span class="font-bold text-slate-800 block">
                                            <?= esc($ticket['quantity']) ?>x <?= esc($ticket['name']) ?>
                                        </span>

                                        <?php if (!empty($ticket['ticket_date'])): ?>
                                            <span class="inline-block mt-1 text-xs text-blue-primary-normal bg-blue-primary-light border border-slate-200 px-2 py-0.5 rounded-full font-bold">
                                                Berlaku: <?= date('d M Y', strtotime($ticket['ticket_date'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-block mt-1 text-xs text-green-700 bg-green-50 border border-slate-200 px-2 py-0.5 rounded-full font-bold">
                                                All Days Pass
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <span class="font-extrabold text-slate-900 whitespace-nowrap">
                                        Rp <?= number_format($ticket['subtotal'], 0, ',', '.') ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Breakdown -->
                        <div class="space-y-2.5 text-sm text-slate-600 mb-5">
                            <div class="flex justify-between">
                                <span class="font-medium">Subtotal Tiket</span>
                                <span class="font-bold text-slate-800">Rp <?= number_format($sub_total, 0, ',', '.') ?></span>
                            </div>

                            <div class="flex justify-between items-center text-palette-orange-dark">
                                <span class="flex items-center gap-1 font-medium">
                                    PPN (11%)
                                    <svg data-popover-target="taxppn-info" data-popover-placement="bottom" class="w-4 h-4 cursor-pointer ms-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>    
                                    <div data-popover id="taxppn-info" role="tooltip" class="absolute z-10 p-3 invisible inline-block text-xs text-slate-600 transition-opacity duration-200 bg-white border border-slate-200 rounded-xl shadow-flat-sm opacity-0 w-64">
                                        <h3 class="font-bold text-slate-900 mb-1">Tentang PPN</h3>
                                        <p>PPN ditetapkan sesuai dengan peraturan perpajakan yang berlaku di Indonesia.</p>
                                        <div data-popper-arrow></div>
                                    </div>
                                </span>
                                <span class="font-bold">+ Rp <?= number_format($tax_amount, 0, ',', '.') ?></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-medium">Biaya Platform</span>
                                <span class="font-bold text-slate-800">+ Rp <?= number_format($platform_fee, 0, ',', '.') ?></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-medium">Biaya Admin</span>
                                <span class="font-bold text-slate-800">+ Rp <?= number_format($admin_fee, 0, ',', '.') ?></span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center pt-4 border-t-2 border-dashed border-slate-300">
                            <span class="text-base font-extrabold text-slate-900">Total Bayar</span>
                            <span class="text-2xl font-black text-blue-primary-normal">
                                Rp <?= number_format($grand_total, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                    <!-- METODE BAYAR -->
                    <div class="card-flat bg-blue-soft-light">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                            Metode Pembayaran
                        </h4>
                        <p class="text-lg text-blue-primary-normal font-black">
                            <?= esc($payment_method_name) ?>
                        </p>
                    </div>

                    <!-- ACTION -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-2">
                        <a href="/checkout/payment_method" class="btn-flat-gray justify-center w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </a>

                        <div class="flex flex-col-reverse sm:flex-row gap-3 w-full sm:w-auto">
                            <button type="button" onclick="showCancelModal()" class="btn-flat-gray hover:text-red-600 justify-center w-full sm:w-auto">
                                Batal
                            </button>

                            <button type="submit" class="btn-flat-blue justify-center w-full sm:w-auto">
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?= $this->endSection() ?>