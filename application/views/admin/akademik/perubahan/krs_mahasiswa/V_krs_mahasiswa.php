<?= $this->session->flashdata('info') ?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid flat">
            <div class="box-body">
                <a href="<?= site_url('admin/akademik/perubahan/krs_mahasiswa') ?>" class="btn btn-danger btn-xs flat"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary flat">
            <div class="box-header">
                <h4><i class="fa fa-user"></i> Identitas Mahasiswa</h4>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="180">NIM</th>
                        <td><?= e($mahasiswa->nim) ?></td>
                        <th width="180">Nama Mahasiswa</th>
                        <td><?= e($mahasiswa->nama_mahasiswa) ?></td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td colspan="3"><?= e($mahasiswa->nama_program_studi) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary flat">
            <div class="box-header">
                <h4><i class="fa fa-table"></i> Matakuliah yang Diambil (Semua Tahun Akademik)</h4>
            </div>
            <div class="box-body">
                <?php if (count($data) > 0) : ?>
                <div class="table-responsive">
                    <table class="table demo-table" id="table-edit">
                        <thead>
                            <tr>
                                <th style="text-align:center">No</th>
                                <th style="display:none">ID</th>
                                <th>Tahun Akademik</th>
                                <th style="text-align:center">Semester</th>
                                <th>Kode MK</th>
                                <th>Nama Matakuliah</th>
                                <th style="text-align:center">SKS</th>
                                <th style="text-align:center">Nilai Harian</th>
                                <th style="text-align:center">Nilai UTS</th>
                                <th style="text-align:center">Nilai UAS</th>
                                <th style="text-align:center">Nilai Akhir</th>
                                <th style="text-align:center">Grade</th>
                                <th style="text-align:center">Ket</th>
                                <th style="text-align:center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($data as $row) : ?>
                            <tr>
                                <td style="text-align:center"><?= $no++ ?></td>
                                <td style="display:none"><?= e($row->kode_krs_detail) ?></td>
                                <td><?= e($row->nama_ta) ?></td>
                                <td style="text-align:center"><?= e($row->semester) ?></td>
                                <td><?= e($row->kode_matakuliah) ?></td>
                                <td><?= e($row->nama_matakuliah) ?></td>
                                <td style="text-align:center"><?= e($row->sks_teori + $row->sks_praktek + $row->sks_praktikum) ?></td>
                                <td style="text-align:center"><?= ($row->nilai_harian === null || $row->nilai_harian === '') ? '' : e($row->nilai_harian) ?></td>
                                <td style="text-align:center"><?= ($row->nilai_uts === null || $row->nilai_uts === '') ? '' : e($row->nilai_uts) ?></td>
                                <td style="text-align:center"><?= ($row->nilai_uas === null || $row->nilai_uas === '') ? '' : e($row->nilai_uas) ?></td>
                                <td style="text-align:center"><?= ($row->nilai_akhir === null || $row->nilai_akhir === '') ? '' : e($row->nilai_akhir) ?></td>
                                <td style="text-align:center"><?= ($row->status == 'K' || empty($row->grade)) ? '-' : e($row->grade) ?></td>
                                <td style="text-align:center"><?= $row->tidak_berhak == 'A' ? 'TB' : '' ?></td>
                                <td style="text-align:center">
                                    <?php if ($row->status == 'K') : ?>
                                        <span class="label label-danger">K</span>
                                    <?php else : ?>
                                        <span class="label label-success"><?= e($row->status) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                <div class="callout callout-info flat">
                    <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
                    <p>Mahasiswa ini tidak memiliki matakuliah yang diambil.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var lastKodeKrsDetail = '';

    $(document).ready(function () {
        $('#table-edit').dataTable({
            "ordering": false,
            "info": false,
            "searching": true,
            "paging": false,
            "columnDefs": [{
                orderable: false,
                targets: "no-sort"
            }]
        });

        $('#table-edit').Tabledit({
            url: "<?= site_url('admin/akademik/perubahan/Krs_mahasiswa/ubah_krs_nilai') ?>",
            hideIdentifier: true,
            restoreButton: false,
            onAjax: function (action, serialize) {
                if (action === 'edit') {
                    var match = serialize.match(/(?:^|&)kode_krs_detail=([^&]*)/);
                    lastKodeKrsDetail = match ? decodeURIComponent(match[1]) : '';
                }
                return true;
            },
            buttons: {
                confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirm'
                },
                save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Save'
                }
            },
            columns: {
                identifier: [1, 'kode_krs_detail'],
                editable: [
                    [7, 'edit_nilai_harian'],
                    [8, 'edit_nilai_uts'],
                    [9, 'edit_nilai_uas'],
                    [10, 'edit_nilai_akhir'],
                    [12, 'tidak_berhak', '{"N": "Berhak", "A": "TB"}']
                ]
            },
            onSuccess: function (data, textStatus, jqXHR) {
                if (data && data.status === true) {
                    if (data.action === 'edit' && lastKodeKrsDetail) {
                        $('#table-edit tbody tr').each(function () {
                            if ($(this).find('td:eq(1)').text() === lastKodeKrsDetail) {
                                $(this).find('td:eq(11)').text(data.grade || '-');
                            }
                        });
                        swal("Berhasil!", "Pembaruan selesai", "success");
                    } else if (data.action === 'delete') {
                        $('#table-edit tbody tr.tabledit-deleted-row').remove();
                        swal("Berhasil!", "Penghapusan berhasil", "success");
                    }
                } else {
                    swal("Gagal!", "Gagal memperbarui data", "error");
                }
            },
            onFail: function (jqXHR, textStatus, errorThrown) {
                swal("Gagal!", "Terjadi kesalahan saat menyimpan", "error");
            }
        });
    });
</script>
