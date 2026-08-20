<?php if (isset($kelas) && isset($data)) : ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/perubahan/nilai_kelas') ?>" class="btn btn-danger btn-sm flat"><i
                    class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body">
        <p><center><strong>PERUBAHAN NILAI PER KELAS</strong></center></p>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td width="150"><strong>Matakuliah</strong></td>
                            <td width="10"><strong>:</strong></td>
                            <td><?= e(isset($matakuliah->kode_matakuliah) ? $matakuliah->kode_matakuliah : '') ?> - <?= e(isset($matakuliah->nama_matakuliah) ? $matakuliah->nama_matakuliah : '') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e(isset($kelas->nama_kelas) ? $kelas->nama_kelas : '') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tahun Akademik</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e(isset($tahun_akademik->tahun_akademik) ? $tahun_akademik->tahun_akademik : '') ?><?= isset($tahun_akademik->semester) && $tahun_akademik->semester == 1 ? ' - Ganjil' : (isset($tahun_akademik->semester) && $tahun_akademik->semester == 0 ? ' - Genap' : '') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-edit">
                <thead>
                <tr>
                    <th style="text-align: center; width: 40px;">No.</th>
                    <th style="display:none;">Kode KRS Detail</th>
                    <th style="text-align: center; width: 120px;">NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th style="text-align: center; width: 80px;">Nilai Harian</th>
                    <th style="text-align: center; width: 80px;">Nilai UTS</th>
                    <th style="text-align: center; width: 80px;">Nilai UAS</th>
                    <th style="text-align: center; width: 80px;">Nilai Akhir</th>
                    <th style="text-align: center; width: 60px;">Grade</th>
                    <th style="text-align: center; width: 60px;">Ket</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($data as $row) : ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ . "." ?></td>
                        <td style="display:none;"><?= e($row->kode_krs_detail) ?></td>
                        <td style="text-align: center;"><?= e($row->nim) ?></td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                        <td style="text-align:center;"><?= e($row->nilai_harian) ?></td>
                        <td style="text-align:center;"><?= e($row->nilai_uts) ?></td>
                        <td style="text-align:center;"><?= e($row->nilai_uas) ?></td>
                        <td style="text-align: center;"><?= e($row->nilai_akhir) ?></td>
                        <td style="text-align: center;"><?= e(isset($row->grade) ? $row->grade : '-') ?></td>
                        <td style="text-align: center;"><?= (isset($row->tidak_berhak) && $row->tidak_berhak == 'A') ? 'TB' : '' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var lastKodeKrsDetail = '';
    $('#table-edit').Tabledit({
        url: "<?= site_url('admin/akademik/perubahan/Nilai_kelas/ubah_krs_nilai') ?>",
        hideIdentifier: true,
        onAjax: function (action, serialize) {
            if (action === 'edit') {
                var match = serialize.match(/(?:^|&)kode_krs_detail=([^&]*)/);
                lastKodeKrsDetail = match ? decodeURIComponent(match[1]) : '';
            }
            return true;
        },
        columns: {
            identifier: [1, 'kode_krs_detail'],
            editable: [[4, 'nilai_harian'], [5, 'nilai_uts'], [6, 'nilai_uas'], [7, 'nilai_akhir'], [9, 'tidak_berhak', '{"N": "Berhak", "A": "TB"}']],
        },
        onSuccess: function (data, textStatus, jqXHR) {
            if (data && data.status === true) {
                if (lastKodeKrsDetail) {
                    $('#table-edit tbody tr').each(function () {
                        if ($(this).find('td:eq(1)').text() === lastKodeKrsDetail) {
                            $(this).find('td:eq(8)').text(data.grade || '-');
                        }
                    });
                }
                swal("Berhasil!", "Pembaruan selesai", "success");
            } else {
                swal("Gagal!", "Gagal memperbarui nilai", "error");
            }
        },
        onFail: function (jqXHR, textStatus, errorThrown) {
            swal("Gagal!", "Terjadi kesalahan saat menyimpan", "error");
        }
    });
</script>
<?php else: ?>
    <div class="callout callout-info flat">
        <h4>Info!</h4>
        <p>Pilih Tahun Akademik, Matakuliah, dan Kelas terlebih dahulu.</p>
    </div>
<?php endif; ?>
