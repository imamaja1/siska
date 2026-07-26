<div class="box box-primary flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td width="20%"><strong>NIM</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td><?= e($data->nim) ?></td>
                </tr>
                <tr>
                    <td><strong>NISN</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nisn) ?></td>
                </tr>
                <tr>
                    <td><strong>NIK</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nik) ?></td>
                </tr>
                <tr>
                    <td><strong>NPM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->npm) ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Mahasiswa</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nama_mahasiswa) ?></td>
                </tr>
                <tr>
                    <td><strong>Tempat/Tgl. lahir</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->tempat_lahir) ?>, <?= date('d-m-Y', strtotime($data->tanggal_lahir)) ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->alamat) ?>, <?= e($data->kota) ?></td>
                </tr>
                <tr>
                    <td><strong>Propinsi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->propinsi) ?></td>
                </tr>
                <tr>
                    <td><strong>Jenis Kelamin</strong></td>
                    <td><strong>:</strong></td>
                    <td>
                        <?php
                        $jenis_kelamin = $data->jenis_kelamin;
                        if ($jenis_kelamin == "P") {
                            echo "Perempuan";
                        } else if ($jenis_kelamin == "L") {
                            echo "Laki-laki";
                        } else {
                            echo "";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Agama</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->agama) ?></td>
                </tr>
                <tr>
                    <td><strong>Kewarganegaraan</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->kewarganegaraan) ?></td>
                </tr>
                <tr>
                    <td><strong>No. Telepon</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->telepon) ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Instansi/Tempat Kerja</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->nama_instansi) ?></td>
                </tr>
                <tr>
                    <td><strong>Email Kampus</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->email) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-header">
        <h3 class="box-title"><b>Detail Orang Tua</b></h3>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td width="20%"><strong>Nama Ayah</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td><?= strtoupper(e($data->nama_ayah)) ?></td>
                </tr>
                <tr>
                    <td><strong>Agama Ayah</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->agama_ayah) ?></td>
                </tr>
                <tr>
                    <td><strong>Pekerjaan Ayah</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->pekerjaan_ayah) ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= strtoupper(e($data->nama_ibu)) ?></td>
                </tr>
                <tr>
                    <td><strong>Agama Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->agama_ibu) ?></td>
                </tr>
                <tr>
                    <td><strong>Pekerjaan Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->pekerjaan_ibu) ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->alamat_orangtua) ?>, <?= e($data->kota_orangtua) ?> </td>
                </tr>
                <tr>
                    <td><strong>Propinsi Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->propinsi_orangtua) ?> </td>
                </tr>
                <tr>
                    <td><strong>No. Telepon Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= e($data->telepon_orangtua) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
