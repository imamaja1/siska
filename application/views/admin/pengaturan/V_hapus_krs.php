<?= $this->session->flashdata('pesan') ?>
<div class="box box-solid flat">
    <div class="box-header">
        <h4><i class="fa fa-search" style="color: #1b6d85"></i><strong> Cari Mahasiswa</strong></h4>
        <hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="<?= site_url('admin/pengaturan/pengaturan/hapus_krs') ?>" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group">
                <label class="control-label col-sm-2">NIM Mahasiswa</label>
                <div class="col-sm-4">
                    <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM Mahasiswa" value="<?= e($nim) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-sm flat" type="submit"><i class="fa fa-search"></i> Cari Data KRS</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($data_mhs !== null): ?>
<div class="box box-primary flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-user" style="color: #1b6d85"></i><strong> Data Mahasiswa</strong></h4>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th width="150">NIM</th>
                        <td><?= e($data_mhs->nim) ?></td>
                    </tr>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <td><?= e($data_mhs->nama_mahasiswa) ?></td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td><?= e($data_mhs->nama_program_studi) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="box box-danger flat">
    <div class="box-header with-border">
        <h4 class="box-title"><i class="fa fa-list-alt text-red"></i><strong> Daftar KRS Mahasiswa</strong></h4>
    </div>
    <div class="box-body">
        <?php if (!empty($data_krs)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-gray-light">
                        <th width="30" class="text-center">No.</th>
                        <th>Tahun Akademik</th>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Jumlah MK</th>
                        <th class="text-center">Total SKS</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data_krs as $row): 
                        $ta_label = e($row->tahun_akademik ?? '-') . ' (' . ($row->ta_semester == 0 ? 'Genap' : 'Ganjil') . ')';
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?>.</td>
                        <td><strong><?= $ta_label ?></strong></td>
                        <td class="text-center"><?= e($row->semester) ?></td>
                        <td class="text-center"><span class="badge bg-aqua"><?= (int) $row->total_mk ?> MK</span></td>
                        <td class="text-center"><span class="badge bg-blue"><?= (int) $row->total_sks ?> SKS</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-xs flat btn-detail-mk" data-kode="<?= (int) $row->kode_krs ?>" data-ta="<?= $ta_label ?>">
                                <i class="fa fa-eye"></i> Detail MK
                            </button>
                            <button type="button" class="btn btn-danger btn-xs flat btn-hapus-krs" data-kode="<?= (int) $row->kode_krs ?>" data-ta="<?= $ta_label ?>">
                                <i class="fa fa-trash"></i> Hapus KRS
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="callout callout-warning flat" style="margin-bottom: 0;">
            <h4><i class="fa fa-warning"></i> Perhatian</h4>
            <p>Tidak ditemukan data KRS untuk mahasiswa ini.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Detail MK -->
<div class="modal fade" id="modal-detail-mk" role="dialog" aria-labelledby="modalDetailLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Detail Matakuliah KRS - <span id="detail-ta-title"></span></b></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-gray-light">
                                <th width="30" class="text-center">No.</th>
                                <th width="120">Kode MK</th>
                                <th>Nama Matakuliah</th>
                                <th width="60" class="text-center">SKS</th>
                                <th width="80" class="text-center">Status</th>
                                <th width="90" class="text-center">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody id="detail-mk-tbody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Form Hidden Hapus KRS -->
<form id="form-hapus-krs" action="<?= site_url('admin/pengaturan/pengaturan/proses_hapus_krs') ?>" method="POST" style="display:none;">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="kode_krs" id="hapus-kode-krs">
    <input type="hidden" name="nim" value="<?= e($nim) ?>">
</form>

<script type="text/javascript">
$(document).ready(function() {
    $('.btn-detail-mk').on('click', function(e) {
        e.preventDefault();
        var kodeKrs = $(this).data('kode');
        var labelTa = $(this).data('ta');
        $('#detail-ta-title').text(labelTa);
        $('#detail-mk-tbody').html('<tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat detail matakuliah...</td></tr>');
        $('#modal-detail-mk').modal('show');

        $.ajax({
            url: "<?= site_url('admin/pengaturan/pengaturan/detail_krs_ajax/') ?>" + kodeKrs,
            type: "GET",
            dataType: "json",
            success: function(res) {
                if (res.status && res.data && res.data.length > 0) {
                    var html = '';
                    $.each(res.data, function(idx, item) {
                        var statusLabel = item.status_mk === 'B' ? '<span class="label label-success">Baru</span>' : (item.status_mk === 'U' ? '<span class="label label-warning">Ulang</span>' : item.status_mk);
                        html += '<tr>' +
                            '<td class="text-center">' + (idx + 1) + '.</td>' +
                            '<td>' + $('<div>').text(item.kode_matakuliah || '-').html() + '</td>' +
                            '<td>' + $('<div>').text(item.nama_matakuliah || '-').html() + '</td>' +
                            '<td class="text-center">' + (item.sks || '0') + '</td>' +
                            '<td class="text-center">' + statusLabel + '</td>' +
                            '<td class="text-center">' + (item.nilai_akhir !== null ? item.nilai_akhir : '<span class="text-muted">-</span>') + '</td>' +
                            '</tr>';
                    });
                    $('#detail-mk-tbody').html(html);
                } else {
                    $('#detail-mk-tbody').html('<tr><td colspan="6" class="text-center text-muted">Tidak ada matakuliah dalam KRS ini.</td></tr>');
                }
            },
            error: function() {
                $('#detail-mk-tbody').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat detail matakuliah.</td></tr>');
            }
        });
    });

    $('.btn-hapus-krs').on('click', function(e) {
        e.preventDefault();
        var kodeKrs = $(this).data('kode');
        var labelTa = $(this).data('ta');

        swal({
            title: 'Hapus KRS ini?',
            text: 'KRS ' + labelTa + ' beserta seluruh data matakuliah dan nilainya akan dihapus permanen!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function() {
            $('#hapus-kode-krs').val(kodeKrs);
            $('#form-hapus-krs').submit();
        });
    });
});
</script>
<?php endif; ?>
