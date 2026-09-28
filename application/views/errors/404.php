<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>404 - Halaman Tidak Ditemukan | SISKA Universitas Bumigora</title>

    <link rel="icon" type="image/png" href="<?= app_logo() ?>">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Roboto', system-ui, -apple-system, sans-serif; }
        .code-404 {
            font-weight: 700;
            letter-spacing: -0.04em;
            background: linear-gradient(180deg, #0077B6 0%, #578EF5 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 flex items-center justify-center px-4 py-10">

    <main class="w-full max-w-lg">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-10 text-center">

            <!-- Brand -->
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-3 mb-6">
                <img src="<?= app_logo() ?>" alt="Logo Universitas Bumigora" class="h-11 w-11 object-contain rounded-lg border border-slate-200 bg-white p-1">
                <div class="text-left leading-tight">
                    <div class="text-[10px] uppercase tracking-wider font-semibold text-slate-500">Universitas Bumigora</div>
                    <div class="text-base font-bold text-slate-900 tracking-tight">SISKA</div>
                </div>
            </a>

            <div class="code-404 text-7xl sm:text-8xl leading-none">404</div>

            <h1 class="mt-3 text-xl sm:text-2xl font-bold text-slate-900">Halaman Tidak Ditemukan</h1>
            <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                Maaf, halaman yang Anda cari tidak tersedia. Mungkin alamatnya salah, sudah dipindahkan,
                atau Anda tidak memiliki akses ke halaman tersebut.
            </p>

            <div class="mt-7 flex flex-col sm:flex-row gap-2.5 justify-center">
                <button type="button" onclick="window.history.back();"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
                <a href="<?= base_url('/') ?>"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-[#0077B6] text-white text-sm font-semibold hover:bg-[#006199] transition">
                    <i class="bi bi-house-door"></i> Ke Beranda
                </a>
            </div>
        </div>

        <p class="mt-5 text-center text-[11px] text-slate-400">
            &copy; <?= date('Y') ?> Universitas Bumigora &middot; Sistem Informasi Akademik (SISKA)
        </p>
    </main>

</body>
</html>
