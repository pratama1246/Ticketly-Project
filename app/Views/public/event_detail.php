<?= $this->extend('layout/app') ?>
<?= $this->section('content') ?>

<main class="w-full pt-24 pb-20 md:pb-0 mb-20 grow">
    <div class="max-w-7xl mx-auto p-4">

        <a href="/<?= esc(strtolower($event['category'])) ?>s" class="bg-yellow-accent-normal hover:bg-yellow-accent-normal-hover text-gray-700 hover:text-gray-900 flex items-center gap-3 w-max px-3 py-2 mt-4 rounded-base">
            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <h1 class="text-xs md:text-sm font-bold text-gray-900 m-0">Kembali</h1>
        </a>

        <!-- Mobile Title & Detail Card (Only visible on mobile) -->
        <div class="card-flat mt-6 lg:hidden">
            <!-- Category & Status Tag -->
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <span class="badge-flat uppercase font-bold text-xs py-1 px-3 bg-blue-50">
                    <?= esc($event['category']) ?>
                </span>
                <span class="<?= $status['color'] ?> text-xs font-bold px-3 py-1 rounded-full border border-current shadow-xs flex items-center gap-1.5">
                    <?= $status['icon'] ?>
                    <?= $status['text'] ?>
                </span>
            </div>

            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight">
                <?= esc($event['name']) ?>
            </h1>

            <!-- Event Metadata -->
            <div class="border-t border-dashed border-slate-150 mt-4 pt-4 space-y-3.5 text-slate-600 font-medium text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Tanggal</p>
                        <p class="text-sm text-slate-800 font-semibold">
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
                                            echo $start->toLocalizedString('d MMM') . ' - ' . $end->toLocalizedString('d MMM yyyy');
                                        }
                                    }
                                } else {
                                    echo $start->toLocalizedString('d MMMM yyyy');
                                }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Waktu</p>
                        <p class="text-sm text-slate-800 font-semibold">
                            <?php 
                                $startTime = $start->format('H:i');
                                if (!empty($event['event_end_date'])) {
                                    $endTime = \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('H:i');
                                    $isSameDay = ($start->format('Y-m-d') === \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('Y-m-d'));
                                    if ($isSameDay) {
                                        echo $startTime . ' - ' . $endTime . ' WIB';
                                    } else {
                                        echo 'Mulai ' . $startTime . ' WIB';
                                    }
                                } else {
                                    echo $startTime . ' WIB';
                                }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Lokasi</p>
                        <p class="text-sm text-slate-800 font-semibold"><?= esc($event['venue']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Poster Image (Mobile Only) -->
            <div class="mt-4 lg:hidden">
                <img src="<?= base_url(esc($event['poster_image'])) ?>" 
                     alt="<?= esc($event['name']) ?>" 
                     class="w-full h-auto object-cover rounded-xl border border-slate-100">
            </div>

            <!-- Buy Ticket Button -->
            <div class="mt-6 pt-4 border-t border-slate-150">
                <?php if ($status['purchasable']): ?>
                    <a href="/event/<?= $event['slug'] ?>/select" class="btn-flat-blue w-full py-3 text-center justify-center text-base font-bold">
                        Beli Tiket Sekarang
                    </a>
                <?php else: ?>
                    <button disabled class="w-full inline-flex items-center justify-center font-bold px-5 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed text-base">
                        <?= $status['text'] === 'Telah Berakhir' ? 'Event Telah Berakhir' : 'Tiket Habis Terjual' ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6">
            <!-- Kiri (Poster & Seatmap) -->
            <div class="hidden lg:block lg:col-span-5 space-y-6 lg:sticky lg:top-24 h-fit">
                <div class="card-flat overflow-hidden p-0">
                    <img src="<?= base_url(esc($event['poster_image'])) ?>" 
                         alt="<?= esc($event['name']) ?>" 
                         class="w-full h-auto object-cover rounded-2xl">
                </div>

                <?php if (!empty($event['seatmap_image'])): ?>
                    <div class="bg-slate-900 border border-slate-200 p-4 rounded-2xl shadow-md">
                        <h3 class="text-sm font-bold text-white mb-3 text-center tracking-wider uppercase">Seat Map</h3>
                        <img src="<?= base_url(esc($event['seatmap_image'])) ?>" 
                             alt="Seat Map <?= esc($event['name']) ?>" 
                             class="w-full h-auto object-contain rounded bg-transparent">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kanan (Informasi Event) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="card-flat hidden lg:block">
                    <!-- Category & Status Tag -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="badge-flat uppercase font-bold text-xs py-1 px-3 bg-blue-50">
                            <?= esc($event['category']) ?>
                        </span>
                        <span class="<?= $status['color'] ?> text-xs font-bold px-3 py-1 rounded-full border border-current shadow-xs flex items-center gap-1.5">
                            <?= $status['icon'] ?>
                            <?= $status['text'] ?>
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight">
                        <?= esc($event['name']) ?>
                    </h1>

                    <!-- Event Metadata -->
                    <div class="border-t-2 border-dashed border-slate-100 mt-6 pt-6 space-y-3.5 text-slate-600 font-medium">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Tanggal</p>
                                <p class="text-sm md:text-base text-slate-800">
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
                                                    echo $start->toLocalizedString('d MMM') . ' - ' . $end->toLocalizedString('d MMM yyyy');
                                                }
                                            }
                                        } else {
                                            echo $start->toLocalizedString('d MMMM yyyy');
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Waktu</p>
                                <p class="text-sm md:text-base text-slate-800">
                                    <?php 
                                        $startTime = $start->format('H:i');
                                        if (!empty($event['event_end_date'])) {
                                            $endTime = \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('H:i');
                                            $isSameDay = ($start->format('Y-m-d') === \CodeIgniter\I18n\Time::parse($event['event_end_date'])->format('Y-m-d'));
                                            if ($isSameDay) {
                                                echo $startTime . ' - ' . $endTime . ' WIB';
                                            } else {
                                                echo 'Mulai ' . $startTime . ' WIB';
                                            }
                                        } else {
                                            echo $startTime . ' WIB';
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Lokasi</p>
                                <p class="text-sm md:text-base text-slate-800"><?= esc($event['venue']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Buy Ticket Button -->
                    <div class="mt-8 pt-6 border-t-2 border-slate-100">
                        <?php if ($status['purchasable']): ?>
                            <a href="/event/<?= $event['slug'] ?>/select" class="btn-flat-blue w-full py-4 text-center justify-center text-lg">
                                Beli Tiket Sekarang
                            </a>
                        <?php else: ?>
                            <button disabled class="w-full inline-flex items-center justify-center font-bold px-5 py-4 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed">
                                <?= $status['text'] === 'Telah Berakhir' ? 'Event Telah Berakhir' : 'Tiket Habis Terjual' ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="card-flat">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-4 pb-3 border-b-2 border-slate-100">Deskripsi Event</h3>
                    <div class="prose max-w-none text-slate-600 font-medium leading-relaxed">
                        <?= $event['description'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Bottom CTA for Mobile -->
    <?php if ($status['purchasable']): ?>
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-200 md:hidden z-40 shadow-sm flex items-center justify-between gap-4">
            <div class="flex-grow min-w-0">
                <p class="text-2xs font-extrabold text-slate-400 uppercase tracking-wider">Event</p>
                <h4 class="text-xs font-bold text-slate-800 truncate"><?= esc($event['name']) ?></h4>
            </div>
            <a href="/event/<?= $event['slug'] ?>/select" class="btn-flat-blue py-3 px-5 text-sm font-bold whitespace-nowrap shadow-sm">
                Beli Tiket
            </a>
        </div>
    <?php endif; ?>
</main>

<!-- Modal Zoom Gambar -->
<div id="image-zoom-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 transition-opacity duration-300 opacity-0" role="dialog" aria-modal="true">
    <!-- Close Button -->
    <button type="button" id="close-zoom-btn" class="absolute top-6 right-6 text-white hover:text-gray-300 focus:outline-none z-10 transition-transform hover:scale-110">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    
    <!-- Image Wrapper for Zoom & Pan -->
    <div id="zoom-container" class="relative w-full h-full overflow-hidden flex items-center justify-center p-4 select-none cursor-zoom-in">
        <img id="zoomed-image" src="" alt="Zoomed view" class="max-w-full max-h-[90vh] object-contain transition-transform duration-200 ease-out origin-center">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('image-zoom-modal');
    const zoomedImg = document.getElementById('zoomed-image');
    const closeBtn = document.getElementById('close-zoom-btn');
    const zoomContainer = document.getElementById('zoom-container');

    // Menargetkan gambar poster (mobile/desktop) dan gambar seatmap
    const zoomableImages = document.querySelectorAll('.lg\\:block .card-flat img, .lg\\:hidden img.object-cover, .bg-slate-900 img');

    let scale = 1;
    let isDragging = false;
    let startX = 0, startY = 0;
    let translateX = 0, translateY = 0;

    zoomableImages.forEach(img => {
        img.classList.add('cursor-pointer', 'transition-all', 'duration-300', 'hover:opacity-95');
        
        img.addEventListener('click', function() {
            zoomedImg.src = this.src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                modal.classList.remove('opacity-0');
            }, 10);
            
            // Reset zoom state
            scale = 1;
            translateX = 0;
            translateY = 0;
            updateImageTransform();
        });
    });

    function updateImageTransform() {
        zoomedImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        if (scale > 1) {
            zoomContainer.classList.remove('cursor-zoom-in');
            zoomContainer.classList.add('cursor-grab');
        } else {
            zoomContainer.classList.remove('cursor-grab', 'cursor-grabbing');
            zoomContainer.classList.add('cursor-zoom-in');
        }
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        document.body.style.overflow = '';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            zoomedImg.src = '';
        }, 300);
    }

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target === zoomContainer) {
            closeModal();
        }
    });

    // Zoom on wheel
    zoomContainer.addEventListener('wheel', function(e) {
        e.preventDefault();
        const zoomIntensity = 0.1;
        if (e.deltaY < 0) {
            scale = Math.min(scale + zoomIntensity, 4);
        } else {
            scale = Math.max(scale - zoomIntensity, 1);
            if (scale === 1) {
                translateX = 0;
                translateY = 0;
            }
        }
        updateImageTransform();
    }, { passive: false });

    // Double click to toggle zoom
    zoomContainer.addEventListener('dblclick', function() {
        if (scale > 1) {
            scale = 1;
            translateX = 0;
            translateY = 0;
        } else {
            scale = 2;
            translateX = 0;
            translateY = 0;
        }
        updateImageTransform();
    });

    // Pan (drag) functionality
    zoomContainer.addEventListener('mousedown', function(e) {
        if (scale > 1) {
            isDragging = true;
            zoomContainer.classList.remove('cursor-grab');
            zoomContainer.classList.add('cursor-grabbing');
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        }
    });

    window.addEventListener('mousemove', function(e) {
        if (isDragging) {
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            updateImageTransform();
        }
    });

    window.addEventListener('mouseup', function() {
        if (isDragging) {
            isDragging = false;
            zoomContainer.classList.remove('cursor-grabbing');
            zoomContainer.classList.add('cursor-grab');
        }
    });

    // Touch events for mobile zooming
    let touchStartDist = 0;
    let initialScale = 1;

    zoomContainer.addEventListener('touchstart', function(e) {
        if (e.touches.length === 1 && scale > 1) {
            isDragging = true;
            startX = e.touches[0].clientX - translateX;
            startY = e.touches[0].clientY - translateY;
        } else if (e.touches.length === 2) {
            isDragging = false;
            touchStartDist = getTouchDistance(e.touches);
            initialScale = scale;
        }
    });

    zoomContainer.addEventListener('touchmove', function(e) {
        if (isDragging && e.touches.length === 1) {
            translateX = e.touches[0].clientX - startX;
            translateY = e.touches[0].clientY - startY;
            updateImageTransform();
        } else if (e.touches.length === 2) {
            const dist = getTouchDistance(e.touches);
            const factor = dist / touchStartDist;
            scale = Math.min(Math.max(initialScale * factor, 1), 4);
            updateImageTransform();
        }
    }, { passive: true });

    zoomContainer.addEventListener('touchend', function() {
        isDragging = false;
    });

    function getTouchDistance(touches) {
        return Math.hypot(
            touches[0].clientX - touches[1].clientX,
            touches[0].clientY - touches[1].clientY
        );
    }
});
</script>

<?= $this->endSection() ?>