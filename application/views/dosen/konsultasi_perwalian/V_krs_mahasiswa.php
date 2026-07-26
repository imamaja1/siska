<?php if(count($data) > 0) : ?>
<div class="box box-primary flat">
    <div class="box-body flat table-responsive">
        <p style="text-align: center"><strong>KARTU RECANA STUDI (KRS) PROGRAM STUDI <?= strtoupper($prodi->nama_program_studi) ?> (<?= strtoupper($prodi->singkatan_program_studi) ?>)</strong></p>
        <p style="text-align: center"><strong>SEMESTER <?= e($tahun_akademik->semester % 2 == (0)? "GENAP" : "GANJIL") ?> <?= e($tahun_akademik->ta) ?></strong></p>
        <table class="table" width="100%">
            <tr>
                <td width="50%">
                    <table class="table">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <td> : <?= e($data_mahasiswa->nama_mahasiswa) ?></td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td> : <?= e($data_mahasiswa->nim) ?></td>
                        </tr>
                        <tr>
                            <th>Semester</th>
                            <td> : <?= e($semester) ?></td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table class="table">
                        <tr>
                            <th>Alamat Sekarang</th>
                            <td> : <?= e($data_mahasiswa->alamat) ?></td>
                        </tr>
                        <tr>
                            <th>No. Telp/HP</th>
                            <td> : <?= e($data_mahasiswa->telepon) ?></td>
                        </tr>
                        <tr>
                            <th>Alamat Orang Tua</th>
                            <td> : <?= e($data_mahasiswa->alamat_orangtua) ?></td>
                        </tr>
                        <tr>
                            <th>Telp</th>
                            <td> : <?= e($data_mahasiswa->telepon_orangtua) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div align="center">Tanda <b>&radic;</b> di kolom <b>B</b> atau <b>U</b> menandakan matakuliah yang dipilih.</div>
        <br>
        <table class="table demo-table">
            <thead>
            <tr>
                <th class="th-color" width="20" rowspan="2" style="padding-bottom: 25px;">
                    <center>NO.</center>
                </th>
                <th class="th-color" width="200" rowspan="2" style="padding-bottom: 25px;">
                    <center>KODE MK</center>
                </th>
                <th class="th-color" rowspan="2" style="padding-bottom: 25px;">
                    <center>MATAKULIAH</center>
                </th>
                <th class="th-color" colspan="3">
                    <center>SKS</center>
                </th>
                <th class="th-color" rowspan="2" style="padding-bottom: 25px;">
                    <center>B</center>
                </th>
                <th class="th-color" rowspan="2" style="padding-bottom: 25px;">
                    <center>U</center>
                </th>
            </tr>
            <tr>
                <th class="th-color">
                    <center>T</center>
                </th>
                <th class="th-color">
                    <center>PK</center>
                </th>
                <th class="th-color">
                    <center>PT</center>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            $t= 0;
            $pk= 0;
            $pt= 0;
            foreach ($data as $row) : ?>
                <tr>
                    <td>
                        <center><?= $i++ ?>.</center>
                    </td>
                    <td >
                        <center><?= e($row->kode_matakuliah) ?></center>
                    </td>
                    <td><?= e($row->nama_matakuliah) ?></td>
                    <td width="100"><center><?= e($row->sks_teori != 0 ? $row->sks_teori : '') ?></center></td>
                    <td width="100"><center><?= e($row->sks_praktek != 0 ? $row->sks_praktek : '') ?></center></td>
                    <td width="100"><center><?= e($row->sks_praktikum != 0 ? $row->sks_praktikum : '') ?></center></td>
                    <?php if ($row->status == "B") : ?>
                        <td width="100">
                            <center><b>&radic;</b></center>
                        </td>
                        <td width="100"></td>
                    <?php else : ?>
                        <td width="100"></td>
                        <td width="100">
                            <center><b>&radic;</b></center>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php
                $t = $t + $row->sks_teori;
                $pk = $pk + $row->sks_praktek;
                $pt = $pt + $row->sks_praktikum;
            endforeach;
            $total_sks = $t+$pk+$pt;
            ?>
            <tr class="th-color">
                <td colspan="3"><center><strong>Jumlah</strong></center></td>
                <td ><center><?=e($t) ?></center></td>
                <td ><center><?=e($pk) ?></center></td>
                <td ><center><?=e($pt) ?></center></td>
                <td colspan="2"><center></center></td>
            </tr>
            </tbody>
        </table>
    </div>
    <table class="table" width="100%">
        <tr>
            <td width="50%">
                <table class="table" width="100%">
                    <tr>
                        <td width="40%">IP Semester Lalu</td>
                        <td width="3%">:</td>
                        <td width="20%"><?= number_format($beban_sks['ip_semester_lalu'],2) ?></td>
                    </tr>
                    <tr>
                        <td width="40%">Beban SKS Semester Sekarang</td>
                        <td width="3%">:</td>
                        <td width="20%"><?= e($total_sks) ?></td>
                    </tr>
                    <tr>
                        <td width="40%">Beban Max. SKS Semester ini</td>
                        <td width="3%">:</td>
                        <td width="20%"><?= e($beban_sks['beban_sks']) ?></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table class="table" width="100%">
                    <tr>
                        <td width="40%">Jumlah kredit yang diambil</td>
                        <td width="3%">:</td>
                        <td width="20%"><?= e($total_sks) ?></td>
                    </tr>
                    <tr>
                        <td width="40%">Jumlah kredit yang dibatalkan</td>
                        <td width="3%">:</td>
                        <td width="20%">-</td>
                    </tr>
                    <tr>
                        <td width="40%">Jumlah kredit yang ditambah</td>
                        <td width="3%">:</td>
                        <td width="20%" style="border-bottom: 1px solid black;">-</td>
                    </tr>
                    <tr>
                        <td width="40%">Jumlah kredit terakhir</td>
                        <td width="3%">:</td>
                        <td width="20%"><?= e($total_sks) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<?php else: ?>
    <div class="callout callout-info flat">
        <h4>Informasi!</h4>

        <p>Mahasiswa ini belum melakukan pengisian KRS semester berjalan.</p>
    </div>
<?php endif; ?>
