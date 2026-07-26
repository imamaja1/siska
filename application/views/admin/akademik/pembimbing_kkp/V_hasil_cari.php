<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="box box-warning flat" id="cont">
            <?php if (count($data) > 0) : ?>
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-table"></i> Daftar Pembimbing KKP</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table demo-table data-table">
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:3%;">NO.</th>
                                    <th style="text-align: center">PEMBIMBING</th>
                                    <th style="text-align: center">JUMLAH BIMBINGAN</th>
                                    <th style="text-align: center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no=1; foreach ($data as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?>.</td>
                                    <td><?= e($row->nama_dosen) ?></td>
                                    <td align="center"><span class="badge bg-info"><?= $row->jumlah_bimbingan ?> Mahasiswa</span></td>
                                    <td align="center">
                                        <a href="<?= site_url('admin/akademik/pembimbing_kkp/view/'.$row->kode_dosen) ?>" class="btn bg-maroon btn-xs">
                                            <i class="fa fa-eye"></i> Lihat Bimbingan
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="box-body" style="height: 20%; text-align: center">
                    <span class="text-orange"><i class="fa fa-warning fa-4x"></i></span>
                    <p><b>Tidak ada data bimibingan</b></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
