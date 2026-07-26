<div class="panel box box-solid flat">
    <div class="alert  alert-dismissible flat" style="height: 30px; padding-top: 4px; font-size: 15px; background-color: #3c8dbc; color: white;">
        <b>Detail Data Mahasiswa</b>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="tabel" width="100%">
                <tr style="height: 30px;">
                    <td width="20%"><strong>NIM</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td><?= $data->nim ?></td>
                    <td rowspan="8">
                        <div style="width: 170px; height: 220px; margin: auto;">
                            <?php
                            $foto = $data->foto;
                            $jk = $data->jenis_kelamin;
                            if (empty($foto)) {
                                if ($jk == "L") {
                                    ?>
                                    <img style="width: 170px; height: 220px; border: #899bc1 solid 2px; margin: auto;" src="<?= base_url('assets/foto/L.png'); ?>" alt="Foto">
                                <?php } else { ?>
                                    <img style="width: 170px; height: 220px; border:#899bc1  solid 2px; margin: auto;" src="<?= base_url('assets/foto/P.png'); ?>" alt="Foto">
                                <?php } ?>
                            <?php } else { ?>
                                <img style="width: 170px; height: 220px; border:#899bc1  solid 2px; margin: auto;" src="<?= base_url('assets/foto/' . $data->foto); ?>" alt="Foto">
                            <?php } ?>

                        </div>
                    </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>NPM</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->npm ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Nama Mahasiswa</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->nama_mahasiswa ?></td>
                </tr>

                <tr style="height: 30px;">
                    <td><strong>Tempat/Tgl. lahir</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->tempat_lahir ?>, <?= date('d-m-Y', strtotime($data->tanggal_lahir)) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Alamat</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->alamat ?>, <?= $data->kota ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Propinsi</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->propinsi ?></td>
                </tr>
                <tr style="height: 30px;">
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
                <tr style="height: 30px;">
                    <td><strong>Agama</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->agama ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Kewarganegaraan</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->kewarganegaraan ?></td>
                    <td align="center">
                        <?php
                        if (empty($foto) or ( $foto == "P.png") or ( $foto == "L.png")) {
                            echo "<b> Belum ada foto</b>";
                        } else {
                            echo "<b>" . $data->nama_mahasiswa . "</b>";
                        }
                        ?>

                    </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>No. Telepon</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->telepon ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Nama Instansi/Tempat Kerja</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->nama_instansi ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Email Kampus</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->email ?></td>
                </tr>

            </table>
        </div>
    </div>
</div>
<div class="panel box box-solid flat">
    <div class="alert alert-dismissible flat" style="height: 30px; padding-top: 4px; font-size: 15px; background-color: #3c8dbc; color: white;">
        <b>Detail Orang Tua</b>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="tabel" width="100%">
                <tr style="height: 30px;">
                    <td width="20%"><strong>Nama Ayah</strong></td>
                    <td width="1%"><strong>:</strong></td>
                    <td><?= strtoupper($data->nama_ayah) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Agama Ayah</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->agama_ayah ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Pekerjaan Ayah</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->pekerjaan_ayah ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Nama Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= strtoupper($data->nama_ibu) ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Agama Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->agama_ibu ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Pekerjaan Ibu</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->pekerjaan_ibu ?></td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Alamat Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->alamat_orangtua ?>, <?= $data->kota_orangtua ?> </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>Propinsi Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->propinsi_orangtua ?> </td>
                </tr>
                <tr style="height: 30px;">
                    <td><strong>No. Telepon Orang Tua</strong></td>
                    <td><strong>:</strong></td>
                    <td><?= $data->telepon_orangtua ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>