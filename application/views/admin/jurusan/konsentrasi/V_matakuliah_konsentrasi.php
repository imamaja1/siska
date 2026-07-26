<?= e($this->session->flashdata('info')) ?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="box box-solid">
            <div class="box-header">
                <h4>Form Matakuliah Kompetensi</h4>
                <div class="box-tools">
                    <a href="<?= site_url('admin/jurusan/konsentrasi') ?>" class="btn btn-danger btn-round btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <div class="box-body">
                <form action="<?= site_url('admin/jurusan/konsentrasi/simpan_matakuliah') ?>" method="post">
                    <input type="hidden" name="kode_kompetensi" value="<?= e($konsentrasi->kode_kompetensi) ?>">
                    <div class="form-group">
                        <label for="">Matakuliah <span class="text-danger">*</span></label>
                        <select name="id_matakuliah" required class="form-control select2">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($matakuliah as $row) : ?>
                                <option value="<?= e($row->id_matakuliah) ?>"><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <h4><span class="text-warning"><i class="fa fa-book"></i></span> Matakuliah Konsentrasi
                    <b><?= e($konsentrasi->nama_kompetensi) ?></b></h4>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped data-table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Matakuliah</th>
                            <th>Matakuliah</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lists as $key => $row) : ?>
                            <tr>
                                <td><?= $key + 1 ?>.</td>
                                <td><?= e($row->kode_matakuliah) ?></td>
                                <td><?= e($row->nama_matakuliah) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" onclick="hapus('<?= site_url('admin/jurusan/konsentrasi/delete_matakuliah/'.$row->id) ?>')" class="btn btn-danger btn-sm" title="Delete Matakuliah"><i class="fa fa-trash"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function hapus(url) {
        swal({
            title: '',
            text: 'Anda yakin ingin menghapus data ini?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            window.location.href = url;
        });
    }
</script>

