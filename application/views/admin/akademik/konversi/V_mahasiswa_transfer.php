<?= $this->session->flashdata('info')?>
<?php if (count($data) > 0) : ?>
<div class="box box-primary flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table">
                <thead>
                <tr>
                    <th id="th">NO.</th>
                    <th id="th">NIM</th>
                    <th id="th">NAMA</th>
                  	<th id="th">JURUSAN</th>
                    <th id="th">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($data as $row) : ?>
                    <tr>
                        <td align="center" width="3%"><?= $i++ ?>.</td>
                        <td align="center"><?= e($row->nim) ?></td>
                        <td ><?= e($row->nama_mahasiswa) ?> <?= $row->kode_krs !== null ? '<span class="badge bg-aqua">Konverted</span>' : ''?> </td>
                      	<td align="center"><?= e($row->nama_program_studi) ?></td>
                        <td align="center">
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