<html>
<head>
    <style>
        body {font-family: sans-serif;
            font-size: 8pt;
        }
        p.keterangan {
            margin: 0pt;
            font-family: sans-serif;
            font-size: 8pt;
            font-weight:bold;
            text-decoration:underline;
        }
        td { vertical-align: top; }
        .items td {
            border: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
        }
        td.right {
            text-align: right;
        }
        .items td.blanktotal {
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }
        .footer {
            font-family: sans-serif;
            font-size: 8pt;
        }
        .garis_bawah {
            text-decoration:underline;
        }
        h4 {
            text-align:center;
        }
    </style>
</head>
<body>
<h4>KARTU RENCANA STUDI KULIAH PROGRAM ALIH TAHUN (KPAT) <br />PROGRAM STUDI <?= strtoupper($prodi->nama_program_studi) ?><br />
    SEMESTER <?= $tahun_akademik->semester % 2 == 0 ? "GENAP" : "GANJIL"?> TAHUN AKADEMIK <?= $tahun_akademik->tahun_akademik ?></h4>
<table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td><b>Nama Mahasiswa</b></td>
                    <td><b>:</b></td>
                    <td><?= $mahasiswa->nama_mahasiswa  ?></td>
                </tr>
                <tr>
                    <td><b>NIM</b></td>
                    <td><b>:</b></td>
                    <td><?= $mahasiswa->nim  ?></td>
                </tr>
                <tr>
                    <td><b>Semester</b></td>
                    <td><b>:</b></td>
                    <td><?= $semester ?></td>
                </tr>
                <tr>
                    <td><b>Dosen Wali</b></td>
                    <td><b>:</b></td>
                    <td><?= $dosen_wali->nama_dosen  ?></td>
                </tr>
            </table>
        </td>
        <td width="10%">&nbsp;</td>
        <td>
            <table align="right">
                <tr>
                    <td><b>Alamat Sekarang</b></td>
                    <td><b>:</b></td>
                    <td><?= $mahasiswa->alamat.", ".$mahasiswa->kota."<br>".$mahasiswa->propinsi  ?> </td>
                </tr>
                <tr>
                    <td><b>Telp/HP</b></td>
                    <td><b>:</b></td>
                    <td><?= $mahasiswa->telepon  ?></td>
                </tr>
                <tr>
                    <td><b>Alamat Ortu/Wali/Libur</b></td>
                    <td><b>:</b></td>
                    <td><?= $mahasiswa->alamat_orangtua.", ".$mahasiswa->kota_orangtua."<br>".$mahasiswa->propinsi_orangtua  ?></td>
                </tr>
                <tr>
                    <td><b>Telp</b></td>
                    <td><b>:</b></td>
                    <td><?= $mahasiswa->telepon_orangtua  ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End Header KRS -->
<br />
<!-- Start Content KRS -->
<table border="1" class="items" width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <th colspan="9" align="center">SEMESTER <?= $semester ?> </th>
    </tr>
    <tr>
        <th rowspan="2" width="5%">NO</th>
        <th rowspan="2" width="13%">KODE MK</th>
        <th rowspan="2" width="40%">MATAKULIAH</th>
        <th colspan="3" align="center" width="18%">SKS</th>
        <th rowspan="2" align="center" width="6%">UTS</th>
        <th rowspan="2" align="center" width="6%">UAS</th>
        <th rowspan="2" align="center" width="6%">KET</th>
    </tr>
    <tr>
        <th align="center">T</th>
        <th align="center">PK</th>
        <th align="center">PT</th>
    </tr>
    <?php
    $teori = 0;
    $praktek = 0;
    $praktikum = 0;
    $i=1; foreach ($data_matakuliah as $row) : ?>
        <tr>
            <td style="text-align: center;"><?= $i++."." ?></td>
            <td style="text-align: center;"><?= $row->kode_matakuliah ?></td>
            <td><?= $row->nama_matakuliah ?></td>
            <td style="text-align: center;"><?= $row->sks_teori == (0) ? "" : $row->sks_teori ?></td>
            <td style="text-align: center;"><?= $row->sks_praktek == (0) ? "" : $row->sks_praktek ?></td>
            <td style="text-align: center;"><?= $row->sks_praktikum == (0) ? "" : $row->sks_praktikum?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
        </tr>
        <?php
        $teori = $teori + $row->sks_teori;
        $praktek = $praktek + $row->sks_praktek;
        $praktikum = $praktikum + $row->sks_praktikum;
        $jumlah_sks = $teori+$praktek+$praktikum;
        ?>
    <?php endforeach; ?>
    <tr class="dark">
        <td colspan="3" align="center">JUMLAH</td>
        <td align="center"><?= $teori; ?></td>
        <td align="center"><?= $praktek; ?></td>
        <td align="center"><?= $praktikum; ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>
</table>
<!-- End Content KRS -->
<!-- Start Footer KRS -->
<table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <td valign="top" width="50%">
            <table>
                <tr>
                    <td>Beban SKS KPAT maksimum</td>
                    <td>:</td>
                    <td>
                        <?= $maksimum_sks ?>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
                <tr>
                    <td width="61%">Jumlah kredit yang diambil</td>
                    <td width="3%">:</td>
                    <td width="20%" class="right">
                        <?php
                        /* Menampilkan jumlah kredit yang diambil. Khusus semester 1 karena menggunakan sistem paket maka langsung ditampilkan jumlah sks
                         * dari matakuliah yang ada di semester 1.
                         */
                        $jumlah_kredit_yg_diambil = $jumlah_sks;
                        echo $jumlah_kredit_yg_diambil;
                        ?>
                    </td>
                    <td width="16%" class="right">SKS</td>
                </tr>
                <tr>
                    <td>Jumlah kredit yang batal</td>
                    <td>:</td>
                    <td class="right">
                        <?php
                        $jumlah_kredit_yg_batal = 0;
                        echo $jumlah_kredit_yg_batal;
                        ?>
                    </td>
                    <td align="right">SKS</td>
                </tr>
                <tr>
                    <td>Jumlah penambahan kredit</td>
                    <td>:</td>
                    <td style="border-bottom: 1px solid #000000;" class="right">
                        <?php
                        $jumlah_penambahan_kredit = 0;
                        echo $jumlah_penambahan_kredit;
                        ?>
                    </td>
                    <td style="border-bottom: 1px solid #000000;" class="right">SKS</td>
                </tr>
                <tr>
                    <td>Jumlah kredit terakhir</td>
                    <td>:</td>
                    <td class="right">
                        <?php
                        $jumlah_kredit_terakhir = ($jumlah_kredit_yg_diambil - $jumlah_kredit_yg_batal) + $jumlah_penambahan_kredit;
                        echo $jumlah_kredit_terakhir;
                        ?>
                    </td>
                    <td class="right">SKS</td>
                </tr>
            </table></td>
    </tr>
</table>
<br />
<table width="100%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2">
    <tr>
        <td width="33%">&nbsp;</td>
        <td width="33%">&nbsp;</td>
        <td width="34%">Mataram, <?= tgl_indo(date('d F Y')) ?></td>
    </tr>
    <tr>
        <td>Ketua Jurusan</td>
        <td>&nbsp;</td>
        <td>Mahasiswa yang bersangkutan,</td>
    </tr>
    <tr>
        <td>
            <img style="height: 50px" src="<?= base_url('assets/signature_kaprodi/'.$kaprodi->tanda_tangan) ?>" />
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><div class="garis_bawah"><?= $kaprodi->nama_dosen ?></div>NIP/NIK: NIP/NIK: <?= $kaprodi->nik ?></td>
        <td>&nbsp;</td>
        <td><div class="garis_bawah"><?= $data_mahasiswa->nama_mahasiswa ?></div>NIM: <?= $data_mahasiswa->nim ?></td>
    </tr>
</table>
<p class="keterangan"><br />Keterangan:</p>
<ol class="footer">
    <li>Makna kode-kode:<br />
        <ol type="a">
            <li>Jenis SKS matakuliah, yaitu: <i>Teori</i> <strong>(T)</strong>, <i>Praktek</i> <strong>(PK)</strong>, <i>Praktikum</i> <strong>(PT)</strong>, <i>Ujian Tengah Semester</i> <strong>(UTS)</strong>, <i>Ujian Akhir Semester</i> <strong>(UAS)</strong>.</li>
            <li>Kode Kompetensi, yaitu: <i>Rekayasa Perangkat Lunak</i> <strong>(RPL)</strong>, <i>Jaringan Komputer</i> <strong>(JK)</strong>, M: <i>Multimedia</i> <strong>(M)</strong></li>
        </ol>
    </li>
    <li>Kartu ini dibuat rangkap tiga, antara lain:<br />
        a. Lembar <strong>merah</strong> untuk Akademik.&nbsp;&nbsp;b. Lembar <strong>putih</strong> untuk Mahasiswa.
    </li>
    <li>Kartu ini harus dibawa saat mengikuti <strong>Ujian Tengah Semester (UTS)</strong> dan <strong>Ujian Akhir Semester (UAS)</strong>, serta saat mengurus keperluan lainnya di STMIK Bumigora Mataram.</li>
    <li>Universitas Bumigora Mataram tidak bertanggungjawab atas hilangnya kartu ini.</li>
</ol>
<!-- End Footer KRS -->
<table border="1" class="items" width="70%" style="font-size: 8pt; border-collapse: collapse;" cellpadding="2" align="center">
    <tr>
        <th width="33%">PERPUSTAKAAN</th>
        <th width="34%">ADUM & KEUANGAN</th>
        <th width="33%">BAA</th>
    </tr>
    <tr>
        <td height="60px">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
