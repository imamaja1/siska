<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <strong>Dosen Wali :</strong> <span class="badge bg-navy"><?= $dosen_wali ?></span>&nbsp;
        <?php if (isset($dosen_perwakilan)) : ?>
            <strong>Dosen Perwakilan :</strong> <span class="badge bg-orange"><?= $dosen_perwakilan ?></span>
        <?php endif; ?>
    </div>
</div>
<div class="box box-solid flat">
    <div class="box-body">
        <?php $new_semester=0; foreach ($krs_mhs as $row) : ?>
            <?php if($new_semester == 0) $new_semester = $row->kode_tahun_akademik   ?>
            <a href="<?= site_url('mahasiswa/krs/old/'.$row->kode_tahun_akademik.'/'.$row->semester) ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER  <?= $row->semester ?></a>
        <?php endforeach; ?>
        <a href="<?= site_url('mahasiswa/krs/index/') ?>" class="btn bg-navy flat btn-xs"><i class="fa fa-arrow-circle-right"></i> | SEMESTER <?= $semester ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="box box-solid">
            <div class="box-body">
                Aktifasi Dosen Wali : <span
                        class="badge <?= !empty($aktif_dosen) ? "bg-green" : 'bg-red' ?> "><?= !empty($aktif_dosen) ? '<i class="fa fa-check"></i> Sudah' : '<i class="fa fa-times"></i> Belum' ?></span>
              	<?php if(substr($data_mahasiswa->nim, 0, 2) < 25): ?>
                | Validasi Pembayaran SKS : <span
                        class="badge <?= !empty($bayar_sks) ? "bg-green" : 'bg-red' ?> "><?= !empty($bayar_sks) ? '<i class="fa fa-check"></i> Sudah' : '<i class="fa fa-times"></i> Belum' ?></span>
              	<?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="box box-solid">
            <div class="box-body">
              <!--<div class="col-12">
                  <div class="alert alert-warning" role="alert">
                  Kepada Mahasiswa kami mengingatkan, Bahwa Batas Validasi KRS disiska paling lambat 7 September 2024
               </div> 
              </div>-->
                <div class="text-right">
                    <?php 
                        if ($ta == $tahun_akademik->kode_tahun_akademik) {
                        ?>
                  			<?php if(empty($aktif_dosen) && empty($bayar_sks)):?>
                                    <a href="krs/edit_krs" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Ubah</a>
                            <?php else:?>
                  				<?php if( empty($aktif_dosen) && substr($data_mahasiswa->nim, 0, 2) > 24 ):?>
                                    <a href="krs/edit_krs" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Ubah</a>
                  				<?php else: ?>
                  					<a href="#" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Tidak Dapat Diubah</a>
                                <?php endif; ?>
                  				<?php if( !empty($aktif_dosen) && substr($data_mahasiswa->nim, 0, 2) > 24 ):?>
                                    <a href="<?= site_url('mahasiswa/krs/print_view') ?>" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if( !empty($aktif_dosen) && !empty($bayar_sks) && substr($data_mahasiswa->nim, 0, 2) < 25 ):?>
                                <a href="<?= site_url('mahasiswa/krs/print_view') ?>" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a>
                            <?php endif; ?>
                        <?php 
                        }else{
                            ?>
                                <a href="<?= site_url('mahasiswa/krs/print_view_lalu/'.$tahun_akademik->kode_tahun_akademik.'/'.$semester) ?>" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a>
                            <?php
                        }
                    ?>
                  
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$angkatan = substr($data_mahasiswa->nim, 0, 2); 
if (empty($data_mahasiswa->foto) || $data_mahasiswa->foto == 'P.png' || $data_mahasiswa->foto == 'L.png') {
    $foto = false;
} else {
    $foto = true;
}
?>
<div class="box box-primary flat">
    <div class="box-body flat table-responsive">
        <?php if(false): // if ($this->session->flashdata('pesan')): ?>
            <div class="box box-warning box-solid animated shake -repeat">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="icon fa fa-info"></i> Perhatian!</h3>
                </div>
                <div class="box-body">
                    <!--<b><?= $this->session->flashdata('pesan'); ?></b>-->
                    <p>Maaf anda tidak bisa melakukan download KRS, Adapun penyebabnya sebagi berikut : </p>
                    <ul style="color: red;">
                        <?php if (empty($aktif_dosen)) : ?>
                            <li>Belum melakukan pengaktifan KRS ke dosen wali</li>
                        <?php endif; ?>
                        <?php if (empty($bayar_sks)) : ?>
                                                  <?php //TODO::perbaiki biar otomatis untuk angkatan baru ?>
                            <?php if ($angkatan == '22') : ?>
                                <li>Bagi angkatan 2022 validasi pembayaran SKS akan dilakukan bertahap oleh bagian Keuangan.</li>
                            <?php else : ?>
                                <li>Pembayaran SKS belum di validasi oleh bagian keuangan.</li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if(true): //if (!$foto) : ?>
                            <li>Belum melakukan upload foto profile. Silahkan upload foto dan tunggu proses Validasi pada link berikut :
  								<?php //TODO::perbaiki biar otomatis untuk angkatan baru ?>
                                <?php if ($angkatan == '22') : ?>
                                    <a target="_blank" class="btn btn-success btn-xs" href="https://berkas.universitasbumigora.ac.id/pmb/">Link Upload</a>
                                <?php else : ?>
                                    <a target="_blank" class="btn btn-success btn-xs" href="https://berkas.universitasbumigora.ac.id/">Link Upload</a>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <p><i>NB: untuk status pengaktifan dosen wali dan validasi pembayaran SKS bisa dilihat dibagian atas notifikasi ini.
                        Untuk validasi foto profile bisa di cek pada menu profile, jika foto anda sudah muncul maka foto sudah tervalidasi.</i></p>
                </div>
            </div>
        <?php
        endif;
        ?>
        <div class="row">
            <div class="col-md-2 col-xs-12 col-sm-12">
                <div class="btn-group">
                    <!--<a href="krs/edit_krs" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Ubah</a>
                    <a href="<?= site_url('mahasiswa/krs/cetak/') ?>" class="btn btn-danger btn-xs"><i class="fa fa-download"></i> Download</a>-->
                    <!--            <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">-->
                    <!--                <span class="text-primary"><i class="fa fa-ellipsis-h fa-2x"></i></span></button>-->
                    <!--            <ul class="dropdown-menu" role="menu">-->
                    <!--                <li>-->
                    <!--                    <a href="krs/edit_krs" class="text-danger"><i class="fa fa-edit"></i> Ubah</a>-->
                    <!--                </li>-->
                    <!--                <li>-->
                    <!--                    <a href="-->
                    <? //= site_url('mahasiswa/krs/cetak/') ?><!--" class="text-danger"><i class="fa fa-download"></i> Download</a>-->
                    <!--                </li>-->
                    <!--            </ul>-->
                </div>
            </div>
            <div class="col-md-10 col-xs-12 col-sm-12">
                <p style="text-align: center"><strong>KARTU RECANA STUDI (KRS)
                        JENJANG <?= strtoupper($prodi->nama_program_studi) ?>
                        (<?= strtoupper($prodi->singkatan_program_studi) ?>)</strong></p>
                <p style="text-align: center">
                    <strong>SEMESTER <?= $tahun_akademik->semester % 2 == (0) ? "GENAP" : "GANJIL" ?></strong></p>
            </div>
        </div>
        <table class="table" width="100%">
            <tr>
                <td width="50">
                    <table class="table">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <td> : <?= $nama_mahasiswa ?></td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td> : <?= $nim ?></td>
                        </tr>
                        <tr>
                            <th>Semester</th>
                            <td> : <?= $semester ?></td>
                        </tr>
                        <tr>
                            <th>Dosen Wali</th>
                            <td> : <?= $dosen_wali ?></td>
                        </tr>
                    </table>
                </td>
                <td width="50">
                    <table class="table">
                        <tr>
                            <th>Alamat Sekarang</th>
                            <td>
                                : <?= $data_mahasiswa->alamat . ', ' . $data_mahasiswa->kota . '<br>' . $data_mahasiswa->propinsi ?></td>
                        </tr>
                        <tr>
                            <th>No. Telp/HP</th>
                            <td> : <?= $data_mahasiswa->telepon ?></td>
                        </tr>
                        <tr>
                            <th>Alamat Orang Tua</th>
                            <td>
                                : <?= $data_mahasiswa->alamat_orangtua . ', ' . $data_mahasiswa->kota_orangtua . '<br>' . $data_mahasiswa->propinsi_orangtua ?></td>
                        </tr>
                        <tr>
                            <th>Telp</th>
                            <td> : <?= $data_mahasiswa->telepon_orangtua ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div align="center">Tanda <b>&radic;</b> di kolom <b>B</b> atau <b>U</b> menandakan matakuliah yang dipilih.
        </div>
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
            $t = 0;
            $pk = 0;
            $pt = 0;
            foreach ($data as $row) : ?>
                <tr>
                    <td>
                        <center><?= $i++ ?>.</center>
                    </td>
                    <td>
                        <center><?= $row->kode_matakuliah ?></center>
                    </td>
                    <?php if (substr($this->session->userdata('nim'), 0, 2) == '16') : ?>
                        <?php if ($row->kode_matakuliah == 'TSKB351435') : ?>
                            <td>Animasi 2 Dimensi</td>
                        <?php else : ?>
                            <td><?= $row->nama_matakuliah ?></td>
                        <?php endif; ?>
                    <?php else: ?>
                        <td><?= $row->nama_matakuliah ?></td>
                    <?php endif; ?>
                    <td width="100">
                        <center><?= $row->sks_teori != 0 ? $row->sks_teori : '' ?></center>
                    </td>
                    <td width="100">
                        <center><?= $row->sks_praktek != 0 ? $row->sks_praktek : '' ?></center>
                    </td>
                    <td width="100">
                        <center><?= $row->sks_praktikum != 0 ? $row->sks_praktikum : '' ?></center>
                    </td>
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
            $total_sks = $t + $pk + $pt;
            ?>
            <tr class="th-color">
                <td align="center" colspan="3"><strong>Jumlah</strong></td>
                <td align="center"><?= $t ?></td>
                <td align="center"><?= $pk ?></td>
                <td align="center"><?= $pt ?></td>
                <td align="center" colspan="2"></td>
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
                        <td width="20%"><?= $semester != "1" ? number_format($beban_sks['ip_semester_lalu'], 2) : "-" ?></td>
                    </tr>
                    <?php if (substr($nim, 4, 1) != 3) : ?>
                        <tr>
                            <td width="40%">Beban SKS Semester Sekarang</td>
                            <td width="3%">:</td>
                            <td width="20%"><?= $semester == 1 ? $total_sks : $beban_sks['beban_sks'] ?>
                        </tr>
                    <?php endif; ?>
                </table>
            </td>
            <td width="50%">
                <table class="table" width="100%">
                    <tr>
                        <td width="40%">Jumlah kredit yang diambil</td>
                        <td width="3%">:</td>
                        <td width="20%"><?= $total_sks ?></td>
                    </tr>
                    <tr>
                        <td width="40%">Jumlah kredit yang dibatalkan</td>
                        <td width="3%">:</td>
                        <!--<td width="20%"><?= $semester !== 1 ? $beban_sks['beban_sks'] - $total_sks : "-" ?></td> -->
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
                        <td width="20%"><?= $total_sks ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="box box-solid flat">
        <div class="box-body">
            <hr>
            <h4>Keterangan</h4>
            <p><b>Status Pengambilan Matakuliah</b><br/>
                <b>B</b> : Baru &nbsp;&nbsp;&nbsp; <b>U</b> : Ulang<br/>
                <b>Jenis SKS Matakuliah</b><br/>
                <b>T</b> : Teori &nbsp;&nbsp;&nbsp; <b>PK</b> : Praktek &nbsp;&nbsp;&nbsp; <b>PT</b> : Praktikum<br/>
                <!--                <b>Kode Kompetensi khusus untuk Strata 1 (S1)</b><br />-->
                <!--                <b>RPL</b> : Rekayasa Perangkat Lunak &nbsp;&nbsp;&nbsp;&nbsp; <b>JK</b> : Jaringan Komputer &nbsp;&nbsp;&nbsp;&nbsp; <b>M</b> : Multimedia-->
            </p>
        </div>
    </div>
</div>
