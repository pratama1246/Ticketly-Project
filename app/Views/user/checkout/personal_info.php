<?= $this->extend('layout/checkout') ?>
<?= $this->section('content') ?>

<main class="w-full pt-[152px] md:pt-[120px] mb-20 grow transition-all duration-300">
<?php
    $sessionData = session()->get('checkout_process');
    $p = $sessionData['personal_data'] ?? [];
    $errors = session('errors');
?>

    <div class="max-w-3xl mx-auto p-4 animate-fade-in">
        
        <div class="card-flat">
            <!-- Header & Info Alert -->
            <h2 class="text-2xl font-extrabold text-slate-900 mb-4 border-b-2 border-slate-100 pb-3">Informasi Personal</h2>

            <div class="flex items-start p-4 mb-6 text-sm text-blue-900 border border-blue-200 rounded-2xl bg-blue-50/60 shadow-sm animate-fade-in-down" role="alert">
                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 border border-blue-200 rounded-full shrink-0 mr-3 text-blue-600">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-extrabold text-base block mb-0.5 text-blue-950">Tiketmu Aman!</span>
                    <span class="font-medium text-blue-800">Kami menahan stok tiket pilihanmu selama <strong class="underline">5 menit</strong>. Yuk, segera lengkapi data dirimu sebelum waktu habis!</span>
                </div>
            </div>
            
            <form action="/checkout/process_personal_info" method="POST" id="personalInfoForm" class="space-y-5">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">Nama Depan <span class="text-red-500">*</span></label>
                        <input type="text" id="first_name" name="first_name" 
                               value="<?= old('first_name', $p['first_name'] ?? '') ?>" 
                               class="input-flat text-sm <?= isset($errors['first_name']) ? 'border-red-500 bg-red-50 focus:ring-red-500' : '' ?>" 
                               required>
                        <?php if(isset($errors['first_name'])): ?>
                            <p class="mt-1 text-sm text-red-600 font-medium">
                                <?= esc($errors['first_name']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="last_name" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">Nama Belakang</label>
                        <input type="text" id="last_name" name="last_name" 
                               value="<?= old('last_name', $p['last_name'] ?? '') ?>" 
                               class="input-flat text-sm <?= isset($errors['last_name']) ? 'border-red-500 bg-red-50 focus:ring-red-500' : '' ?>">
                        <?php if(isset($errors['last_name'])): ?>
                            <p class="mt-1 text-sm text-red-600 font-medium"><?= esc($errors['last_name']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" 
                               value="<?= old('email', $p['email'] ?? '') ?>" 
                               class="input-flat text-sm <?= isset($errors['email']) ? 'border-red-500 bg-red-50 focus:ring-red-500' : '' ?>" 
                               required>
                        <?php if(isset($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600 font-medium"><?= esc($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="phone_number" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone_number" name="phone_number" 
                               value="<?= old('phone_number', $p['phone_number'] ?? '') ?>" 
                               class="input-flat text-sm <?= isset($errors['phone_number']) ? 'border-red-500 bg-red-50 focus:ring-red-500' : '' ?>" 
                               required>
                        <?php if(isset($errors['phone_number'])): ?>
                            <p class="mt-1 text-sm text-red-600 font-medium"><?= esc($errors['phone_number']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="identity_number" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">Nomor Identitas (KTP/SIM/NIK/Paspor) <span class="text-red-500">*</span></label>
                        <input type="text" id="identity_number" name="identity_number" 
                               value="<?= old('identity_number', $p['identity_number'] ?? '') ?>" 
                               class="input-flat text-sm <?= isset($errors['identity_number']) ? 'border-red-500 bg-red-50 focus:ring-red-500' : '' ?>" 
                               required>
                        <?php if(isset($errors['identity_number'])): ?>
                            <p class="mt-1 text-sm text-red-600 font-medium"><?= esc($errors['identity_number']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="birth_date" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                        
                        <?php 
                            $birthVal = old('birth_date', $p['birth_date'] ?? '');
                            if ($birthVal && strpos($birthVal, '-') !== false) {
                                $birthVal = date('d/m/Y', strtotime($birthVal));
                            }
                        ?>
                        <input id="birth_date" name="birth_date" type="text" 
                               value="<?= $birthVal ?>" 
                               class="input-flat text-sm <?= isset($errors['birth_date']) ? 'border-red-500 bg-red-50 focus:ring-red-500' : '' ?>" 
                               placeholder="DD/MM/YYYY" required>
                        <?php if(isset($errors['birth_date'])): ?>
                            <p class="mt-1 text-sm text-red-600 font-medium"><?= esc($errors['birth_date']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="border-t-2 border-slate-100 pt-5 mt-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Saya setuju untuk menerima notifikasi terkait pemesanan tiket berikut melalui nomor WhatsApp saya.
                    </p>
                    <div class="flex items-center gap-6 mb-6">
                        <div class="flex items-center">
                            <input id="wa_yes" type="radio" value="yes" name="whatsapp_notif" class="w-4 h-4 text-blue-600 border border-slate-200 focus:ring-blue-500">
                            <label for="wa_yes" class="ms-2 text-sm font-bold text-slate-800">Iya</label>
                        </div>
                        <div class="flex items-center">
                            <input id="wa_no" type="radio" value="no" name="whatsapp_notif" class="w-4 h-4 text-blue-600 border border-slate-200 focus:ring-blue-500" checked>
                            <label for="wa_no" class="ms-2 text-sm font-bold text-slate-800">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-slate-100">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" value="1" class="w-4 h-4 text-blue-primary-normal border border-slate-200 rounded bg-slate-50 focus:ring-blue-500" required>
                        </div>
                        <label for="terms" class="ms-3 text-xs font-semibold text-slate-600">
                            Dengan mengklik “Lanjut ke Pembayaran”, kamu menyetujui <a href="#" class="text-blue-600 hover:underline font-bold">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline font-bold">Kebijakan Privasi</a> Ticketly.
                        </label>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="privacy_data" name="privacy_data" type="checkbox" value="1" class="w-4 h-4 text-blue-primary-normal border border-slate-200 rounded bg-slate-50 focus:ring-blue-500" required>
                        </div>
                        <label for="privacy_data" class="ms-3 text-xs font-semibold text-slate-600">
                            Dengan mengklik “Lanjut ke Pembayaran”, kamu menyetujui <a href="#" class="text-blue-600 hover:underline font-bold">Kebijakan Pemrosesan Data Pribadi</a> Ticketly.
                        </label>
                    </div>
                </div>
                
                <!-- Tombol Lanjut ke Pembayaran -->
                <div class="mt-8 pt-5 border-t-2 border-slate-100 gap-3 flex flex-col-reverse sm:flex-row sm:justify-end">
                    <button type="button" onclick="showCancelModal()" class="btn-flat-gray w-full sm:w-auto justify-center text-center">
                        Batal
                    </button>
                    <button type="submit" class="btn-flat-blue w-full sm:w-auto justify-center text-center shadow-sm hover:translate-y-0.5">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?= $this->endSection() ?>