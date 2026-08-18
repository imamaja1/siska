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
        <table class="table">
            <tr>
                <td><strong>Matakuliah</strong></td>
                <td><strong>:</strong></td>
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
        <table class="table demo-table" id="table-edit">
            <thead>
            <tr>
                <th id="color" width="20"><center>No.</center></th>
                <th id="color"><center>NIM</center></th>
                <th id="color"><center>Nama Mahasiswa</center></th>
                <th id="color"><center>Nilai Harian</center></th>
                <th id="color"><center>Nilai UTS</center></th>
                <th id="color"><center>Nilai UAS</center></th>
                <th id="color"><center>Nilai Akhir</center></th>
                <th id="color"><center>Grade</center></th>
                <th id="color"><center>Ket</center></th>
                <th id="color"><center>Status</center></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; foreach ($data as $row) : ?>
                <tr>
                    <td><?= $i++ . "." ?></td>
                    <td style="display:none;"><?= e($row->kode_krs_detail) ?></td>
                    <td><?= e($row->nim) ?></td>
                    <td><?= e($row->nama_mahasiswa) ?></td>
                    <td style="text-align:center;"><?= e($row->nilai_harian) ?></td>
                    <td style="text-align:center;"><?= e($row->nilai_uts) ?></td>
                    <td style="text-align:center;"><?= e($row->nilai_uas) ?></td>
                    <td><?= e($row->nilai_akhir) ?></td>
                    <td style="text-align:center;"><?= e(isset($row->grade) ? $row->grade : '-') ?></td>
                    <td><?= (isset($row->tidak_berhak) && $row->tidak_berhak == 'A') ? 'TB' : '' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
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
