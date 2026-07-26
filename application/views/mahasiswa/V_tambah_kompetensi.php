<div class="modal fade" id="tambah-kompetensi">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Kompetensi Mahasiswa</h4>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('mahasiswa/kompetensi/simpan') ?>" method="post">
                    <div class="form-group">
                        <label for=""> Kompetensi</label>
                        <select name="kode_kompetensi" id="" class="form-control">
                            <option selected disabled>Pilih</option>
                            <?php foreach ($data_kompetensi as $row) : ?>
                                <option value="<?= $row->kode_kompetensi ?>"><?= $row->singkatan_kompetensi ?> - <?= $row->nama_kompetensi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger flat" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" name="submit" class="btn btn-primary flat"><i class="fa fa-check-square-o"></i> Simpan</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function () {
        $('#tambah-kompetensi').modal('show');
    })
</script>