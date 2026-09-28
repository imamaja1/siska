<?php
$fs  = isset($font_size) ? (float) $font_size : 8;
$kop = bodo_kop($mahasiswa->nim);

// Hitung total SKS/SKSN & IPK bila belum disediakan oleh pemanggil.
if (!isset($total_sks) || !isset($total_sksn) || !isset($ipk)) {
    $total_sks = 0;
    $total_sksn = 0;
    foreach ((array) $data as $key) {
        foreach ($key['data_nilai'] ?? [] as $row) {
            if (isset($row['semester']) && ($row['semester'] <= $semester || $row['semester'] == 'K')) {
                $total_sks += $row['sks_teori'] + $row['sks_praktek'] + $row['sks_praktikum'];
                $total_sksn += $row['sksn'];
            }
        }
    }
    $ipk = $total_sks != 0 ? $total_sksn / $total_sks : 0;
}

// Nilai satu sel. Mata kuliah pada semester yang belum ditempuh memakai $fallback.
$nilai = function ($row, $semester, $key, $fallback) {
    $ditempuh = isset($row['semester']) && ($row['semester'] <= $semester || $row['semester'] == 'K');
    return e($ditempuh ? $row[$key] : $fallback);
};

// Render tabel daftar mata kuliah untuk rentang semester [$dari..$sampai].
$tabelSemester = function ($data, $dari, $sampai, $semester, $nilai) use ($fs) {
    ob_start(); ?>
    <table border="1" class="items" width="100%" style="font-size: <?= $fs ?>pt; border-collapse: collapse;" cellpadding="1">
        <thead>
            <tr>
                <th width="20" style="text-align: center;">No.</th>
                <th width="30" style="text-align: center;">KODE MK</th>
                <th width="110" style="text-align: center;">MATAKULIAH</th>
                <th width="20" style="text-align: center;">SKS</th>
                <th width="30" style="text-align: center;">GRADE</th>
                <th width="20" style="text-align: center;">SKSN</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = $dari; $i <= $sampai; $i++) : ?>
                <?php if (isset($data[$i]['data_nilai'])) : ?>
                    <?php $j = 1; foreach ($data[$i]['data_nilai'] as $row) : ?>
                        <tr>
                            <td align="center"><?= $j++ ?>.</td>
                            <td align="center"><?= e($row['kode_matakuliah']) ?></td>
                            <td><?= e($row['nama_matakuliah']) ?></td>
                            <td align="center"><?= $nilai($row, $semester, 'sks', '0') ?></td>
                            <td align="center"><?= $nilai($row, $semester, 'grade', '-') ?></td>
                            <td align="center"><?= $nilai($row, $semester, 'sksn', '0') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr><td colspan="6" style="border: none; height: 3px; line-height: 3px;"></td></tr>
                <?php endif; ?>
            <?php endfor; ?>
        </tbody>
    </table>
    <?php return ob_get_clean();
};
?>
<html>
    <head>
        <style>
            body {
                font-family: sans-serif;
                font-size: <?= $fs ?>pt;
                line-height: 1.1;
            }

            td {
                vertical-align: top;
            }

            .items td {
                border: 0.1mm solid #000000;
            }

            .garis_bawah {
                text-decoration: underline;
            }

            h4 {
                text-align: center;
                margin: 4px 0;
                white-space: normal;
                word-wrap: break-word;
            }
        </style>
    </head>
    <body>

        <!-- Start Header Petikan Nilai -->
        <hr style="border:5px solid black">
        <h4>PETIKAN NILAI MAHASISWA SEMESTER <?= $tahun_akademik->semester != 1 ? 'GENAP' : 'GANJIL'; ?>
            TA. <?= e($tahun_akademik->ta) ?> ANGKATAN 20<?= e(substr($mahasiswa->nim, 0, 2)) ?></h4>
        <table width="100%" style="font-size: <?= $fs ?>pt; border-collapse: collapse;" cellpadding="2">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td><b>Nama Mahasiswa</b></td>
                            <td><b>:</b></td>
                            <td><?= e($mahasiswa->nama_mahasiswa) ?></td>
                        </tr>
                        <tr>
                            <td><b>NIM</b></td>
                            <td><b>:</b></td>
                            <td><?= e($mahasiswa->nim) ?></td>
                        </tr>
                    </table>
                </td>
                <td width="10%">&nbsp;</td>
                <td>
                    <table>
                        <tr>
                            <td><b>Jurusan</b></td>
                            <td><b>:</b></td>
                            <td><?= strtoupper(e($prodi->nama_program_studi)) ?></td>
                        </tr>
                        <tr>
                            <td><b>Fakultas</b></td>
                            <td><b>:</b></td>
                            <td><?= e($kop['nama_fakultas']) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- End Header Petikan Nilai -->
        <!-- Start Content Petikan Nilai -->
        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" style="vertical-align: top; padding-right: 3px;">
                    <?= $tabelSemester($data, 0, 4, $semester, $nilai) ?>
                </td>
                <td width="50%" style="vertical-align: top; padding-left: 3px;">
                    <?= $tabelSemester($data, 5, 7, $semester, $nilai) ?>
                    <div style="padding-left: 5px; margin-top: 8px;">
                        <table width="100%" style="font-weight: bold; font-size: <?= $fs ?>pt; border-collapse: collapse;"
                               cellpadding="1" align="center">
                            <tr>
                                <td width="15%" rowspan="2" valign="middle" align="center">IPK</td>
                                <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                                <td width="25%" style="border-bottom:1px solid black;" align="center">&#931; SKSN</td>
                                <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                                <td width="30%" style="border-bottom:1px solid black;" align="center"><?= e($total_sksn) ?></td>
                                <td width="5%" rowspan="2" valign="middle" align="center">=</td>
                                <td width="15%" rowspan="2" valign="middle"
                                    align="center"><?= e(number_format($ipk, 2, '.', '')) ?></td>
                            </tr>
                            <tr>
                                <td align="center">&#931; SKS</td>
                                <td align="center"><?= e($total_sks) ?></td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                        <table width="100%" style="font-size: <?= $fs ?>pt; border-collapse: collapse; margin-top: 5px; margin-bottom: 5px;" cellpadding="1" align="center">
                            <tr>
                                <td style="padding-top: 15px;">Mataram, <?= tgl_indo(date('d F Y')) ?></td>
                            </tr>
                            <tr>
                                <td>Dekan,</td>
                            </tr>
                            <tr>
                                <td><img style="height: 50px;" src="<?= e(!empty($ttd) && file_exists(FCPATH . 'assets/signature-dosen/' . $ttd) ? base_url('assets/signature-dosen/'.rawurlencode($ttd)) : base_url('assets/gambar/notfound.png')) ?>"></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="garis_bawah"><?= e($kop['dekan']) ?></div>
                                    NIK: <?= e($kop['nik']) ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <!-- End Content Petikan Nilai -->
    </body>
</html>