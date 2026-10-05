<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mahasiswa Login | SISKA - Universitas Bumigora</title>
    <link rel="icon" href="<?= app_favicon() ?>">

    <!-- Google Font: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#2185D5',
                            darkblue: '#1A6FB3',
                            navy: '#155B94',
                            slate: '#3A4750',
                            charcoal: '#303841',
                            bg: '#F3F3F3'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-[#F3F3F3] font-sans text-[#3A4750] flex flex-col justify-between selection:bg-[#2185D5] selection:text-white">

    <!-- BEGIN: MainContent -->
    <main class="flex-grow flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-slate-300/80 shadow-md p-6 sm:p-8 text-[#3A4750]">

                <!-- Login Header -->
                <div class="text-center pb-5 mb-5 border-b border-slate-100">
                    <div class="inline-flex items-center justify-center mb-3">
                        <img src="<?= app_logo() ?>" alt="Logo Universitas Bumigora" class="h-16 w-auto object-contain">
                    </div>
                    <h3 class="text-xl font-bold text-[#303841]">Masuk Akun Mahasiswa</h3>
                    <p class="text-xs text-[#3A4750]/70 mt-1">Silakan masukkan NIM dan kata sandi SISKA Anda</p>
                </div>

                <!-- Flash Message / Alert Notification -->
                <?php $pesan = $this->session->flashdata('pesan'); ?>
                <?php if (!empty($pesan)): ?>
                    <div class="mb-4 p-3.5 rounded-lg text-xs font-medium flex items-start gap-2.5 bg-rose-50 text-rose-800 border border-rose-200">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 text-sm text-rose-600 shrink-0"></i>
                        <div><?= e($pesan); ?></div>
                    </div>
                <?php endif; ?>

                <!-- Form Content -->
                <form action="<?= site_url('mahasiswa/login_mahasiswa/cek_login'); ?>" method="post" class="space-y-4">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                    <!-- NIM Input -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-[#3A4750]" for="nim">
                            Nomor Induk Mahasiswa (NIM)
                        </label>
                        <div class="relative">
                            <input class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border <?= form_error('nim') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-[#2185D5] focus:ring-[#2185D5]' ?> text-[#303841] bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="nim" name="nim" placeholder="Masukkan NIM..." required type="text" inputmode="numeric" maxlength="15" autocomplete="off" value="<?= set_value('nim') ?>">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-xs"></i>
                            </div>
                        </div>
                        <?php if (form_error('nim')): ?>
                            <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('nim'); ?></p>
                        <?php else: ?>
                            <p class="text-[11px] font-medium text-[#3A4750]/70 flex items-center gap-1 mt-1">
                                <i class="fa-solid fa-circle-info text-[#303841] text-[11px]"></i>
                                Gunakan NIM yang terdaftar pada SISKA
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-[#3A4750]" for="sandi">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <input class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border <?= form_error('sandi') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-[#2185D5] focus:ring-[#2185D5]' ?> text-[#303841] bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="sandi" name="sandi" placeholder="••••••••••••••••" required type="password" autocomplete="current-password" value="<?= set_value('sandi') ?>">
                            <button aria-label="Lihat kata sandi" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors" id="togglePassword" type="button">
                                <i class="fa-solid fa-lock text-xs" id="lockIcon"></i>
                            </button>
                        </div>
                        <?php if (form_error('sandi')): ?>
                            <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('sandi'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons Group -->
                    <div class="pt-2">
                        <button class="w-full py-2.5 px-4 rounded-lg bg-[#2185D5] hover:bg-[#1A6FB3] active:bg-[#155B94] text-white text-sm font-bold tracking-wide shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer" type="submit">
                            <i class="fa-solid fa-right-to-bracket text-xs"></i>
                            <span>Masuk Mahasiswa</span>
                        </button>
                    </div>
                </form>

                <!-- Divider & Portal Link -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-xs text-[#3A4750]/70">
                        Bukan Mahasiswa?
                        <a href="<?= site_url('login') ?>" class="text-[#303841] font-bold hover:text-black hover:underline inline-flex items-center gap-1">
                            Kembali ke Portal Login <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </p>
                </div>

                <!-- Footer Information -->
                <div class="mt-5 pt-4 border-t border-slate-100 text-center text-[11px] text-slate-500 space-y-0.5">
                    <p class="font-medium text-slate-600">
                        &copy; <?= date('Y') ?> <strong>Universitas Bumigora</strong> (UBG). Hak Cipta Dilindungi.
                    </p>
                    <p class="text-slate-400">
                        Jl. Ismail Marzuki No. 22, Cilinaya, Kec. Cakranegara, Kota Mataram, NTB 83127
                    </p>
                    <p class="text-slate-400">SISKA v2.0 Terintegrasi</p>
                </div>

            </div>
        </div>
    </main>
    <!-- END: MainContent -->

    <!-- Scripts -->
    <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
    <?php $this->load->view('csrf_js'); ?>

    <script>
        // Password Visibility Toggle
        const toggleButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('sandi');
        const lockIcon = document.getElementById('lockIcon');

        if (toggleButton && passwordInput && lockIcon) {
            toggleButton.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                if (isPassword) {
                    passwordInput.setAttribute('type', 'text');
                    lockIcon.classList.remove('fa-lock');
                    lockIcon.classList.add('fa-unlock');
                } else {
                    passwordInput.setAttribute('type', 'password');
                    lockIcon.classList.remove('fa-unlock');
                    lockIcon.classList.add('fa-lock');
                }
            });
        }
    </script>
</body>
</html>
