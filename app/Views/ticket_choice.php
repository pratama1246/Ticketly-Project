<main class="w-full pt-24">
    
    <div class="max-w-7xl mx-auto p-4">
        
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">
            
            <h1 class="text-3xl font-bold text-black">
                <?= esc($event['name']) ?>
            </h1>
            
            <p class="text-lg text-gray-600 mt-2">
                <?= esc($event['event_date']) ?>
            </p>
            
            <span class="bg-green-500 text-white text-xs font-medium px-2.5 py-1 rounded mt-4 inline-block">
                Sedang Berlangsung
            </span>

        <button type="button" class="float-right text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 mt-4 focus:outline-none" onclick="window.location.href='<?= base_url('events/book/' . $event['id']) ?>'">Pesan Sekarang</button>

            <img src="<?= base_url('assets/' . $event['poster_image']) ?>" alt="<?= esc($event['name']) ?>" class="w-full rounded-lg my-6">

        
        </div>
    </div>
</main><main class="w-full pt-24">
    
    <div class="max-w-7xl mx-auto p-4">
        
        <div class="p-6 md:p-10 rounded-lg border border-solid-black">
            
            <h1 class="text-3xl font-bold text-black">
                <?= esc($event['name']) ?>
            </h1>
            
            <p class="text-lg text-gray-600 mt-2">
                <?= esc($event['event_date']) ?>
            </p>
            
            <span class="bg-green-500 text-white text-xs font-medium px-2.5 py-1 rounded mt-4 inline-block">
                Sedang Berlangsung
            </span>

        <button type="button" class="float-right text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 mt-4 focus:outline-none" onclick="window.location.href='<?= base_url('events/book/' . $event['id']) ?>'">Pesan Sekarang</button>

            <img src="<?= base_url('assets/' . $event['poster_image']) ?>" alt="<?= esc($event['name']) ?>" class="w-full rounded-lg my-6">

            <div class="bg-blue-900 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-white mb-4 text-center">
                    SEAT MAP <?= esc($event['name']) ?>
                </h2>
                <img src="<?= base_url('assets/' . $event['seatmap_image']) ?>" alt="Seat Map" class="w-full rounded-lg my-6">
            </div>


            <div class="mt-10">
                <div class="prose dark:prose-invert max-w-none text-black">
                    <?= $event['description'] ?>
                </div>
            </div>

        </div>
    </div>
</main>