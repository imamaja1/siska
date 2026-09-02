<?= $this->session->flashdata('pesan') ?>

<div class="box box-primary flat">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Distribusi Perwalian Random</h3>
    </div>
    <div class="box-body">
        <p>Distribusikan mahasiswa yang belum memiliki dosen wali ke dosen aktif, berdasarkan urutan NIM dan dirata-ratakan sesuai jumlah dosen aktif per program studi.</p>

        <form id="form-distribusi" method="POST" action="<?= site_url('admin/pengaturan/distribusi_perwalian/proses') ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Program Studi</label>
                        <select required name="kode_program_studi" id="kode_program_studi" class="form-control select2" style="width: 100%;">
                            <option value="" selected disabled>Pilih Program Studi</option>
                            <?php foreach ($program_studi as $row): ?>
                            <option value="<?= e($row->kode_program_studi) ?>"><?= e($row->singkatan_program_studi) ?> - <?= e($row->nama_program_studi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Tahun Akademik Aktif</label>
                        <input type="text" class="form-control" value="<?= e($tahun_akademik->ta ?? '-') ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-info flat" id="btn-preview"><i class="fa fa-eye"></i> Preview</button>
                <button type="submit" class="btn btn-primary flat" id="btn-proses"><i class="fa fa-refresh"></i> Distribusi Random</button>
            </div>
        </form>

        <hr>

        <div id="hasil-preview" style="display: none;">
            <h4>Preview Distribusi</h4>
            <p id="info-preview" class="text-muted"></p>
            <div id="table-preview"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btn-preview').on('click', function() {
        var kode = $('#kode_program_studi').val();
        if (!kode) {
            swal('Gagal', 'Silakan pilih program studi terlebih dahulu.', 'error');
            return;
        }
        $.ajax({
            url: '<?= site_url("admin/pengaturan/distribusi_perwalian/preview") ?>',
            type: 'POST',
            dataType: 'json',
            data: { kode_program_studi: kode },
            success: function(res) {
                if (res.status) {
                    $('#hasil-preview').show();
                    $('#info-preview').text('Jumlah dosen aktif: ' + res.jumlah_dosen + ' | Jumlah mahasiswa belum ber-wali: ' + res.jumlah_mahasiswa);
                    $('#table-preview').html(res.html);
                } else {
                    swal('Gagal', 'Terjadi kesalahan.', 'error');
                }
            },
            error: function() {
                swal('Gagal', 'Terjadi kesalahan koneksi.', 'error');
            }
        });
    });

    $('#form-distribusi').on('submit', function(e) {
        var kode = $('#kode_program_studi').val();
        if (!kode) {
            e.preventDefault();
            swal('Gagal', 'Silakan pilih program studi terlebih dahulu.', 'error');
            return;
        }
        return confirm('Yakin ingin melakukan distribusi perwalian? Data perwalian baru akan ditambahkan untuk mahasiswa yang belum memiliki dosen wali.');
    });
});
</script>
