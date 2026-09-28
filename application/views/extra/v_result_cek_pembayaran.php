<?php if (!empty($data)) : ?>
    <?php
    $angkatan = (int) substr($nim, 0, 2);
    $hanya_ukt = $angkatan >= 25; // angkatan 25 ke atas: UKT saja (UKT = SPP)
    $badge = function ($val) {
        if ($val == '1') {
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800"><i class="fa-solid fa-check text-[10px]"></i> Lunas</span>';
        }
        if ($val == '2') {
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">Dispen</span>';
        }
        return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800"><i class="fa-solid fa-xmark text-[10px]"></i> Belum</span>';
    };
    ?>
    <div class="mt-3 bg-slate-50 border border-slate-200 rounded-xl p-4 text-slate-800">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200 text-xs">
            <div>
                <p class="font-bold text-slate-900"><?= e($mahasiswa->nama_mahasiswa ?? $nim) ?></p>
                <p class="text-[11px] text-slate-500">NIM: <?= e($nim) ?></p>
                <p class="text-[11px] text-slate-500">Semester <?= e($data->semester ?? '-') ?> (TA <?= e($ta->tahun_akademik ?? '-') ?>)</p>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold <?= $data->status_perkuliahan == 'A' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' ?>">
                Status: <?= e($data->status_perkuliahan == 'A' ? 'Aktif' : $data->status_perkuliahan) ?>
            </span>
        </div>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-100 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-2 px-3">Jenis Pembayaran</th>
                        <th class="py-2 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($hanya_ukt) : ?>
                        <tr>
                            <td class="py-2.5 px-3 font-medium text-slate-800">UKT</td>
                            <td class="py-2.5 px-3 text-center"><?= $badge($data->pembayaran_spp) ?></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td class="py-2.5 px-3 font-medium text-slate-800">SPP</td>
                            <td class="py-2.5 px-3 text-center"><?= $badge($data->pembayaran_spp) ?></td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-3 font-medium text-slate-800">SKS</td>
                            <td class="py-2.5 px-3 text-center"><?= $badge($data->pembayaran_sks) ?></td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-3 font-medium text-slate-800">LAB</td>
                            <td class="py-2.5 px-3 text-center"><?= $badge($data->pembayaran_lab) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!$hanya_ukt) : ?>
            <p class="text-[11px] text-slate-500 italic mt-2.5">
                * Abaikan status pembayaran LAB jika tidak mengambil matakuliah praktikum.
            </p>
        <?php endif; ?>
    </div>
<?php else : ?>
    <div class="mt-3 p-4 bg-amber-50 border border-amber-200 rounded-xl text-center text-amber-900 text-xs">
        <i class="fa-solid fa-circle-exclamation text-amber-600 text-lg mb-1 block"></i>
        <p class="font-bold">Data Tidak Ditemukan</p>
        <p class="text-[11px] text-amber-700 mt-0.5">
            Status perkuliahan/pembayaran untuk NIM <strong>"<?= e($nim) ?>"</strong> pada semester ini belum tersedia. Pastikan NIM yang dimasukkan sudah benar.
        </p>
    </div>
<?php endif; ?>
