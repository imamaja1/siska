<div class="col-md-12">
    <div class="box box-primary flat">
        <div class="box-header">
            <h4><i class="fa fa-file-text-o"></i> PETIKAN NILAI MAHASISWA (FEEDER)</h4>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <tr>
                            <td width="180"><strong>NAMA</strong></td>
                            <td width="10"><strong>:</strong></td>
                            <td><?= e($header['nama_mahasiswa'] !== '' ? $header['nama_mahasiswa'] : '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>NIM</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e($header['nim']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>ANGKATAN</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e($header['angkatan'] !== '' ? $header['angkatan'] : '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>PROGRAM STUDI</strong></td>
                            <td><strong>:</strong></td>
                            <td><?= e($header['nama_program_studi'] !== '' ? $header['nama_program_studi'] : '-') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-detail" role="tab" data-toggle="tab"><i class="fa fa-list"></i> Detail Nilai</a>
                </li>
                <li role="presentation">
                    <a href="#tab-petikan" role="tab" data-toggle="tab"><i class="fa fa-file-text"></i> Petikan Nilai</a>
                </li>
                <li role="presentation">
                    <a href="#tab-banding" role="tab" data-toggle="tab">
                        <i class="fa fa-exchange"></i> Perbandingan SISKA vs Feeder
                        <?php if (!empty($perbandingan)) : ?>
                            <span class="label label-danger"><?= (int) ($summary_banding['berbeda'] + $summary_banding['tidak_ada_siska'] + $summary_banding['tidak_ada_feeder']) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding-top: 15px;">
                <!-- Tab Detail Nilai -->
                <div role="tabpanel" class="tab-pane active" id="tab-detail">
                    <?php foreach ($semester as $smt) : ?>
                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="9" class="bg-primary">
                                        <?= e($smt['nama_semester'] !== '' ? $smt['nama_semester'] : $smt['id_semester']) ?>
                                        <?php if (isset($smt['id_semester']) && $smt['id_semester'] === 'KONVERSI') : ?>
                                            <span class="label label-warning">Konversi</span>
                                        <?php endif; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th width="40" class="text-center">No</th>
                                    <th width="120" class="text-center">Kode MK</th>
                                    <th>Matakuliah</th>
                                    <th width="80" class="text-center">Kelas</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Angka</th>
                                    <th width="90" class="text-center">Nilai Huruf</th>
                                    <th width="80" class="text-center">Indeks</th>
                                    <th width="90" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($smt['items'] as $item) : ?>
                                <tr class="<?= !empty($item['highlight']) ? 'warning' : '' ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= e($item['kode_mata_kuliah']) ?></td>
                                    <td><?= e($item['nama_mata_kuliah']) ?></td>
                                    <td class="text-center"><?= e($item['nama_kelas'] !== '' ? $item['nama_kelas'] : '-') ?></td>
                                    <td class="text-center"><?= e($item['sks_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_angka_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_huruf_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_indeks_tampil']) ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($item['status'])) : ?>
                                            <span class="label label-primary"><?= e($item['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Jumlah</th>
                                    <th class="text-center"><?= number_format($smt['total_sks'], 2) ?></th>
                                    <th colspan="2" class="text-right">SKSN</th>
                                    <th class="text-center"><?= number_format($smt['total_sksn'], 2) ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-right">IPS</th>
                                    <th class="text-center"><?= number_format($smt['ips'], 2) ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Tab Petikan Nilai (GetTranskripMahasiswa) -->
                <div role="tabpanel" class="tab-pane" id="tab-petikan">
                    <p class="text-muted"><i class="fa fa-info-circle"></i> Sumber: Feeder <code>GetTranskripMahasiswa</code> (memuat nilai konversi/transfer).</p>
                    <?php if (!empty($transkrip_error)) : ?>
                        <div class="callout callout-warning flat"><p>Catatan transkrip: <?= e($transkrip_error) ?></p></div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="40" class="text-center">No</th>
                                    <th width="120" class="text-center">Kode MK</th>
                                    <th>Matakuliah</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Angka</th>
                                    <th width="90" class="text-center">Nilai Huruf</th>
                                    <th width="80" class="text-center">Indeks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($petikan)) : ?>
                                <tr><td colspan="7" class="text-center">Tidak ada data transkrip.</td></tr>
                                <?php else : ?>
                                <?php $no = 1; foreach ($petikan as $item) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= e($item['kode_mata_kuliah']) ?></td>
                                    <td><?= e($item['nama_mata_kuliah']) ?></td>
                                    <td class="text-center"><?= e($item['sks_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_angka_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_huruf_tampil']) ?></td>
                                    <td class="text-center"><?= e($item['nilai_indeks_tampil']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-right" width="70%">TOTAL SKS</th>
                                <td class="text-center"><?= number_format($petikan_total_sks, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">TOTAL SKSN</th>
                                <td class="text-center"><?= number_format($petikan_total_sksn, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-right">IPK</th>
                                <td class="text-center"><strong><?= number_format($petikan_ipk, 2) ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Tab Perbandingan SISKA vs Feeder -->
                <div role="tabpanel" class="tab-pane" id="tab-banding">
                    <div style="margin-bottom:10px;">
                        <span class="label label-success">Sesuai: <?= (int) ($summary_banding['sesuai'] ?? 0) ?></span>
                        <span class="label label-danger">Berbeda: <?= (int) ($summary_banding['berbeda'] ?? 0) ?></span>
                        <span class="label label-warning">SISKA belum ada nilai: <?= (int) ($summary_banding['siska_kosong'] ?? 0) ?></span>
                        <span class="label label-info">Feeder belum ada nilai: <?= (int) ($summary_banding['feeder_kosong'] ?? 0) ?></span>
                        <span class="label label-warning">Tidak ada di SISKA: <?= (int) ($summary_banding['tidak_ada_siska'] ?? 0) ?></span>
                        <span class="label label-info">Tidak ada di Feeder: <?= (int) ($summary_banding['tidak_ada_feeder'] ?? 0) ?></span>
                    </div>
                    <?php if (empty($perbandingan_grup)) : ?>
                        <p class="text-muted">Tidak ada data perbandingan.</p>
                    <?php else : ?>
                        <?php foreach ($perbandingan_grup as $grup) : ?>
                            <?php $this->load->view('admin/audit/V_feeder_petikan_banding', ['grup' => $grup]); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-dummy" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button>
                <h4 class="modal-title"><i class="fa fa-search"></i> Pencarian Nilai Dummy SISKA</h4>
            </div>
            <div class="modal-body" id="modal-dummy-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default flat" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
(function ($) {
    var URL_CEK   = '<?= site_url('admin/audit/petikan/cek_null_siska') ?>';
    var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
    var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';

    function esc(v) {
        return $('<div>').text(v == null ? '' : String(v)).html();
    }

    function hasVal(v) {
        return v !== null && v !== undefined && v !== '';
    }

    function fmtVal(v) {
        if (!hasVal(v)) {
            return '-';
        }
        var n = parseFloat(v);
        if (!isNaN(n) && (typeof v === 'number' || /^-?\d+(\.\d+)?$/.test(String(v).trim()))) {
            return n.toFixed(2);
        }
        return esc(v);
    }

    function renderTable(title, headers, rows, renderRow) {
        var html = '<h5><strong>' + esc(title) + '</strong></h5>';
        if (!rows || rows.length === 0) {
            return html + '<p class="text-muted">Tidak ada data.</p>';
        }
        html += '<div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr>';
        for (var i = 0; i < headers.length; i++) {
            html += '<th class="text-center">' + esc(headers[i]) + '</th>';
        }
        html += '</tr></thead><tbody>';
        for (var j = 0; j < rows.length; j++) {
            html += renderRow(rows[j]);
        }
        return html + '</tbody></table></div>';
    }

    $('body > #modal-dummy').remove();
    $('#modal-dummy').appendTo('body');

    $(document).off('click.cekDummy', '.btn-cek-dummy').on('click.cekDummy', '.btn-cek-dummy', function () {
        var $btn  = $(this);
        var nim   = $btn.data('nim');
        var ta    = $btn.data('ta');
        var kode  = $btn.data('kode');
        var $body = $('#modal-dummy-body');

        $body.html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</p>');
        $('#modal-dummy').modal('show');

        var payload                 = {};
        payload[CSRF_NAME]          = CSRF_HASH;
        payload.nim                 = nim;
        payload.kode_tahun_akademik = ta;
        payload.kode_matakuliah     = kode;

        $.ajax({
            url: URL_CEK,
            type: 'POST',
            data: payload,
            dataType: 'json'
        }).done(function (res) {
            var khs        = (res && res.khs) ? res.khs : [];
            var dummy      = (res && res.dummy) ? res.dummy : [];
            var dummyNilai = (res && res.dummy_nilai) ? res.dummy_nilai : [];
            var hanyaKhs   = (res && res.hanya_khs) ? true : false;

            var html = '<p class="text-muted">NIM <strong>' + esc(nim) + '</strong> | Kode MK <strong>' + esc(kode) + '</strong> | TA <strong>' + esc(ta) + '</strong></p>';

            html += renderTable('KHS', ['Semester', 'Harian', 'UTS', 'UAS', 'Nilai Akhir', 'Huruf'], khs, function (r) {
                return '<tr>'
                    + '<td class="text-center">' + esc(r.semester) + '</td>'
                    + '<td class="text-center">' + fmtVal(r.nilai_harian) + '</td>'
                    + '<td class="text-center">' + fmtVal(r.nilai_uts) + '</td>'
                    + '<td class="text-center">' + fmtVal(r.nilai_uas) + '</td>'
                    + '<td class="text-center">' + fmtVal(r.nilai_akhir) + '</td>'
                    + '<td class="text-center">' + (hasVal(r.nilai_huruf) ? esc(r.nilai_huruf) : '-') + '</td>'
                    + '</tr>';
            });

            if (hanyaKhs) {
                html += '<div class="callout callout-info flat"><p>Angkatan ini hanya dicek pada KHS (tanpa dummy).</p></div>';
            } else {
                html += renderTable('Dummy Update (dummy_update_nilai)', ['Level', 'Harian', 'UTS', 'UAS', 'NA', 'Ket', 'Huruf'], dummy, function (r) {
                    return '<tr>'
                        + '<td class="text-center">' + esc(r.level) + '</td>'
                        + '<td class="text-center">' + fmtVal(r.harian) + '</td>'
                        + '<td class="text-center">' + fmtVal(r.uts) + '</td>'
                        + '<td class="text-center">' + fmtVal(r.uas) + '</td>'
                        + '<td class="text-center">' + fmtVal(r.na) + '</td>'
                        + '<td class="text-center">' + (hasVal(r.ket) ? esc(r.ket) : '-') + '</td>'
                        + '<td class="text-center">' + (hasVal(r.nilai_huruf) ? esc(r.nilai_huruf) : '-') + '</td>'
                        + '</tr>';
                });

                html += renderTable('Dummy Nilai (dummy_nilai)', ['Harian', 'UTS', 'UAS', 'NA', 'Huruf'], dummyNilai, function (r) {
                    return '<tr>'
                        + '<td class="text-center">' + fmtVal(r.dummy_harian) + '</td>'
                        + '<td class="text-center">' + fmtVal(r.dummy_uts) + '</td>'
                        + '<td class="text-center">' + fmtVal(r.dummy_uas) + '</td>'
                        + '<td class="text-center">' + fmtVal(r.dummy_na) + '</td>'
                        + '<td class="text-center">' + (hasVal(r.nilai_huruf) ? esc(r.nilai_huruf) : '-') + '</td>'
                        + '</tr>';
                });
            }

            $body.html(html);
        }).fail(function () {
            $body.html('<div class="callout callout-danger flat"><p>Gagal memuat data.</p></div>');
        });
    });
})(jQuery);
</script>
