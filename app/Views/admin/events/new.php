<h1 class="text-3xl font-bold text-black mb-6">
    <?= esc($title) ?>
</h1>

<?= $validation->listErrors('list') // Ini akan menampilkan daftar error jika validasi gagal ?>

<form action="/admin/events" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="md:col-span-2">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Event*</label>
                <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                       value="<?= old('name') ?>" required>
            </div>

            <div>
                <label for="venue" class="block mb-2 text-sm font-medium text-gray-900">Venue</label>
                <input type="text" id="venue" name="venue" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                       value="<?= old('venue') ?>">
            </div>

            <div>
                <label for="event_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal & Waktu Event*</label>
                <input type="datetime-local" id="event_date" name="event_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                       value="<?= old('event_date') ?>" required>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi Event</label>
                <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                          placeholder="Tulis deskripsi event..."><?= old('description') ?></textarea>
            </div>

            <div>
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="event" selected>Event (Default)</option>
                    <option value="concert">Konser</option>
                    <option value="festival">Festival</option>
                </select>
            </div>

            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="published" selected>Published (Tayang)</option>
                    <option value="draft">Draft (Sembunyi)</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-center">
                <input id="is_featured" name="is_featured" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_featured" class="ms-2 text-sm font-medium text-gray-900">
                    Tampilkan di Carousel Utama (Featured)
                </label>
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="poster_image">Upload Poster Event</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" 
                       id="poster_image" name="poster_image" type="file">
                <p class="mt-1 text-sm text-gray-500">PNG, JPG, atau WEBP (Maks. 2MB)</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="seatmap_image">Upload Seat Map</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" 
                       id="seatmap_image" name="seatmap_image" type="file">
                <p class="mt-1 text-sm text-gray-500">PNG, JPG, atau WEBP (Maks. 2MB)</p>
            </div>

        </div>

        <div class="mt-6">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Simpan Event
            </button>
            <a href="/admin/events" class="ml-2 text-sm font-medium text-gray-700 hover:underline">
                Batal
            </a>
        </div>
    </div>
</form>