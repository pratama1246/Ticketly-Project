<!-- HALAMAN EDIT EVENT -->

<!-- Judul Halaman -->
<h1 class="text-3xl font-bold text-black mb-6">
    <?= esc($title) ?>
</h1>

<?= $validation->listErrors('list') ?>

<!-- FORM EDIT EVENT -->
<form action="/admin/events/<?= $event['id'] ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT"> 
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Nama Event -->
            <div class="md:col-span-2">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Event*</label>
                <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                       value="<?= old('name', $event['name']) ?>" required>
            </div>

            <!-- Venue -->
            <div>
                <label for="venue" class="block mb-2 text-sm font-medium text-gray-900">Venue</label>
                <input type="text" id="venue" name="venue" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                       value="<?= old('venue', $event['venue']) ?>">
            </div>

            <!-- Tanggal & Waktu Event -->
            <div>
                <label for="event_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal & Waktu Event*</label>
                <input type="datetime-local" id="event_date" name="event_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                       value="<?= old('event_date', $event['event_date']) ?>" required>
            </div>
            
            <!-- Deskripsi Event -->
            <div class="md:col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi Event</label>
                <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                          placeholder="Tulis deskripsi event..."><?= old('description', $event['description']) ?></textarea>
            </div>

            <!-- Kategori Event -->
            <div>
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="event" <?= $event['category'] == 'event' ? 'selected' : '' ?>>Event</option>
                    <option value="concert" <?= $event['category'] == 'concert' ? 'selected' : '' ?>>Konser</option>
                    <option value="festival" <?= $event['category'] == 'festival' ? 'selected' : '' ?>>Festival</option>
                </select>
            </div>

            <!-- Status Event -->
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="published" <?= $event['status'] == 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft" <?= $event['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>

            <!-- Featured Checkbox -->
            <div class="md:col-span-2 flex items-center">
                <input id="is_featured" name="is_featured" type="checkbox" value="1" 
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                       <?= $event['is_featured'] == 1 ? 'checked' : '' ?>>
                <label for="is_featured" class="ms-2 text-sm font-medium text-gray-900">
                    Tampilkan di Carousel Utama (Featured)
                </label>
            </div>

            <div class="mb-4">
                <label for="sort_order" class="block mb-2 text-sm font-medium text-gray-900">Nomor Urut (Prioritas)</label>
                <input type="number" id="sort_order" name="sort_order" 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" 
                       value="<?= old('sort_order', $event['sort_order']) ?>" 
                       placeholder="0">
                <p class="mt-1 text-xs text-gray-500">Semakin kecil angkanya (misal 1), semakin di depan posisinya. 0 = Default.</p>
            </div>

            <!-- Upload Poster Image -->
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="poster_image">Upload Poster Event</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" 
                       id="poster_image" name="poster_image" type="file">
                <p class="mt-1 text-sm text-gray-500">PNG, JPG, atau WEBP (Maks. 2MB). Biarkan kosong jika tidak ingin mengganti.</p>
                
                <?php if (!empty($event['poster_image'])): ?>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-700">Gambar saat ini:</p>
                        <img src="/<?= esc($event['poster_image']) ?>" alt="Poster Saat Ini" class="w-32 h-auto mt-2 rounded">
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Upload Seatmap Image -->
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="seatmap_image">Upload Seat Map</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" 
                       id="seatmap_image" name="seatmap_image" type="file">
                <p class="mt-1 text-sm text-gray-500">PNG, JPG, atau WEBP (Maks. 2MB). Biarkan kosong jika tidak ingin mengganti.</p>
                
                <?php if (!empty($event['seatmap_image'])): ?>
                     <div class="mt-2">
                        <p class="text-sm font-medium text-gray-700">Seatmap saat ini:</p>
                        <img src="/<?= esc($event['seatmap_image']) ?>" alt="Seatmap Saat Ini" class="w-32 h-auto mt-2 rounded">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Tombol Submit dan Batal -->
        <div class="mt-6">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Update Event
            </button>
            <a href="/admin/events" class="ml-2 text-sm font-medium text-gray-700 hover:underline">
                Batal
            </a>
        </div>
    </div>
</form>