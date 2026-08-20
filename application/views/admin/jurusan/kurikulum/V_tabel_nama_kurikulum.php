<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th id="th">No</th>
            <th id="th">Nama Kurikulum</th>
            <th id="th">Nama Jurusan</th>
            <th id="th">Tindakan</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($data as $d) : ?>
            <tr id="row-<?= e($d->kode_nama_kurikulum) ?>">
                <td><center><?= $i++ ?></center></td>
                <td align="center" id="nama-kurikulum-<?= e($d->kode_nama_kurikulum) ?>"><?= e($d->nama_kurikulum) ?></td>
                <td align="center" id="nama-jurusan-<?= e($d->kode_nama_kurikulum) ?>"><?= e($d->singkatan_program_studi) ?></td>
                <td align="center">
                    <a href="#" class="btn btn-xs btn-info flat" onclick="javascript:editNamakurikulum('<?= e($d->kode_nama_kurikulum) ?>')"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                    <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= e($d->kode_nama_kurikulum) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                    <span style="display: none;" id="kode-jurusan-<?= e($d->kode_nama_kurikulum) ?>"><?= e($d->kode_program_studi) ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
