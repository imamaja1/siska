<div class="box box-primary">
    <div class="box-header">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <h3 class="box-title">Rekap SKS Mahasiswa TA. <?php $ta = tahun_akademik(); echo e($ta ? $ta->tahun_akademik : '-'); ?> <?= $ta ? ($ta->semester == '0' ? 'GENAP' : 'GANJIL') : '' ?></h3>
            </div>
            <div class="col-xs-12 col-sm-4" style="margin-top: 5px;">
                <a href="<?= e(site_url('admin/keuangan/pembayaran/excel/'.rawurlencode($kode_program_studi))) ?>" class="btn btn-success btn-sm btn-block"><i class="fa fa-file-excel-o"></i> Excel</a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <?php if(count($data) > 0) : ?>
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>NIM</th>
                    <th>Nama Siswa</th>
                    <th>Semester</th>
                    <th>SKS Teori</th>
                    <th>SKS Praktikum</th>
                    <th>Status Bayar</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach ($data as $row) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= e($row->nim) ?></td>
                    <td><?= e($row->nama_mahasiswa) ?></td>
                    <td style="text-align: center"><?= e($row->semester) ?></td>
                    <td style="text-align: center"><span class="badge bg-aqua-active"><?= e($row->teori) ?></span></td>
                    <td style="text-align: center"><span class="badge bg-red"><?= e($row->praktikum) ?></span></td>
                    <td style="text-align: center; width: 18%">
                        <div class="form-group">
                            <div class="checkbox pilihan">
                                <?php if ($row->pembayaran_spp == '0') : ?>
                                    <span  class="text-danger"><i class="fa fa-times"></i> SPP</span>
                                <?php elseif ($row->pembayaran_spp == '1') : ?>
                                    <span class="text-success"><i class="fa fa-check"></i> SPP</span>
                                <?php else : ?>
                                    <span  class="text-warning"><i class="fa fa-history"></i> SPP</span>
                                <?php endif; ?>
                            </div>&nbsp;&nbsp;

                            <div class="checkbox pilihan">
                                <?php if ($row->pembayaran_sks == '0') : ?>
                                    <span class="text-danger"><i class="fa fa-times"></i> SKS</span>
                                <?php elseif ($row->pembayaran_sks == '1') : ?>
                                    <span class="text-success"><i class="fa fa-check"></i> SKS</span>
                                <?php else : ?>
                                    <span class="text-warning"><i class="fa fa-history"></i> SKS</span>
                                <?php endif; ?>
                            </div>&nbsp;&nbsp;
                            <?php if ($row->praktikum > 0) : ?>
                            <div class="checkbox pilihan">
                                <?php if ($row->pembayaran_lab == '0') : ?>
                                    <span class="text-danger"><i class="fa fa-times"></i> LAB</span>
                                <?php elseif ($row->pembayaran_lab == '1') : ?>
                                    <span class="text-success"><i class="fa fa-check"></i> LAB</span>
                                <?php else : ?>
                                    <span class="text-warning"><i class="fa fa-history"></i> LAB</span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; font-size: 20pt; font-weight: bold"><i> Tidak ada data ditemukan</i></p>
        <?php endif; ?>
    </div>
</div>
<script>
    $(".data-table").dataTable();
</script>