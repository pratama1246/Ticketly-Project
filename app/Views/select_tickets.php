<main class="w-full pt-20 grow">
    <div class="max-w-8xl mx-auto p-4 mb-20">

        <div class="w-full rounded-xl overflow-hidden mb-8 mt-8 flex justify-center items-center">
            <img src="<?= base_url(esc($event['poster_image'])) ?>"
                alt="<?= esc($event['name']) ?>"
                class="w-auto max-w-full h-auto max-h-[700px] md:max-h-[800px] object-contain shadow-sm rounded-lg">
        </div>

        <div class="mb-6">
            <h1 class="text-3xl lg:text-5xl font-bold text-black">
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
                             echo $start->toLocalizedString('d MMMM yyyy');
                        } else {
                            if ($start->getMonth() == $end->getMonth() && $start->getYear() == $end->getYear()) {
                                echo $start->format('d') . ' - ' . $end->toLocalizedString('d MMMM yyyy');
                            } else {
                                echo $start->format('d MMM') . ' - ' . $end->toLocalizedString('d MMM yyyy');
                            }
                        }
                    } else {
                        echo $start->toLocalizedString('d MMMM yyyy');
                    }
                ?>
            </p>

            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
                <?php 
                    $startTime = $start->format('H:i');
                    if (!empty($event['event_end_date'])) {
                        $endTime = \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('H:i');
                        $isSameDay = ($start->format('Y-m-d') === \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('Y-m-d'));
                        if ($isSameDay) {
                            echo $startTime . ' - ' . $endTime . ' WIB';
                        } else {
                            echo 'Mulai pukul ' . $startTime . ' WIB';
                        }
                    } else {
                        echo $startTime . ' WIB';
                    }
                ?>
            </p>

            <p class="text-gray-500 text-s font-medium flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                <?= esc($event['venue']) ?>
            </p>

            <?php
            $category = strtolower($event['category']);

            if ($category === 'concert') {
                $label = 'Kembali ke Detail Konser';
            } elseif ($category === 'festival') {
                $label = 'Kembali ke Detail Festival';
            } else {
                $label = 'Kembali ke Detail Event';
            }
            ?>

            <a href="/event/<?= esc($event['slug']) ?>"
                class="mt-4 inline-flex bg-yellow-accent-normal hover:bg-yellow-accent-normal-hover text-gray-700 hover:text-gray-900 items-center gap-3 w-max px-3 py-2 rounded-base">
                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class="text-xs md:text-sm font-bold text-gray-900 m-0"><?= esc($label) ?></p>
            </a>

        </div>

        <form action="/checkout/start" method="post" id="ticketForm">
            <?= csrf_field() ?>
            <input type="hidden" name="eventId" value="<?= $event['id'] ?>">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <div class="lg:col-span-2">

                    <?php 
                        // Cek apakah Event Sudah Lewat?
                        $eventEnded = false;
                        $now = \CodeIgniter\I18n\Time::now();
                        $endDate = !empty($event['event_end_date']) ? $event['event_end_date'] : $event['event_date'];
                        
                        if ($now->isAfter($endDate)) {
                            $eventEnded = true;
                        }
                    ?>

                    <?php if ($eventEnded): ?><div class="shadow-sm border border-gray-200 bg-gray-50 rounded-xl text-center">
                            <div class="w-20 h-20 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Event Telah Berakhir</h3>
                            <p class="text-gray-500 mt-2 max-w-sm">
                                Sayang sekali, event ini sudah selesai. Nantikan event seru lainnya ya!
                            </p>
                            <a href="/" class="mt-6 px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Cari Event Lain
                            </a>
                        </div>

                    <?php elseif (empty($ticket_types)): ?>
                        <div class="flex flex-col items-center justify-center py-16 shadow-sm border border-gray-200 bg-gray-50 rounded-xl text-center">
                            <div class="w-20 h-20 bg-white text-gray-400 rounded-full flex items-center justify-center mb-4 shadow-sm">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Tiket Belum Tersedia</h3>
                            <p class="text-gray-500 mt-2 max-w-sm">
                                Penjualan tiket belum dibuka atau sedang dipersiapkan oleh panitia. Cek lagi nanti ya!
                            </p>
                        </div>

                    <?php else: ?>

                    <?php
                    $groupedTickets = [];
                    $passTickets = [];

                    // Pisahkan tiket berdasarkan tanggal
                    foreach ($ticket_types as $t) {
                        if (empty($t['ticket_date'])) {
                            $passTickets[] = $t;
                        } else {
                            $groupedTickets[$t['ticket_date']][] = $t;
                        }
                    }

                    // Urutkan tanggal (Day 1, Day 2...)
                    ksort($groupedTickets);

                    // Gabungkan: Tiket Terusan ditaruh paling depan
                    if (!empty($passTickets)) {
                        $groupedTickets = array_merge(['pass' => $passTickets], $groupedTickets);
                    }

                    // Cek apakah perlu menampilkan Tab? (Hanya jika grup > 1)
                    $showTabs = count($groupedTickets) > 1;
                    ?>

                    <?php if ($showTabs): ?>
                        <div class="mb-6 border-b border-gray-200">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="ticketTabs" data-tabs-toggle="#ticketTabContent" role="tablist">
                                <?php $isFirst = true;
                                foreach ($groupedTickets as $dateKey => $tickets): ?>
                                    <li class="me-2" role="presentation">
                                        <button class="inline-block p-4 border-b-2 rounded-t-lg transition-all hover:text-blue-600 hover:border-blue-300 aria-selected:text-blue-600 aria-selected:border-blue-600"
                                            id="tab-<?= $dateKey ?>"
                                            data-tabs-target="#content-<?= $dateKey ?>"
                                            type="button"
                                            role="tab"
                                            aria-controls="content-<?= $dateKey ?>"
                                            aria-selected="<?= $isFirst ? 'true' : 'false' ?>">
                                            <?php
                                            if ($dateKey === 'pass') {
                                                echo '<span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg> All Days Pass</span>';
                                            } else {
                                                // Format: 10 Jan (Day 1)
                                                echo date('d M (D)', strtotime($dateKey));
                                            }
                                            ?>
                                        </button>
                                    </li>
                                <?php $isFirst = false;
                                endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div id="ticketTabContent">
                        <?php $isFirstContent = true;
                        foreach ($groupedTickets as $dateKey => $tickets): ?>

                            <div class="<?= ($showTabs && !$isFirstContent) ? 'hidden' : '' ?> space-y-4"
                                id="content-<?= $dateKey ?>"
                                role="tabpanel"
                                aria-labelledby="tab-<?= $dateKey ?>">

                                <?php foreach ($tickets as $ticket): ?>

                                    <?php
                                    // Logika variabel aslimu
                                    $isSoldOut = ($ticket['quantity_total'] - $ticket['quantity_sold']) <= 0;
                                    $baseColor = !empty($ticket['ui_color']) ? $ticket['ui_color'] : '#3B82F6';
                                    $headerBg = $isSoldOut ? '#f3f4f6' : $baseColor;
                                    ?>

                                    <div class="bg-white rounded-lg overflow-hidden shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-lg group">

                                        <div class="p-4 text-center relative" style="background-color: <?= $headerBg ?>;">

                                            <h3 class="text-xl font-bold tracking-wide uppercase text-gray-900">
                                                <?= esc($ticket['name']) ?>
                                            </h3>

                                            <?php if (!empty($ticket['ticket_category'])): ?>
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-2xs font-bold px-2 py-0.5 rounded border bg-white text-gray-900 border-gray-300 shadow-sm">
                                                    <?= esc($ticket['ticket_category']) ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!$showTabs && !empty($ticket['ticket_date'])): ?>
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-2xs bg-white/80 px-2 py-0.5 rounded border border-gray-300">
                                                    <?= date('d M', strtotime($ticket['ticket_date'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="p-6">
                                            <div class="mb-4 text-sm text-gray-600">
                                                <?php if (!empty($ticket['description'])): ?>
                                                    <div class="prose prose-sm max-w-none">
                                                        <?= $ticket['description'] ?>
                                                    </div>
                                                <?php else: ?>
                                                    <ul class="list-disc list-inside ml-2 space-y-2 text-gray-500">
                                                        <li>Harga belum termasuk pajak</li>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>

                                            <?php
                                            // Ambil Waktu Event (Tetap pakai logic aslimu)
                                            $eventTime = \CodeIgniter\I18n\Time::parse($event['event_date']);
                                            ?>

                                            <div class="p-2 flex items-start gap-1">
                                                <svg class="w-4 h-4 text-gray-500 shrink-0 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div class="text-xs text-gray-500">
                                                    <span class="block font-medium">Batas Waktu Pemesanan:
                                                        <?= $eventTime->toLocalizedString('d MMMM yyyy, HH:mm') ?> WIB
                                                    </span>
                                                </div>
                                            </div>

                                            <hr class="my-4 border-gray-100">

                                            <div class="flex justify-between items-center">
                                                <p class="text-2xl font-bold <?= $isSoldOut ? 'text-gray-400' : 'text-gray-900' ?>">
                                                    Rp <?= number_format($ticket['price'], 0, ',', '.') ?>
                                                </p>

                                                <div class="w-32 text-right">
                                                    <?php if ($isSoldOut): ?>
                                                        <span class="inline-block w-full py-2 bg-gray-100 text-gray-400 font-bold text-sm rounded border border-gray-200 text-center cursor-not-allowed">HABIS</span>
                                                    <?php else: ?>
                                                        <label for="ticket_<?= $ticket['id'] ?>" class="block text-xs font-medium text-gray-500 mb-1 text-right">Jumlah</label>
                                                        <input type="number"
                                                            name="quantity[<?= $ticket['id'] ?>]"
                                                            id="ticket_<?= $ticket['id'] ?>"
                                                            class="ticket-input w-full border-gray-300 rounded-md shadow-sm text-center focus:ring-blue-500 focus:border-blue-500 font-bold text-gray-900"
                                                            value="0" min="0" max="4"
                                                            data-name="<?= esc($ticket['name']) ?>"
                                                            data-price="<?= $ticket['price'] ?>">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php $isFirstContent = false;
                        endforeach; ?>
                    </div>

                    <?php endif; ?>

                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-200 mt-6">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm uppercase tracking-wide">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Ketentuan Umum
                        </h3>
                        <ul class="list-disc list-inside text-xs text-gray-500 space-y-2 pl-1">
                            <li>Tiket digital akan dikirim ke email setelah pembayaran.</li>
                            <li>Pastikan data diri sesuai dengan kartu identitas.</li>
                            <li>Tiket tidak dapat ditukar atau dikembalikan (non-refundable).</li>
                        </ul>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-6 rounded-xl shadow-lg border border-gray-100 sticky top-24 z-20">
                        <h3 class="font-bold text-gray-900 text-lg mb-4 border-b border-gray-100 pb-3 flex items-center justify-between">
                            Ringkasan Pesanan
                        </h3>

                        <div id="cartItems" class="space-y-3 text-sm text-gray-600 mb-6 min-h-[60px]">
                            <div class="flex flex-col items-center justify-center h-full py-4 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <p class="text-xs">Belum ada tiket dipilih</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4 bg-gray-50 -mx-6 px-6 -mb-6 rounded-b-xl pb-6">
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-gray-600 font-medium text-sm">Total Estimasi</span>
                                <span class="text-2xl font-bold text-blue-600" id="totalPrice">Rp 0</span>
                            </div>

                            <button type="submit" id="btnCheckout" disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-4 rounded-lg cursor-not-allowed transition-all shadow-sm hover:shadow text-center flex justify-center items-center gap-2">
                                Pesan Sekarang
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
        </form>
</main>