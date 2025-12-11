<div class="p-4">
    <h1 class="text-3xl font-bold text-black mb-6"><?= esc($title) ?></h1>

    <!-- Form Edit Tiket -->
    <form action="/admin/events/<?= $event['id'] ?>/tickets/<?= $ticket['id'] ?>/update" method="post">
        <?= csrf_field() ?>
        
        <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl border border-gray-100">
            <div class="grid grid-cols-1 gap-6">
                
                <!-- Nama Tiket -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Nama Tiket</label>
                    <input type="text" name="name" 
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" 
                        value="<?= old('name', $ticket['name']) ?>" required>
                </div>

                <!-- Tanggal Tiket -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Berlaku Untuk Tanggal
                    </label>

                    <?php 
                        $start = new \DateTime($event['event_date']);
                        $end = !empty($event['event_end_date']) ? new \DateTime($event['event_end_date']) : null;
                        $isSingleDay = empty($end) || ($start->format('Y-m-d') === $end->format('Y-m-d'));
                    ?>

                    <?php if ($isSingleDay): ?>
                        
                        <input type="text" 
                            class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" 
                            value="<?= $start->format('d F Y') ?> (Single Day)" 
                            disabled>
                        
                        <p class="mt-1 text-xs text-gray-500">
                            Otomatis berlaku untuk tanggal event.
                        </p>

                        <input type="hidden" name="ticket_date" value="">

                    <?php else: ?>

                        <select id="ticket_date" name="ticket_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            
                            <option value="" <?= (old('ticket_date', $ticket['ticket_date'] ?? '') == '') ? 'selected' : '' ?>>
                                All Days Pass
                            </option>

                            <?php 
                                $curr = clone $start;
                                $dayCount = 1;

                                $endLimit = (clone $end)->modify('+1 day')->setTime(0,0,0); 

                                while($curr < $endLimit):
                                    $valDate = $curr->format('Y-m-d');
                                    $label = $curr->format('d F Y') . ' (Day ' . $dayCount . ')';
                                    
                                    $selected = (old('ticket_date', $ticket['ticket_date'] ?? '') == $valDate) ? 'selected' : '';
                                    echo "<option value='$valDate' $selected>$label</option>";
                                    
                                    $curr->modify('+1 day');
                                    $dayCount++;
                                endwhile;
                            ?>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Pilih tanggal spesifik atau 'Tiket Terusan'.</p>

                    <?php endif; ?>
                </div>

                <!-- Jenis Posisi Tiket -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Jenis Posisi</label>
                    <div class="grid grid-cols-2 gap-4">
                        <?php $currentCategory = old('ticket_category', $ticket['ticket_category'] ?? 'Standing'); ?>

                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500 has-checked:border-blue-500 has-checked:bg-blue-50 transition-all">
                            <input type="radio" name="ticket_category" value="Standing" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" <?= $currentCategory === 'Standing' ? 'checked' : '' ?>>
                            <div class="ms-3">
                                <span class="block text-sm font-medium text-gray-900">Standing</span>
                                <span class="block text-xs text-gray-500">Berdiri (Festival)</span>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500 has-checked:border-blue-500 has-checked:bg-blue-50 transition-all">
                            <input type="radio" name="ticket_category" value="Seating" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" <?= $currentCategory === 'Seating' ? 'checked' : '' ?>>
                            <div class="ms-3">
                                <span class="block text-sm font-medium text-gray-900">Seating</span>
                                <span class="block text-xs text-gray-500">Duduk (Bernomor/Free)</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Harga dan Kuota Tiket -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Harga Tiket (Rp)</label>
                        <input type="number" name="price" 
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" 
                            value="<?= old('price', $ticket['price']) ?>" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Total Kuota</label>
                        <input type="number" name="quantity_total" id="quantity_total"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" 
                            value="<?= old('quantity_total', $ticket['quantity_total']) ?>" required>
                    </div>
                </div>

                <div id="seat-generator-box" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg animate-fade-in">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-blue-800 uppercase tracking-wide flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Generator Kursi Otomatis
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <input type="text" id="seat_row_start" name="seat_row_start" placeholder="Row Awal (A)" maxlength="2" 
                                oninput="autoCalculateStock()"
                                value="<?= old('seat_row_start', $ticket['seat_row_start'] ?? '') ?>" 
                                class="uppercase text-center bg-white border border-blue-300 text-blue-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        </div>

                        <div>
                            <input type="text" id="seat_row_end" name="seat_row_end" placeholder="Row Akhir (C)" maxlength="2" 
                                oninput="autoCalculateStock()"
                                value="<?= old('seat_row_end', $ticket['seat_row_end'] ?? '') ?>"
                                class="uppercase text-center bg-white border border-blue-300 text-blue-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        </div>

                        <div>
                            <input type="number" id="seats_per_row" name="seats_per_row" placeholder="Kursi/Row (20)" 
                                oninput="autoCalculateStock()"
                                value="<?= old('seats_per_row', $ticket['seats_per_row'] ?? '') ?>"
                                class="text-center bg-white border border-blue-300 text-blue-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                        </div>
                    </div>
                    <p class="mt-2 text-2xs text-blue-600">
                        *Data ini diambil dari settingan kursi saat ini. Ubah jika ingin generate ulang struktur kursi.
                    </p>
                </div>

                <!-- Warna Tiket -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Warna Tampilan</label>
                    <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <input type="color" name="ui_color" 
                            class="p-0.5 h-12 w-16 block bg-white border border-gray-300 rounded cursor-pointer" 
                            value="<?= old('ui_color', $ticket['ui_color']) ?>">
                        <span class="text-sm text-gray-500">Ganti warna jika diperlukan.</span>
                    </div>
                </div>

                <!-- Keterangan Tiket -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Keterangan</label>
                    <textarea name="description" id="description" rows="3" 
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"><?= old('description', $ticket['description']) ?></textarea>
                </div>

            </div>
                
            <!-- Tombol Update dan Batal -->
            <div class="mt-8 flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Update Tiket
                </button>
                <a href="/admin/events/<?= $event['id'] ?>/tickets" class="text-gray-700 bg-white border border-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-gray-100">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>