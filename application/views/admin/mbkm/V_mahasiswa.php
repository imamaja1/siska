<?php $this->load->view('admin/mbkm/partial/V_header'); ?>
<div class="box box-primary flat">
    <div class="box-header">
        <h4 class="box-title"><i class="fa fa-users"></i> Data Mahasiswa MBKM</h4>
    </div>
    <div class="box-body">
        <form action="<?= site_url('admin/mbkm/daftar') ?>" method="get">
            <div class="form-group">
                <label for="kode_tahun_akademik">Tahun Akademik</label>
                <select name="ta" id="kode_tahun_akademik" class="form-control">
                    <?php foreach ($tahun_akademik as $value): ?>
                        <option value='<?= e($value->kode_tahun_akademik) ?>' <?= ($value->kode_tahun_akademik == $kode_tahun_akademik) ? 'selected' : '' ?>>Semester <?= $value->semester == '1' ? 'Ganjil Tahun '.$value->tahun_akademik:'Genap Tahun '.$value->tahun_akademik?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="kode_program_studi">Program Studi</label>
                <select name="prodi" id="kode_program_studi" class="form-control">
                    <option value="">Semua</option>
                    <?php foreach ($prodi as $value): ?>
                        <option value="<?= e($value->kode_program_studi) ?>" <?= ($kode_program_studi_filter == $value->kode_program_studi) ? 'selected' : '' ?>><?= e($value->singkatan_program_studi) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
        <hr>
        <?php if (!empty($mahasiswa)): ?>
        <div class="table-responsive">
            <table class="table demo-table data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mahasiswa as $key => $value): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= e($value->nim) ?></td>
                            <td><?= e($value->nama_mahasiswa) ?></td>
                            <td><?= e($value->nama_program_studi) ?></td>
                            <td>
                                <a href="<?= site_url('admin/mbkm/daftar/nilai/'.$value->id_fix.'/'.$kode_tahun_akademik) ?>" class="btn btn-success btn-sm">Penilaian</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="hapus(<?= e($value->id_fix) ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script>
            $('.data-table').DataTable();
            function hapus(id) {
                if (confirm('Anda yakin ingin menghapus data ini?')) {
                    $.get('<?= site_url('admin/mbkm/daftar/hapus_mhs_mbkm') ?>/' + id, function(res) {
                        location.reload();
                    });
                }
            }
        </script>
        <?php else: ?>
        <p class="text-muted">Tidak ada data mahasiswa MBKM.</p>
        <?php endif; ?>
    </div>
</div>
