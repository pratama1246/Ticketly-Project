<h1 class="text-3xl font-bold text-black mb-6"><?= esc($title) ?></h1>


<!-- Form Tambah Tiket Baru -->
<form action="/admin/events/<?= $event['id'] ?>/tickets" method="post">
    <?= csrf_field() ?>
    
    <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl border border-gray-100">
        <div class="grid grid-cols-1 gap-6">
            
            <!-- Nama Tiket -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Tiket / Kategori</label>
                <input type="text" id="name" name="name" 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                       placeholder="Contoh: VIP Standing A, CAT 1, Festival" 
                       value="<?= old('name') ?>" required>
            </div>

            <!-- Tanggal Tiket -->
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Berlaku Untuk Tanggal
                </label>

                <?php 
                    $start = new \DateTime($event['event_date']);
                    $end = !empty($event['event_end_date']) ? new \DateTime($event['event_end_date']) : null;

                    // Deteksi: Apakah ini Event 1 Hari?
                    // (End date kosong ATAU Start & End di hari yang sama)
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
                            // Loop dari Start sampai End
                            $curr = clone $start;
                            $dayCount = 1;
                            
                            // Tambah +1 detik di end biar loop mencakup hari terakhir juga jika jamnya sama/lebih
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
                    <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500 has-checked:border-blue-500 has-checked:bg-blue-50 transition-all">
                        <input type="radio" name="ticket_category" value="Standing" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" <?= old('ticket_category') == 'Standing' ? 'checked' : 'checked' ?>>
                        <div class="ms-3">
                            <span class="block text-sm font-medium text-gray-900">Standing</span>
                            <span class="block text-xs text-gray-500">Berdiri (Festival)</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500 has-checked:border-blue-500 has-checked:bg-blue-50 transition-all">
                        <input type="radio" name="ticket_category" value="Seating" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" <?= old('ticket_category') == 'Seating' ? 'checked' : '' ?>>
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
                    <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Harga Tiket (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <span class="text-gray-500 text-sm">Rp</span>
                        </div>
                        <input type="number" id="price" name="price" 
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" 
                               placeholder="0" value="<?= old('price') ?>" required>
                    </div>
                </div>
                <div>
                    <label for="quantity_total" class="block mb-2 text-sm font-medium text-gray-900">Total Kuota (Stok)</label>
                    <input type="number" id="quantity_total" name="quantity_total" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                           placeholder="Contoh: 100" value="<?= old('quantity_total') ?>" required>
                </div>
            </div>

            <!-- Warna Tiket -->
            <div>
                <label for="ui_color" class="block mb-2 text-sm font-medium text-gray-900">Warna Tampilan Kartu</label>
                <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="relative">
                        <input type="color" id="ui_color" name="ui_color" 
                               class="p-0.5 h-12 w-16 block bg-white border border-gray-300 rounded cursor-pointer shadow-sm" 
                               value="<?= old('ui_color', '#3B82F6') ?>" title="Klik untuk pilih warna">
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-700">Pilih warna identitas untuk kategori tiket ini.</p>
                        <p class="text-xs text-gray-500">Warna ini akan digunakan sebagai background header dan aksen harga di halaman pembeli.</p>
                    </div>
                </div>
            </div>

            <!-- Keterangan Tiket -->
            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Keterangan / Benefit (Opsional)</label>
                <textarea id="description" name="description" rows="3" 
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                          placeholder="Contoh: Termasuk Soundcheck, Antrian Prioritas, Free Merchandise..."><?= old('description') ?></textarea>
            </div>
        </div>

        <!-- Tombol Simpan dan Batal -->
        <div class="mt-8 flex items-center gap-3 pt-6 border-t border-gray-100">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md transition-all hover:shadow-lg">
                Simpan Tiket
            </button>
            <a href="/admin/events/<?= $event['id'] ?>/tickets" class="text-gray-700 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-gray-100 hover:text-blue-700">
                Batal
            </a>
        </div>
    </div>
</form>