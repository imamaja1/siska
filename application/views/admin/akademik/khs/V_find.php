<!--<div class="row">-->
<!--    <div class="col-sm-12 col-md-12 col-xs-12">-->
<!--        <div class="box box-solid">-->
<!--            <div class="box-body">-->
                <?php if ($data != 0 && count($data) > 0) : ?>
                <div class="table-responsive">
                    <table class="table demo-table">
                        <thead>
                        <tr>
                            <th style="text-align: center" width="20" id="color">NO.</th>
                            <th style="text-align: center" id="color">NIM</th>
                            <th style="text-align: center" id="color">NAMA MAHASISWA</th>
                            <th style="text-align: center" id="color">SEMESTER</th>
                            <th style="text-align: center" id="color">AKSI</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; foreach ($data as $row) : ?>
                            <tr>
                                <td><?= $i++ ?>.</td>
                                <td align="center"><?= $row->nim ?></td>
                                <td><?= $row->nama_mahasiswa ?></td>
                                <td align="center"><?= $row->semester ?></td>
                                <td style="text-align: center;">
                                    <a href="<?= site_url('admin/akademik/khs/lihat_khs/'.$row->kode_krs.'/'.$row->nim) ?>" class="btn btn-info btn-xs flat"><i class="fa fa-eye"></i> Lihat</a>&nbsp;
                                    <a href="<?= site_url('admin/akademik/khs/cetak/'.$row->kode_krs.'/'.$row->nim) ?>" class="btn btn-warning btn-xs flat"><i class="fa fa-download"></i> Download</a></center>
                                    <a href="<?= site_url('admin/akademik/khs/print_view/'.$row->kode_krs.'/'.$row->nim) ?>" target="_blank" class="btn btn-danger btn-xs flat"><i class="fa fa-print"></i> Cetak</a></center>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <p style="text-align: center">
                        <span class="text-orange"><i class="fa fa-warning"></span></i> Data tidak ditemukan, Silahkan lakukan pencarian dengan <b>"<i>Keyword</i>"</b> yang benar.
                    </p>
                <?php endif; ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->