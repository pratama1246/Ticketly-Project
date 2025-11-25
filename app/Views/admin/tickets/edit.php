<h1 class="text-3xl font-bold text-black mb-6"><?= esc($title) ?></h1>

<?php if (session()->has('errors')): ?>
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
        <ul class="list-disc list-inside">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

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

            <!-- Jenis Posisi Tiket -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Jenis Posisi</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer has-checked:border-blue-500 has-checked:bg-blue-50">
                        <input type="radio" name="ticket_category" value="Standing" class="w-4 h-4 text-blue-600" 
                               <?= (old('ticket_category', $ticket['ticket_category']) == 'Standing') ? 'checked' : '' ?>>
                        <div class="ms-3">
                            <span class="block text-sm font-medium text-gray-900">Standing</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer has-checked:border-blue-500 has-checked:bg-blue-50">
                        <input type="radio" name="ticket_category" value="Seating" class="w-4 h-4 text-blue-600" 
                               <?= (old('ticket_category', $ticket['ticket_category']) == 'Seating') ? 'checked' : '' ?>>
                        <div class="ms-3">
                            <span class="block text-sm font-medium text-gray-900">Seating</span>
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
                    <input type="number" name="quantity_total" 
                           class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" 
                           value="<?= old('quantity_total', $ticket['quantity_total']) ?>" required>
                </div>
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