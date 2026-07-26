<html>
    <head>
        <title>Cetak Mahasiswa</title>
    </head>
    <body style="font-family: arial;">
        <div style="text-align: center;"><b>BIODATA MAHASISWA</b></div>
        <br><br>
        <b>I. IDENTITAS MAHASISWA</b>
        <br>
        <table>
            <tr>
                <td width="300">a. Nomor Induk Mahasiswa</td>
                <td>:</td>
                <td><?= e($mahasiswa->nim) ?></td>
            </tr>
            <tr>
                <td>b. Nomor Pokok Mahasiswa</td>
                <td>:</td>
                <td><?= e($mahasiswa->npm) ?></td>
            </tr>
            <tr>
                <td>c. Nama Mahasiswa</td>
                <td>:</td>
                <td><?= e($mahasiswa->nama_mahasiswa) ?></td>
            </tr>
            <tr>
                <td>d. Jurusan</td>
                <td>:</td>
                <?php
                $nama_jurusan = substr($mahasiswa->nim, 2, 2);
                if ($nama_jurusan == "10") {
                    $singkatan = "TEKNIK INFORMATIKA";
                } else if ($nama_jurusan == "00") {
                    $singkatan = "MANAJEMEN INFORMATIKA";
                } else if ($nama_jurusan == "09") {
                    $singkatan = "DESAIN VISUAL STUDIO";
                } else {
                    $singkatan = "Jurusan tidak diketahui";
                }
                ?>
                <td><?= $singkatan; ?></td>
            </tr>
            <tr>
                <td>e. Nomor Pendaftaran</td>
                <td>:</td>
                <td><?= e($mahasiswa->nomor_pendaftaran) ?></td>
            </tr>
            <tr>
                <td>f. Nomor Pendaftaran Ulang</td>
                <td>:</td>
                <td><?= e($mahasiswa->nomor_pendaftaran_ulang) ?></td>
            </tr>
            <tr>
                <td>g. Tempat/Tgl Lahir</td>
                <td>:</td>
                <td><?= e($mahasiswa->tempat_lahir) ?>, <?= date('d-m-Y', strtotime($mahasiswa->tanggal_lahir)) ?></td>
            </tr>
            <tr>
                <td>h. Alamat Lengkap</td>
                <td>:</td>
                <td><?= e($mahasiswa->alamat) ?>, <?= e($mahasiswa->kota) ?> - <?= e($mahasiswa->propinsi) ?>.</td>
            </tr>
            <tr>
                <td>i. Telepon</td>
                <td>:</td>
                <td><?= e($mahasiswa->telepon) ?></td>
            </tr>
            <tr>
                <td>j. Jenis Kelamin</td>
                <td>:</td>
                <td><?php
                    $jenis_kelamin = $mahasiswa->jenis_kelamin;
                    if ($jenis_kelamin == "L") {
                        echo "LAKI-LAKI";
                    } else {
                        echo "PEREMPUAN";
                    }
                    ?></td>
            </tr>
            <tr>
                <td>k. Agama</td>
                <td>:</td>
                <td><?= e($mahasiswa->agama) ?></td>
            </tr>
            <tr>
                <td>l. Golongan Darah</td>
                <td>:</td>
                <td><?= e($mahasiswa->golongan_darah) ?></td>
            </tr>
            <tr>
                <td>m. Kewarganegaraan</td>
                <td>:</td>
                <?php
                $warga_negara = $mahasiswa->kewarganegaraan;
                if ($warga_negara == "WNI") {
                    $negara = "WNI (Warga Negara Indonesia)";
                } else {
                    $negara = "WNA (Warga Negara Asing)";
                }
                ?>
                <td><?= $negara; ?></td>
            </tr>
            <tr>
                <td>n. Nama Instansi (Bagi yang bekerja)</td>
                <td>:</td>
                <td><?= e($mahasiswa->nama_instansi) ?></td>
            </tr>
            <tr>
                <td>o. Email</td>
                <td>:</td>
                <td><?= e($mahasiswa->email) ?></td>
            </tr>

        </table>
        <b>II. DATA ORANG TUA</b>
        <table>
            <tr>
                <td width="300">a. Nama Ayah</td>
                <td>:</td>
                <td><?= e($mahasiswa->nama_ayah) ?></td>
            </tr>
            <tr>
                <td>b. Agama Ayah</td>
                <td>:</td>
                <td><?= e($mahasiswa->agama_ayah) ?></td>
            </tr>
            <tr>
                <td>c. Pekerjaan Ayah</td>
                <td>:</td>
                <td><?= e($mahasiswa->pekerjaan_ayah) ?></td>
            </tr>
            <tr>
                <td>d. Nama Ibu</td>
                <td>:</td>
                <td><?= e($mahasiswa->nama_ibu) ?></td>
            </tr>
            <tr>
                <td>e. Agama Ibu</td>
                <td>:</td>
                <td><?= e($mahasiswa->agama_ibu) ?></td>
            </tr>
            <tr>
                <td>f. Pekerjaan Ibu</td>
                <td>:</td>
                <td><?= e($mahasiswa->pekerjaan_ibu) ?></td>
            </tr>
            <tr>
                <td>g. Alamat Lengkap Orang Tua</td>
                <td>:</td>
                <td><?= e($mahasiswa->alamat_orangtua) ?>, <?= e($mahasiswa->kota_orangtua) ?> - <?= e($mahasiswa->propinsi_orangtua) ?>.</td>
            </tr>
        </table>

    </body>
</html>
