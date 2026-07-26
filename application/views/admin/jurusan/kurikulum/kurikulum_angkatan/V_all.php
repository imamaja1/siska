<?php if (count($data) > 0) : ?>
    <div class="box box-solid flat">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table demo-table">
                    <thead>
                    <tr>
                        <th style="width: 3%">NO.</th>
                        <th style="text-align: center">ANGKATAN</th>
                        <th style="text-align: center">PROGRAM STUDI</th>
                        <th style="text-align: center">NAMA KURIKULUM</th>
                        <th style="text-align: center">AKSI</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; foreach ($data as $row) : ?>
                        <tr>
                            <td><?= $no++ ?>.</td>
                            <td style="text-align: center"><?= $row->angkatan ?> <?= $row->ekstensi == 'Y' ? "<span class='badge bg-navy'>Ekstensi</span>" : ''?><?= $row->paket == 'Y' ? "<span class='badge bg-aqua'>Paket</span>" : ''?></td>
                            <td style="text-align: left"><?= $row->nama_program_studi ?></td>
                            <td style="text-align: center"><?= $row->nama_kurikulum ?></td>
                            <td style="text-align: center">
                                <div class="btn-group btn-group-xs">
                                    <button type="button" onclick="edit('<?= $row->kode_kurikulum_angkatan ?>')" class="btn btn-default" title="Edit"><i class="fa fa-edit"></i></button>
                                    <button type="button" onclick="hapus('<?= $row->kode_kurikulum_angkatan ?>')" class="btn btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="box box-solid flat">
        <div class="box-body">
            <div class="callout callout-info">
                <h4>Informasi!</h4>

                <p>Belum ada data.</p>
            </div>
        </div>
    </div>
<?php endif; ?>