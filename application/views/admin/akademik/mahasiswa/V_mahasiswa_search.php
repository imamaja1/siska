<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/mahasiswa'); ?>" class="btn btn-success btn-xs flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <a href="<?= site_url('admin/akademik/mahasiswa/cetak_per_angkatan_jurusan'); ?>" class=" btn btn-xs btn-info flat"><i class="fa fa-file-excel-o"></i> Cetak Ke Excel</a>
        <?php if (count($data_mahasiswa) > 0): ?>
            <span class="btn-default flat btn-xs btn" style="cursor: default">Terdapat <b><?= e($jumlah_data) ?> Record</b></span>
            <div class="pull-right">
                <?= $halaman ?>
            </div>
        <?php else: ?>

        <?php endif; ?>
    </div>
</div>


<?php if (count($data_mahasiswa) > 0): ?>
    <div class="box box-primary flat">
        <div clas="box-body">
            <div class="table-responsive">
                <table class="demo-table table">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">NIM </th>
                            <th style="text-align: center;">NPM</th>

                            <th>Nama Mahasiswa</th>
                            <th style="text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1 + $this->uri->segment(5);
                    foreach ($data_mahasiswa as $row) {
                        ?>

                        <tr>
                            <td align="center"><?= $no++; ?></td>
                            <td align="center"><?= e($row->nim) ?> </td>
                            <td align="center"><?= e($row->npm) ?> </td>

                            <td><?= e($row->nama_mahasiswa) ?></td>
                            <td align="center" width="190">

                                   <!--<a href="#!" class="btn-sm btn-danger flat"><i class="fa fa-refresh"></i> Reset Sandi</a>-->
                                <a href="<?= site_url('admin/akademik/mahasiswa/update/' . $row->nim) ?>" class="btn-primary btn-xs flat"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                                <a href="#!"  onclick="halaman('<?= site_url('admin/akademik/mahasiswa/biodata_mahasiswa/' . $row->nim) ?>');" class="btn-warning btn-xs flat"><i class="fa fa-eye"></i> Detail</a>&nbsp;

                                <a href="<?= site_url('admin/akademik/mahasiswa/cetak/' . $row->nim); ?>" class="btn-info btn-xs flat"><i class="fa fa-print"></i> Cetak</a>
                            </td>
                        </tr>

                    <?php } ?>

                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="callout callout-info flat">
        <?= $this->session->flashdata('keterangan') ?>
    </div>
<?php endif; ?>

<script>
    function halaman(url) {
        var win = window.open(url, 'DUMET School', "height=600, width=1000, scrollbars=yes");
        win.focus();
    }
</script>

<div class="modal fade" id="modal_detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Detail Mahasiswa</b></h4>
            </div>
            <div class="modal-body">
                <div class="box box-solid">

                    <div class="box-body flat">
                        <div class="box-group flat" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title flat">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                                            <H5>I. IDENTITAS MAHASISWA</H5>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="box-body flat">
                                        <table class="table">
                                            <tr>
                                                <td width="200">a. Nomor Induk Mahasiswa</td>    
                                                <td width="20">:</td>
                                                <td id="nim_detail"></td>
                                            </tr>
                                            <tr>
                                                <td>b. Nomor Pokok Mahasiswa</td>    
                                                <td>:</td>
                                                <td id="npm_detail"></td>
                                            </tr>
                                            <tr>
                                                <td>c. No Pendaftaran</td>    
                                                <td>:</td>
                                                <td id="pendaftaran_detail"></td>
                                            </tr>
                                            <tr>
                                                <td>d. No Pendaftaran Ulang</td>    
                                                <td>:</td>
                                                <td id="pendaftaran_ulang_detail"></td>
                                            </tr>
                                            <tr>
                                                <td>e. Nama Mahasiswa</td>    
                                                <td>:</td>
                                                <td id="nama_detail"></td>
                                            </tr>
                                            <tr>
                                                <td>h. Tempat/Tanggal Lahir</td>    
                                                <td>:</td>
                                                <td id="tempat_t_lahir"></td>
                                            </tr>
                                            <tr>
                                                <td>i. Alamat</td>    
                                                <td>:</td>
                                                <td id="alamat"></td>
                                            </tr>
                                            <tr>
                                                <td>j. Propinsi</td>    
                                                <td>:</td>
                                                <td id="propinsi"></td>
                                            </tr>
                                            <tr>
                                                <td>k. Nomor Telepon</td>    
                                                <td>:</td>
                                                <td id="telepon"></td>
                                            </tr>
                                            <tr>
                                                <td>l. Jenis Kelamin</td>    
                                                <td>:</td>
                                                <td id="jenis_kelamin"></td>
                                            </tr>
                                            <tr>
                                                <td>m. Agama </td>
                                                <td>:</td>
                                                <td id="agama"></td>
                                            </tr>
                                            <tr>
                                                <td>n. Golongan Darah</td>
                                                <td>:</td>
                                                <td id="golongan_darah"></td>
                                            </tr>
                                            <tr>
                                                <td>o. Kewarganegaraan</td>
                                                <td>:</td>
                                                <td id="kewarganegaraan"></td>
                                            </tr>
                                            <tr>
                                                <td>p. Nama Instansi</td>
                                                <td>:</td>
                                                <td id="nama_instansi"></td>
                                            </tr>
                                            <tr>
                                                <td>h. Email</td>
                                                <td>:</td>
                                                <td id="email"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-danger flat">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                                            <h5>II. DATA ORANG TUA/WALI</h5>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="box-body flat">
                                        <table class="table">
                                            <tr>
                                                <td width="200">a. Nama Ayah</td>
                                                <td width="20">:</td>
                                                <td id="nama_ayah"></td>
                                            </tr>
                                            <tr>
                                                <td>b. Agama Ayah</td>
                                                <td>:</td>
                                                <td id="agama_ayah"></td>
                                            </tr>
                                            <tr>
                                                <td>c. Pekerjaan Ayah</td>
                                                <td>:</td>
                                                <td id="pekerjaan_ayah"></td>
                                            </tr>
                                            <tr>
                                                <td>d. Nama Ibu</td>
                                                <td>:</td>
                                                <td id="nama_ibu"></td>
                                            </tr>
                                            <tr>
                                                <td>e. Agama Ibu</td>
                                                <td>:</td>
                                                <td id="agama_ibu"></td>
                                            </tr>
                                            <tr>
                                                <td>f. Pekerjaan Ibu</td>
                                                <td>:</td>
                                                <td id="pekerjaan_ibu"></td>
                                            </tr>
                                            <tr>
                                                <td>g. Alamat Orang Tua</td>
                                                <td>:</td>
                                                <td id="alamat_orangtua"></td>
                                            </tr>
                                            <tr>
                                                <td>h. Kota Orang Tua</td>
                                                <td>:</td>
                                                <td id="kota_orangtua"></td>
                                            </tr>
                                            <tr>
                                                <td>i. Nomor Telepon</td>
                                                <td>:</td>
                                                <td id="no_telepon_orangtua"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-success">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                                            <h5>III. FILE FOTO MAHASISWA</h5>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                                    <div class="box-body">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-right flat" data-dismiss="modal"><i class="fa fa-remove"></i> Tutup</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
