<?php $pager->setSurroundCount(2) ?>

<div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 p-4">
    
    <ul class="inline-flex items-stretch -space-x-px">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                    <span class="sr-only">Previous</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-300 bg-gray-50 rounded-l-lg border border-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight border border-gray-300 <?= $link['active'] ? 'z-10 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700' : 'text-gray-500 hover:text-gray-700' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                    <span class="sr-only">Next</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-300 bg-gray-50 rounded-r-lg border border-gray-300 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            </li>
        <?php endif ?>
    </ul>

    <div class="relative">
        <?php 
            // Ambil nilai per_page dari URL, default 10
            $currentPerPage = service('request')->getGet('per_page') ?? 10; 
        ?>
        
        <button id="dropdownPerPageBtn" data-dropdown-toggle="dropdownPerPage" class="text-gray-900 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center inline-flex items-center" type="button">
            <?= $currentPerPage ?> per page
            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        </button>

        <div id="dropdownPerPage" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-32 border border-gray-100">
            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownPerPageBtn">
                <?php foreach ([10, 25, 50, 100] as $limit): ?>
                    <li>
                        <button type="button" onclick="changePerPage(<?= $limit ?>)" class="inline-flex w-full px-4 py-2 hover:bg-gray-100 hover:text-blue-700 <?= $limit == $currentPerPage ? 'font-bold text-blue-700 bg-gray-50' : '' ?>">
                            <?= $limit ?> per page
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <form id="perPageForm" method="get" class="hidden">
            <?php foreach (service('request')->getGet() as $key => $value): ?>
                <?php if ($key !== 'per_page' && $key !== 'page_default'): // Jangan duplikat per_page & reset halaman ?>
                    <input type="hidden" name="<?= esc($key) ?>" value="<?= esc($value) ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            
            <input type="hidden" name="per_page" id="perPageInput" value="<?= $currentPerPage ?>">
        </form>
    </div>
</div>

<script>
    function changePerPage(amount) {
        // Set nilai input hidden
        document.getElementById('perPageInput').value = amount;
        // Submit form
        document.getElementById('perPageForm').submit();
    }
</script>