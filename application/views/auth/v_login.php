<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SISKA - Universitas Bumigora | Sistem Informasi Akademik</title>
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
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 flex flex-col justify-between selection:bg-sky-600 selection:text-white">

    <!-- BEGIN: MainContent -->
    <main class="flex-grow flex items-center justify-center py-8 sm:py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">

            <!-- BEGIN: LoginFormSection -->
            <section class="w-full" data-purpose="login-panel">
                <div class="bg-white rounded-2xl border border-slate-300/80 shadow-md p-6 sm:p-7 text-slate-800">
                    
                    <!-- Login Header -->
                    <div class="text-center pb-5 mb-5 border-b border-slate-100">
                        <div class="inline-flex items-center justify-center mb-2.5">
                            <img src="<?= app_logo() ?>" alt="Logo Universitas Bumigora" class="h-16 w-auto object-contain">
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Masuk Akun SISKA</h3>
                        <p class="text-xs text-slate-500 mt-1">Silakan masukkan identitas akun resmi Anda</p>
                    </div>

                    <!-- Flash Message / Alert Notification -->
                    <?php if ($this->session->flashdata('pesan')): ?>
                        <div class="mb-4 p-3.5 rounded-lg text-xs font-medium flex items-start gap-2.5 <?= ($this->session->flashdata('tipe') == 'danger' || strpos(strtolower($this->session->flashdata('pesan')), 'salah') !== false || strpos(strtolower($this->session->flashdata('pesan')), 'belum') !== false) ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' ?>">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 text-sm <?= ($this->session->flashdata('tipe') == 'danger' || strpos(strtolower($this->session->flashdata('pesan')), 'salah') !== false) ? 'text-rose-600' : 'text-emerald-600' ?>"></i>
                            <div><?= $this->session->flashdata('pesan'); ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Form Content -->
                    <?= form_open('login', array('id' => 'siska-login-form', 'class' => 'space-y-4')); ?>
                        
                        <!-- Identifier / Username Input -->
                        <div class="space-y-1.5" data-purpose="input-group-identifier">
                            <label class="block text-xs font-bold text-slate-700" for="username">
                                Username
                            </label>
                            <div class="relative">
                                <input class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border <?= form_error('username') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-sky-600 focus:ring-sky-600' ?> text-slate-900 bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="username" name="username" placeholder="Masukkan NIM atau NIDN..." required type="text" autocomplete="off" value="<?= set_value('username') ?>">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                            </div>
                            <?php if (form_error('username')): ?>
                                <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('username'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-1.5" data-purpose="input-group-password">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700" for="password">
                                    Kata Sandi
                                </label>
                                <a class="text-xs text-sky-700 hover:text-sky-800 font-semibold hover:underline transition-colors" href="<?= site_url('lupa_password') ?>">
                                    Lupa Password?
                                </a>
                            </div>
                            <div class="relative">
                                <input class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border <?= form_error('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-sky-600 focus:ring-sky-600' ?> text-slate-900 bg-white focus:bg-white text-sm focus:ring-1 transition-all outline-none" id="password" name="password" placeholder="••••••••••••••••" required type="password" value="<?= set_value('password') ?>">
                                <button aria-label="Lihat kata sandi" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors" id="togglePassword" type="button">
                                    <i class="fa-solid fa-lock text-xs" id="lockIcon"></i>
                                </button>
                            </div>
                            <?php if (form_error('password')): ?>
                                <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('password'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Role Selector Dropdown -->
                        <div class="space-y-1.5" data-purpose="input-group-role">
                            <label class="block text-xs font-bold text-slate-700" for="userRole">
                                Hak Akses / Peran
                            </label>
                            <div class="relative">
                                <select class="w-full py-2.5 pl-3.5 pr-10 rounded-lg border <?= form_error('status') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-sky-600 focus:ring-sky-600' ?> text-slate-700 bg-white focus:bg-white text-sm focus:ring-1 appearance-none transition-all outline-none" id="userRole" name="status" required>
                                    <option value="mahasiswa" <?= (set_value('status', 'mahasiswa') == 'mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                                    <option value="dosen" <?= (set_value('status') == 'dosen') ? 'selected' : '' ?>>Dosen / Tenaga Pengajar</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <?php if (form_error('status')): ?>
                                <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('status'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons Group -->
                        <div class="pt-2 space-y-2.5">
                            <!-- Primary Login Button -->
                            <button class="w-full py-2.5 px-4 rounded-lg bg-sky-700 hover:bg-sky-800 active:bg-sky-900 text-white text-sm font-bold tracking-wide shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer" type="submit" name="submit" value="Log In">
                                <i class="fa-solid fa-right-to-bracket text-xs"></i>
                                <span>Log In ke SISKA</span>
                            </button>

                            <!-- Secondary Payment Validation Button -->
                            <button type="button" onclick="openPaymentModal()" class="w-full py-2.5 px-4 rounded-lg bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-sm font-bold tracking-wide shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer text-center">
                                <i class="fa-solid fa-receipt text-xs"></i>
                                <span>Cek Validasi Pembayaran!</span>
                            </button>
                        </div>
                    <?= form_close(); ?>

                    <!-- Divider -->
                    <div class="my-5 flex items-center">
                        <div class="flex-grow border-t border-slate-200"></div>
                        <span class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Tautan Akademik Cepat</span>
                        <div class="flex-grow border-t border-slate-200"></div>
                    </div>

                    <!-- BEGIN: InformationalQuickLinks -->
                    <nav aria-label="Tautan Informasi Mahasiswa" class="space-y-1.5" data-purpose="quick-academic-links">
                        <a class="group flex items-center justify-between p-2 rounded-lg text-xs font-semibold text-slate-700 hover:text-sky-700 hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200" href="https://www.youtube.com/watch?v=HgRVzuzvFf4" target="_blank">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-book-bookmark text-sky-600"></i>
                                <span>Cara Mengurus KRS MABA</span>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-sky-600 transition-colors"></i>
                        </a>
                    </nav>
                    <!-- END: InformationalQuickLinks -->

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
            </section>
            <!-- END: LoginFormSection -->

        </div>
    </main>
    <!-- END: MainContent -->

    <!-- BEGIN: Modal Cek Validasi Pembayaran -->
    <div id="modal-cek-validasi" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h4 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-rose-600"></i> Cek Validasi Pembayaran
                </h4>
                <button type="button" onclick="closePaymentModal()" class="text-slate-400 hover:text-slate-600 w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <form id="form-cek-pembayaran" action="<?= site_url('CekPembayaran/search') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5" for="input-nim">Nomor Induk Mahasiswa (NIM)</label>
                    <div class="flex gap-2">
                        <input type="text" id="input-nim" inputmode="numeric" pattern="[0-9]+" name="nim" required class="flex-grow py-2.5 px-3.5 rounded-lg border border-slate-300 text-sm focus:border-sky-600 focus:ring-1 focus:ring-sky-600 outline-none transition" minlength="8" maxlength="20" title="Masukkan digit NIM dengan benar">
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-lg text-sm font-bold transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
            <div id="landing-result" class="max-h-80 overflow-y-auto mt-2 text-xs"></div>
        </div>
    </div>
    <!-- END: Modal Cek Validasi Pembayaran -->

    <!-- Scripts -->
    <script src="<?= base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js') ?>"></script>
    <?php $this->load->view('csrf_js'); ?>

    <script>
        // Password Visibility Toggle
        const toggleButton = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
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


        // Modal Cek Validasi Pembayaran
        function openPaymentModal() {
            const modal = document.getElementById('modal-cek-validasi');
            if (modal) {
                modal.classList.remove('hidden');
                document.getElementById('input-nim').focus();
            }
        }

        function closePaymentModal() {
            const modal = document.getElementById('modal-cek-validasi');
            if (modal) {
                modal.classList.add('hidden');
                document.getElementById('landing-result').innerHTML = '';
            }
        }

        // Close modal when clicking on backdrop
        document.getElementById('modal-cek-validasi')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        // AJAX search payment validation
        $(document).ready(function () {
            $('#form-cek-pembayaran').submit(function (e) {
                e.preventDefault();
                var url = $(this).prop('action');
                var data = $(this).serialize();
                $('#landing-result').html('<div class="text-center py-4 text-slate-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat status pembayaran...</div>');
                $.ajax({
                    url: url,
                    data: data,
                    type: 'post',
                    success: function (res) {
                        $('#landing-result').html(res);
                    },
                    error: function() {
                        $('#landing-result').html('<div class="p-3 bg-rose-50 text-rose-700 rounded-lg text-xs">Gagal memuat data pembayaran. Silakan coba kembali.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>