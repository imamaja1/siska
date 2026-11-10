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

    <!-- Google Font: Inter & Roboto -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', '"Roboto"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#2563eb',
                            hoverblue: '#1d4ed8',
                            danger: '#ef4444',
                            hoverdanger: '#dc2626',
                            dark: '#0f172a',
                            muted: '#64748b',
                            cardbg: '#f8fafc',
                            border: '#e2e8f0'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-[#f1f5f9] font-sans text-slate-800 flex items-center justify-center p-3 sm:p-5 lg:p-8 selection:bg-blue-600 selection:text-white">

    <!-- BEGIN: Main App Container (Figma: Desktop / SISKA Login Modern v3) -->
    <main class="w-full max-w-[1360px] bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-4 sm:p-5 lg:p-6 my-auto">
        <div class="flex flex-col lg:flex-row gap-5 lg:gap-8 items-stretch justify-between">

            <!-- BEGIN: Left Column (Col / Auth Form) -->
            <section class="w-full lg:w-[480px] xl:w-[540px] flex flex-col justify-between p-2 sm:p-6 lg:p-8 flex-shrink-0" data-purpose="login-auth-form">
                
                <div>
                    <!-- Top Section: Brand Row -->
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center p-1.5 shadow-sm overflow-hidden flex-shrink-0">
                            <img src="<?= app_logo() ?>" alt="Logo Universitas Bumigora" class="w-full h-full object-contain">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold tracking-tight text-slate-900 leading-tight">UNIVERSITAS BUMIGORA</span>
                            <span class="text-xs text-slate-500 font-medium leading-tight">Sistem Informasi Akademik (SISKA)</span>
                        </div>
                    </div>

                    <!-- Heading Group -->
                    <div class="mt-6 sm:mt-7">
                        <h1 class="text-2xl sm:text-[30px] font-extrabold text-slate-900 tracking-tight leading-tight">
                            Selamat Datang Kembali
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                            Silakan masukkan identitas akun resmi kampus Anda
                        </p>
                    </div>

                    <!-- Flash Message / Alert Notification -->
                    <?php if ($this->session->flashdata('pesan')): ?>
                        <div class="mt-4 p-3.5 rounded-xl text-xs font-medium flex items-start gap-2.5 <?= ($this->session->flashdata('tipe') == 'danger' || strpos(strtolower($this->session->flashdata('pesan')), 'salah') !== false || strpos(strtolower($this->session->flashdata('pesan')), 'belum') !== false) ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' ?>">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 text-sm <?= ($this->session->flashdata('tipe') == 'danger' || strpos(strtolower($this->session->flashdata('pesan')), 'salah') !== false) ? 'text-rose-600' : 'text-emerald-600' ?>"></i>
                            <div><?= $this->session->flashdata('pesan'); ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Form Content -->
                    <?= form_open('login', array('id' => 'siska-login-form', 'class' => 'mt-6 space-y-4')); ?>
                        
                        <!-- Field / Username -->
                        <div class="space-y-1.5" data-purpose="input-group-username">
                            <label class="block text-xs font-bold text-slate-800" for="username">
                                Username
                            </label>
                            <div class="relative">
                                <input class="w-full h-11 pl-3.5 pr-10 rounded-lg border <?= form_error('username') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-200 hover:border-slate-300 focus:border-blue-600 focus:ring-blue-600' ?> text-slate-900 bg-white text-sm focus:ring-1 transition-all outline-none" id="username" name="username" placeholder="Masukkan NIM atau NIDN..." required type="text" autocomplete="off" value="<?= set_value('username') ?>">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-user text-sm"></i>
                                </div>
                            </div>
                            <?php if (form_error('username')): ?>
                                <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('username'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Field / Password -->
                        <div class="space-y-1.5" data-purpose="input-group-password">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-800" for="password">
                                    Kata Sandi
                                </label>
                                <a class="text-xs text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-colors" href="<?= site_url('lupa_password') ?>">
                                    Lupa Password?
                                </a>
                            </div>
                            <div class="relative">
                                <input class="w-full h-11 pl-3.5 pr-10 rounded-lg border <?= form_error('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-200 hover:border-slate-300 focus:border-blue-600 focus:ring-blue-600' ?> text-slate-900 bg-white text-sm focus:ring-1 transition-all outline-none" id="password" name="password" placeholder="••••••••••••••" required type="password" value="<?= set_value('password') ?>">
                                <button aria-label="Lihat kata sandi" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors" id="togglePassword" type="button">
                                    <i class="fa-solid fa-lock text-sm" id="lockIcon"></i>
                                </button>
                            </div>
                            <?php if (form_error('password')): ?>
                                <p class="text-[11px] font-medium text-rose-600 mt-1"><?= form_error('password'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Utility Row (Remember Me) -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 transition">
                                <span class="text-xs font-medium text-slate-700">Ingat saya</span>
                            </label>
                        </div>

                        <!-- Field / Hak Akses -->
                        <div class="space-y-1.5" data-purpose="input-group-role">
                            <label class="block text-xs font-bold text-slate-800" for="userRole">
                                Hak Akses / Peran
                            </label>
                            <div class="relative">
                                <select class="w-full h-11 py-2.5 pl-3.5 pr-10 rounded-lg border <?= form_error('status') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-200 hover:border-slate-300 focus:border-blue-600 focus:ring-blue-600' ?> text-slate-800 bg-white text-sm focus:ring-1 appearance-none transition-all outline-none cursor-pointer" id="userRole" name="status" required>
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

                        <!-- Action Buttons -->
                        <div class="pt-3 space-y-2.5">
                            <!-- Button / Log In -->
                            <button class="w-full h-[46px] py-2.5 px-4 rounded-lg bg-[#2563eb] hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold tracking-wide shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer" type="submit" name="submit" value="Log In">
                                <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                                <span>Log In ke SISKA</span>
                            </button>

                            <!-- Button / Validasi -->
                            <button type="button" onclick="openPaymentModal()" class="w-full h-[46px] py-2.5 px-4 rounded-lg bg-[#ef4444] hover:bg-red-600 active:bg-red-700 text-white text-[15px] font-bold tracking-wide shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer text-center">
                                <i class="fa-solid fa-receipt text-sm"></i>
                                <span>Cek Validasi Pembayaran!</span>
                            </button>
                        </div>

                    <?= form_close(); ?>
                </div>

                <!-- Bottom Section: Quick Link & Footer Meta -->
                <div class="mt-8 pt-4 border-t border-slate-100 space-y-3">
                    <!-- Quick Link KRS -->
                    <a class="w-full h-11 px-3.5 bg-[#f8fafc] hover:bg-slate-100 active:bg-slate-200 border border-slate-200/90 rounded-lg flex items-center justify-between text-xs font-semibold text-slate-800 hover:text-blue-600 transition-colors group" href="https://www.youtube.com/watch?v=HgRVzuzvFf4" target="_blank" rel="noopener noreferrer">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                            <span>Cara Mengurus KRS MABA</span>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-blue-600 transition-colors"></i>
                    </a>

                    <!-- Footer Meta -->
                    <div class="flex items-center justify-between text-[11px] text-slate-400 px-0.5">
                        <span>&copy; <?= date('Y') ?> Universitas Bumigora</span>
                        <span class="hidden sm:inline">SISKA v2.0 Terintegrasi</span>
                    </div>
                </div>

            </section>
            <!-- END: Left Column -->

            <!-- BEGIN: Right Column (Col / Hero Showcase) -->
            <aside class="hidden lg:flex flex-1 relative rounded-[20px] overflow-hidden bg-slate-900 min-h-[580px] xl:min-h-[720px] shadow-inner" aria-label="Gedung Universitas Bumigora">
                <img src="<?= base_url('assets/siska/img/gedung_ubg_hero.jpg') ?>?v=<?= file_exists(FCPATH . 'assets/siska/img/gedung_ubg_hero.jpg') ? filemtime(FCPATH . 'assets/siska/img/gedung_ubg_hero.jpg') : '1' ?>" alt="Gedung Kampus Universitas Bumigora" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent pointer-events-none"></div>
            </aside>
            <!-- END: Right Column -->

        </div>
    </main>
    <!-- END: Main App Container -->

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
                        <input type="text" id="input-nim" inputmode="numeric" pattern="[0-9]+" name="nim" required class="flex-grow py-2.5 px-3.5 rounded-lg border border-slate-300 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" minlength="8" maxlength="20" placeholder="Masukkan digit NIM..." title="Masukkan digit NIM dengan benar">
                        <button type="submit" class="px-5 py-2.5 bg-[#ef4444] hover:bg-red-600 active:bg-red-700 text-white rounded-lg text-sm font-bold transition flex items-center gap-1.5 cursor-pointer">
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
                    lockIcon.classList.add('fa-lock-open');
                } else {
                    passwordInput.setAttribute('type', 'password');
                    lockIcon.classList.remove('fa-lock-open');
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

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
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
