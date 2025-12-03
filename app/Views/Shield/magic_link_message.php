<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-center py-10">
    <div class="w-full max-w-[1200px] bg-[#ffe398] rounded-[25px] border border-black p-8 md:p-18 flex flex-col md:flex-row gap-4">
        
        <div class="w-full md:w-1/2">
            <img src="/assets/ticketly-logo.png" class="w-40 mb-6">

            <h2 class="text-3xl font-bold text-gray-900 mb-2"><?= lang('Auth.useMagicLink') ?></h2>
            <div class="mt-10"></div>
            
            <p class="font-bold text-gray-700 mb-2"><?= lang('Auth.checkYourEmail') ?></p>

            <p class="text-gray-700 w-72 mb-8"><?= lang('Auth.magicLinkDetails', [setting('Auth.magicLinkLifetime') / 60]) ?></p>
        </div>
    
        <div class="w-full md:w-1/2 flex justify-end">
            <img src="/assets/icon/password-shield.svg" class="w-80 mb-6">
        </div>
    </div>
</div>

<?= $this->endSection() ?>
