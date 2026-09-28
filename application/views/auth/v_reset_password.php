<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= isset($title) ? $title : 'Reset Password' ?> - Universitas Bumigora</title>
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
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
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
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Atur Ulang Kata Sandi</h3>
                    <p class="text-xs text-slate-500 mt-1">Buat kata sandi baru untuk akun SISKA Anda</p>
                    <?php if (!empty($nama)): ?>
                        <div class="mt-2 text-xs text-slate-600 bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-3 inline-block">
                            Akun: <strong class="text-slate-800"><?= htmlspecialchars($nama); ?></strong>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Flash Message / Alert Notification -->
                <?= $this->session->flashdata('pesan'); ?>

                <?php if (!$this->session->flashdata('pesan')): ?>
                    <!-- Form Content -->
                    <?= form_open('lupa_password/reset_password/token/' . $token, array('class' => 'space-y-4')); ?>
                        
                        <!-- New Password Input -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700" for="password">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border <?= form_error('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-sky-600 focus:ring-sky-600' ?> text-slate-900 bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="password" name="password" placeholder="Minimal 8 karakter" required type="password" autocomplete="new-password">
                                <button aria-label="Lihat kata sandi" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors" id="togglePassword" type="button">
                                    <i class="fa-solid fa-eye text-xs" id="lockIcon"></i>
                                </button>
                            </div>
                            <?php if (form_error('password')): ?>
                                <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('password'); ?></p>
                            <?php else: ?>
                                <p class="text-[11px] font-medium text-slate-500 mt-1">Minimal 8 karakter.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700" for="passconf">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border <?= form_error('passconf') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-sky-600 focus:ring-sky-600' ?> text-slate-900 bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="passconf" name="passconf" placeholder="Ulangi kata sandi baru" required type="password" autocomplete="new-password">
                                <button aria-label="Lihat konfirmasi kata sandi" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors" id="togglePassconf" type="button">
                                    <i class="fa-solid fa-eye text-xs" id="lockIconConf"></i>
                                </button>
                            </div>
                            <?php if (form_error('passconf')): ?>
                                <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('passconf'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button class="w-full py-2.5 px-4 rounded-lg bg-sky-700 hover:bg-sky-800 active:bg-sky-900 text-white text-sm font-bold tracking-wide shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer" type="submit" name="submit" value="Simpan Password">
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                <span>Simpan Kata Sandi Baru</span>
                            </button>
                        </div>
                    <?= form_close(); ?>
                <?php endif; ?>

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
        function setupToggle(buttonId, inputId, iconId) {
            const btn = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (btn && input && icon) {
                btn.addEventListener('click', () => {
                    const isPassword = input.getAttribute('type') === 'password';
                    if (isPassword) {
                        input.setAttribute('type', 'text');
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.setAttribute('type', 'password');
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }
        }
        setupToggle('togglePassword', 'password', 'lockIcon');
        setupToggle('togglePassconf', 'passconf', 'lockIconConf');
    </script>
</body>
</html>