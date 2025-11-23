<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-center py-10">
    <div class="w-full max-w-[1200px] bg-[#ffe398] rounded-[25px] border border-black p-8 md:p-18 flex flex-col md:flex-row gap-4">

        <!-- LEFT PANEL -->
        <div class="w-full md:w-1/2">
            <img src="/assets/ticketly-logo.png" class="w-40 mb-6">

            <h2 class="text-3xl font-bold text-gray-900 mb-2">Gunakan Tautan Masuk</h2>
            <p class="text-gray-700 w-72 mb-8">
                Masuk menggunakan tautan email anda
            </p>

            <!-- extra spacing -->
            <div class="mt-10"></div>
        </div>

          <!-- RIGHT PANEL (THE FORM) -->
        <div class="w-full md:w-1/2">

                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
                <?php elseif (session('errors') !== null) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php if (is_array(session('errors'))) : ?>
                            <?php foreach (session('errors') as $error) : ?>
                                <?= esc($error) ?>
                                <br>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?= esc(session('errors')) ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>

            <form action="<?= url_to('magic-link') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Email -->

                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email"
                        placeholder="<?= lang('Auth.email') ?>"
                        value="<?= old('email', auth()->user()->email ?? null) ?>" required
                        class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                    >
                </div>
                <div class="flex items-center justify-end gap-4 pt-4"> 
                <a href="<?= url_to('login') ?>" class="text-blue-600 font-semibold hover:underline">
                        <?= lang('Auth.backToLogin') ?>
                    </a>
                <button type="submit" class="bg-blue-500 text-white px-8 py-2 rounded-md font-semibold shadow-md hover:bg-blue-600 transition">
                        <?= lang('Auth.send') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
