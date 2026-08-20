<?= $this->session->flashdata('info')?>
<?php if (count($data) > 0) : ?>
<div class="box box-primary flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th style="text-align: center; width: 50px;">NO.</th>
                    <th style="text-align: center; width: 120px;">NIM</th>
                    <th style="text-align: center;">NAMA</th>
                    <th style="text-align: center; width: 150px;">JURUSAN</th>
                    <th style="text-align: center; width: 120px;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($data as $row) : ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ ?>.</td>
                        <td style="text-align: center;"><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?> <?= $row->kode_krs !== null ? '<span class="badge bg-aqua">Konverted</span>' : ''?> </td>
                        <td style="text-align: center;"><?= e($row->nama_program_studi) ?></td>
                        <td style="text-align: center;">
                            <?php if ($row->kode_krs==null) : ?>
                            <a href="<?= site_url('admin/akademik/konversi/konversi/'.$row->nim) ?>" class="btn btn-warning btn-xs flat"><i class="fa fa-gear"></i> Konversi</a>
                            <?php else: ?>
                            <a href="<?= site_url('admin/akademik/konversi/edit/'.$row->nim) ?>" class="btn btn-success btn-xs flat"><i class="fa fa-edit"></i> Edit</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="callout callout-warning">
        <h4><i class="fa fa-warning"></i> Peringatan!</h4>

        <p>Data tidak ditemukan.</p>
    </div>
<?php endif; ?>