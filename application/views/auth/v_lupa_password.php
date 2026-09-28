<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= isset($title) ? $title : 'Lupa Password' ?> - Universitas Bumigora</title>
    <link rel="icon" href="<?= app_favicon() ?>">

    <!-- Google Font: Roboto -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&amp;display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Roboto"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        ubg: {
                            blue: '#0369a1',
                            darkblue: '#1e3a8a',
                            navy: '#0f172a',
                            green: '#047857',
                            darkgreen: '#064e3b',
                            lightgreen: '#ecfdf5',
                            accent: '#d97706',
                            danger: '#dc2626'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.8125rem;
            line-height: 1.25rem;
        }
        .alert h5 {
            font-weight: 700;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        .alert h6 {
            font-weight: 500;
            font-size: 0.8125rem;
        }
        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-warning {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 flex flex-col justify-between selection:bg-sky-600 selection:text-white">

    <!-- BEGIN: University Main Header -->
    <header class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="<?= app_logo() ?>" alt="Logo Universitas Bumigora" class="h-10 w-auto object-contain">
                <div>
                    <h2 class="text-base font-bold text-slate-900 leading-tight tracking-tight">UNIVERSITAS BUMIGORA</h2>
                    <p class="text-[11px] text-slate-500 font-medium">Sistem Informasi Akademik (SISKA)</p>
                </div>
            </div>
            <a href="<?= site_url('login') ?>" class="text-xs font-semibold text-sky-700 hover:text-sky-800 flex items-center gap-1.5 transition-colors">
                <span class="hidden sm:inline">Halaman Login</span>
            </a>
        </div>
    </header>
    <!-- END: University Main Header -->

    <!-- BEGIN: MainContent -->
    <main class="flex-grow flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-slate-300/80 shadow-md p-6 sm:p-8 text-slate-800">
                
                <!-- Card Header -->
                <div class="text-center pb-5 mb-5 border-b border-slate-100">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-sky-50 text-sky-700 border border-sky-100 mb-2.5 shadow-sm">
                        <i class="fa-solid fa-key text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Lupa Kata Sandi</h3>
                    <p class="text-xs text-slate-500 mt-1">Masukkan email terdaftar untuk menerima link verifikasi reset kata sandi</p>
                </div>

                <!-- Flash Message / Alert Notification -->
                <?= $this->session->flashdata('pesan'); ?>
                <?= $this->session->flashdata('email_salah'); ?>

                <!-- Form Content -->
                <?= form_open('lupa_password', array('class' => 'space-y-4')); ?>
                    
                    <!-- Email Input -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700" for="email">
                            Alamat Email Terdaftar
                        </label>
                        <div class="relative">
                            <input class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border <?= form_error('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-sky-600 focus:ring-sky-600' ?> text-slate-900 bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="email" name="email" required type="email" autocomplete="off" value="<?= set_value('email') ?>">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                        </div>
                        <?php if (form_error('email')): ?>
                            <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('email'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Captcha Input -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700" for="captcha">
                            Verifikasi Keamanan: Hitung Hasilnya
                        </label>
                        <div class="flex items-center gap-2">
                            <img src="<?= site_url('Lupa_password/captcha_image'); ?>" id="captcha-img" class="h-11 rounded-lg border border-slate-300 shadow-sm object-cover" alt="Captcha">
                            <button type="button" onclick="refreshCaptcha()" title="Muat ulang verifikasi" class="h-11 w-11 flex-shrink-0 flex items-center justify-center rounded-lg border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-600 transition-colors">
                                <i class="fa-solid fa-arrows-rotate text-sm"></i>
                            </button>
                            <input class="w-full py-2.5 px-3.5 rounded-lg border <?= form_error('captcha') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-sky-600 focus:ring-sky-600' ?> text-slate-900 bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="captcha" name="captcha" placeholder="Jawaban" required type="number" autocomplete="off">
                        </div>
                        <?php if (form_error('captcha')): ?>
                            <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('captcha'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button class="w-full py-2.5 px-4 rounded-lg bg-sky-700 hover:bg-sky-800 active:bg-sky-900 text-white text-sm font-bold tracking-wide shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer" type="submit" name="submit" value="Kirim Link">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Kirim Link Reset</span>
                        </button>
                    </div>
                <?= form_close(); ?>

                <!-- Back to Login Link -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <a href="<?= site_url('login') ?>" class="inline-flex items-center justify-center gap-2 text-xs font-semibold text-sky-700 hover:text-sky-800 hover:underline transition-colors">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i>
                        <span>Kembali ke Halaman Login</span>
                    </a>
                </div>

            </div>
        </div>
    </main>
    <!-- END: MainContent -->

    <!-- BEGIN: MainFooter -->
    <footer class="border-t border-slate-200 bg-white py-4 px-6 text-xs text-slate-600">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-3 text-center md:text-left">
            <div>
                <p class="font-medium">
                    &copy; 2025 <strong>Universitas Bumigora</strong> (UBG). Hak Cipta Dilindungi.
                </p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Jl. Ismail Marzuki No. 22, Cilinaya, Kec. Cakranegara, Kota Mataram, Nusa Tenggara Barat 83127
                </p>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-slate-500">
                <span>SISKA v2.0 Terintegrasi</span>
            </div>
        </div>
    </footer>
    <!-- END: MainFooter -->

    <!-- Scripts -->
    <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
    <?php $this->load->view('csrf_js'); ?>

    <script>
        function refreshCaptcha() {
            var img = document.getElementById('captcha-img');
            if (img) {
                img.src = '<?= site_url('Lupa_password/captcha_image'); ?>?' + new Date().getTime();
            }
        }
    </script>
</body>
</html>