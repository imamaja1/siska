<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header">
            <h4><i class="fa fa-table"></i> <?= e($judul_hasil) ?></h4>
            <small><?= e($sub_hasil) ?><?php if (empty($tampil_ta)) : ?> | ID Semester Feeder: <?= e($id_semester) ?><?php endif; ?></small>
        </div>
        <div class="box-body">
            <?php if (!empty($hasil['feeder_error'])) : ?>
                <div class="callout callout-warning flat">
                    <p><i class="fa fa-exclamation-triangle"></i> Catatan Feeder: <?= e($hasil['feeder_error']) ?></p>
                </div>
            <?php elseif (empty($hasil['feeder_total'])) : ?>
                <div class="callout callout-info flat">
                    <?php if (empty($tampil_ta)) : ?>
                        <p><i class="fa fa-info-circle"></i> Tidak ada data nilai pada Feeder untuk semester ini. Pastikan ID semester (<?= e($id_semester) ?>) tersedia di Feeder.</p>
                    <?php else : ?>
                        <p><i class="fa fa-info-circle"></i> Tidak ada data nilai pada Feeder untuk tahun akademik terkait.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($hasil['feeder_keys']) && !empty($hasil['feeder_error'])) : ?>
                <div class="callout callout-info flat">
                    <p>Field respons Feeder tidak dapat dikenali. Jalankan Test Koneksi di Konfig Feeder lalu periksa format respons.</p>
                </div>
            <?php endif; ?>

            <?php if (count($hasil['rows']) > 0) : ?>
            <?php
            $null_rows = [];
            if (!empty($tampil_null)) {
                foreach ($hasil['rows'] as $r) {
                    if ($r->status === 'Tidak ada di Feeder') {
                        $null_rows[] = $r;
                    }
                }
            }
            $siska_kosong_rows = [];
            if (!empty($tampil_null)) {
                foreach ($hasil['rows'] as $r) {
                    if ($r->status === 'Nilai SISKA kosong') {
                        $siska_kosong_rows[] = $r;
                    }
                }
            }
            ?>
            <?php if (!empty($tampil_null)) : ?>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab-audit-semua" role="tab" data-toggle="tab"><i class="fa fa-table"></i> Semua Data</a>
                </li>
                <li role="presentation">
                    <a href="#tab-audit-null" role="tab" data-toggle="tab" id="tab-null-link">
                        <i class="fa fa-exclamation-circle"></i> Cek Data Null Feeder (<?= count($null_rows) ?>)
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab-audit-null-siska" role="tab" data-toggle="tab">
                        <i class="fa fa-cloud"></i> Cek Data Null SISKA (<?= count($siska_kosong_rows) ?>)
                    </a>
                </li>
            </ul>
            <div class="tab-content" style="padding-top: 10px;">
                <div role="tabpanel" class="tab-pane active" id="tab-audit-semua">
            <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <?php if (!empty($tampil_ta)) : ?><th>Tahun Akademik</th><?php endif; ?>
                                    <th>Matakuliah</th>
                                    <th>Kelas</th>
                                    <th>Nilai SISKA (Angka)</th>
                                    <th>Nilai SISKA (Huruf)</th>
                                    <th>Nilai Feeder (Angka)</th>
                                    <th>Nilai Feeder (Huruf)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($hasil['rows'] as $row) : ?>
                                <tr class="<?= in_array($row->status, ['Berbeda', 'Berbeda (Kode Berubah)'], TRUE) ? 'danger' : '' ?>">
                                    <td align="center"><?= $no++ ?></td>
                                    <td><?= e($row->nim) ?></td>
                                    <td><?= e($row->nama_mahasiswa) ?></td>
                                    <?php if (!empty($tampil_ta)) : ?><td><?= e($row->tahun_akademik) ?> - <?= e($row->semester_label) ?></td><?php endif; ?>
                                    <td><?= e($row->kode_matakuliah) ?> - <?= e($row->nama_matakuliah) ?>
                                        <?php
                                        $kode_feeder = isset($row->kode_feeder) ? (string) $row->kode_feeder : '';
                                        if ($kode_feeder !== '' && $kode_feeder !== (string) $row->kode_matakuliah) :
                                        ?>
                                            <br><small class="text-muted"><i class="fa fa-exchange"></i> Feeder: <?= e($kode_feeder) ?> - <?= e($row->nama_mk_feeder ?? '') ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= !empty($row->nama_kelas) ? e($row->nama_kelas) : '-' ?>
                                        <?php
                                        $kelas_feeder = isset($row->kelas_feeder) ? (string) $row->kelas_feeder : '';
                                        if ($kelas_feeder !== '' && $kelas_feeder !== (string) $row->nama_kelas) :
                                        ?>
                                            <br><small class="text-muted">Feeder: <?= e($kelas_feeder) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td align="center"><?= e($row->nilai_siska) ?></td>
                                    <td align="center"><?= e($row->nilai_huruf_siska) ?></td>
                                    <td align="center"><?= e($row->nilai_angka) ?></td>
                                    <td align="center"><?= e($row->nilai_huruf) ?></td>
                                    <td align="center">
                                        <?php if ($row->status == 'Sesuai') : ?>
                                            <span class="label label-success">Sesuai</span>
                                        <?php elseif ($row->status == 'Sesuai (Kode Berubah)') : ?>
                                            <span class="label label-info">Sesuai (Kode Berubah)</span>
                                        <?php elseif ($row->status == 'Berbeda') : ?>
                                            <span class="label label-danger">Berbeda</span>
                                        <?php elseif ($row->status == 'Berbeda (Kode Berubah)') : ?>
                                            <span class="label label-danger">Berbeda (Kode Berubah)</span>
                                        <?php elseif ($row->status == 'Tidak ada di Feeder') : ?>
                                            <span class="label label-warning">Tidak ada di Feeder</span>
                                        <?php elseif ($row->status == 'Tidak ada di SISKA') : ?>
                                            <span class="label label-info">Tidak ada di SISKA</span>
                                        <?php else : ?>
                                            <span class="label label-default"><?= e($row->status) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

            <?php if (!empty($tampil_null)) : ?>
                </div><!-- /#tab-audit-semua -->

                <div role="tabpanel" class="tab-pane" id="tab-audit-null">
                    <h4><i class="fa fa-list"></i> Daftar Null (FEEDER)</h4>
                    <?php if (empty($null_rows)) : ?>
                        <div class="callout callout-success flat"><p>Tidak ada data null di Feeder.</p></div>
                    <?php else : ?>
                    <div style="margin-bottom: 10px;">
                        <button type="button" class="btn btn-primary flat" id="btn-cek-all">
                            <i class="fa fa-search"></i> Cek All
                        </button>
                        <span id="cek-all-progress" class="text-muted" style="margin-left: 10px;"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-null">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">No</th>
                                    <th width="60" class="text-center">Aksi</th>
                                    <th width="120">NIM</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th width="130">Kode Mata Kuliah</th>
                                    <th width="90" class="text-center">Kelas</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Angka</th>
                                    <th width="90" class="text-center">Nilai Huruf</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($null_rows as $nr) : ?>
                                <tr class="siska-row" data-nim="<?= e($nr->nim) ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-primary flat btn-cek-row" data-nim="<?= e($nr->nim) ?>">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </td>
                                    <td><?= e($nr->nim) ?></td>
                                    <td><?= e($nr->nama_matakuliah) ?></td>
                                    <td><?= e($nr->kode_matakuliah) ?></td>
                                    <td class="text-center"><?= e(!empty($nr->nama_kelas) ? $nr->nama_kelas : '-') ?></td>
                                    <td class="text-center"><?= e((isset($nr->sks) && $nr->sks !== '') ? (string) $nr->sks : '-') ?></td>
                                    <td class="text-center"><?= e($nr->nilai_siska) ?></td>
                                    <td class="text-center"><?= e($nr->nilai_huruf_siska) ?></td>
                                </tr>
                                <tr class="feeder-row">
                                    <td class="text-center"><em>Feeder</em></td>
                                    <td class="text-center">-</td>
                                    <td><?= e($nr->nim) ?></td>
                                    <td class="f-nama">-</td>
                                    <td class="f-kode">-</td>
                                    <td class="text-center f-kelas">-</td>
                                    <td class="text-center f-sks">-</td>
                                    <td class="text-center f-angka">-</td>
                                    <td class="text-center f-huruf">-</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div><!-- /#tab-audit-null -->

                <div role="tabpanel" class="tab-pane" id="tab-audit-null-siska">
                    <h4><i class="fa fa-cloud"></i> Daftar Null SISKA (status "Nilai SISKA kosong")</h4>
                    <?php if (empty($siska_kosong_rows)) : ?>
                        <div class="callout callout-success flat"><p>Tidak ada data berstatus "Nilai SISKA kosong".</p></div>
                    <?php else : ?>
                    <div style="margin-bottom: 10px;">
                        <button type="button" class="btn btn-primary flat" id="btn-cek-all-siska">
                            <i class="fa fa-search"></i> Cek All
                        </button>
                        <span id="cek-all-siska-progress" class="text-muted" style="margin-left: 10px;"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-null-siska">
                            <thead>
                                <tr>
                                    <th width="40" class="text-center">No</th>
                                    <th width="60" class="text-center">Aksi</th>
                                    <th width="120">NIM</th>
                                    <th>Nama</th>
                                    <th width="120">Kode MK</th>
                                    <th>Nama MK</th>
                                    <th width="80" class="text-center">Kelas</th>
                                    <th width="60" class="text-center">SKS</th>
                                    <th width="90" class="text-center">Nilai Akhir</th>
                                    <th width="70" class="text-center">Huruf</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($siska_kosong_rows as $sk) : ?>
                                <?php
                                $sk_kode  = !empty($sk->kode_feeder) ? $sk->kode_feeder : $sk->kode_matakuliah;
                                $sk_nama  = !empty($sk->nama_mk_feeder) ? $sk->nama_mk_feeder : $sk->nama_matakuliah;
                                $sk_kelas = !empty($sk->kelas_feeder) ? $sk->kelas_feeder : $sk->nama_kelas;
                                ?>
                                <tr class="siska-null-row"
                                    data-nim="<?= e($sk->nim) ?>"
                                    data-kode="<?= e($sk_kode) ?>"
                                    data-kode-siska="<?= e($sk->kode_matakuliah) ?>"
                                    data-nama="<?= e($sk_nama) ?>"
                                    data-nama-mhs="<?= e($sk->nama_mahasiswa) ?>"
                                    data-nama-siska="<?= e($sk->nama_matakuliah) ?>"
                                    data-kelas="<?= e($sk_kelas) ?>"
                                    data-kelas-siska="<?= e(!empty($sk->nama_kelas) ? $sk->nama_kelas : '-') ?>"
                                    data-sks="<?= e(isset($sk->sks) ? (string) $sk->sks : '') ?>"
                                    data-angka="<?= e($sk->nilai_angka) ?>"
                                    data-huruf="<?= e($sk->nilai_huruf) ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-info flat btn-cek-siska-row">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </td>
                                    <td><?= e($sk->nim) ?></td>
                                    <td><?= e($sk->nama_mahasiswa) ?></td>
                                    <td><?= e($sk_kode) ?></td>
                                    <td><?= e($sk_nama) ?></td>
                                    <td class="text-center"><?= e(!empty($sk_kelas) ? $sk_kelas : '-') ?></td>
                                    <td class="text-center"><?= e(isset($sk->sks) && $sk->sks !== '' ? (string) $sk->sks : '-') ?></td>
                                    <td class="text-center"><?= e($sk->nilai_angka) ?></td>
                                    <td class="text-center"><?= e($sk->nilai_huruf) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div><!-- /#tab-audit-null-siska -->
            </div><!-- /.tab-content -->

            <script>
            (function ($) {
                function esc(v) {
                    return $('<div>').text(v == null ? '' : String(v)).html();
                }

                var URL_KANDIDAT = '<?= site_url('admin/audit/kelas/kandidat_null_feeder') ?>';
                var CSRF_NAME    = '<?= $this->security->get_csrf_token_name() ?>';
                var CSRF_HASH    = '<?= $this->security->get_csrf_hash() ?>';
                var KODE_TA      = '<?= e($kode_tahun_akademik) ?>';
                var NAMA_MK      = '<?= e(!empty($null_rows) ? $null_rows[0]->nama_matakuliah : '') ?>';

                function cekBaris($siskaRow) {
                    var nim      = $siskaRow.attr('data-nim');
                    var $feeder  = $siskaRow.next('.feeder-row');

                    $feeder.removeClass('success warning');
                    $feeder.find('.f-nama, .f-kode, .f-kelas, .f-sks, .f-angka, .f-huruf').html('<i class="fa fa-spinner fa-spin"></i>');

                    var payload                 = {};
                    payload[CSRF_NAME]          = CSRF_HASH;
                    payload.kode_tahun_akademik = KODE_TA;
                    payload.nama_matakuliah     = NAMA_MK;
                    payload.nims                = [nim];

                    return $.ajax({
                        url: URL_KANDIDAT,
                        type: 'POST',
                        data: payload,
                        dataType: 'json'
                    }).done(function (res) {
                        if (res && res.error) {
                            $feeder.find('.f-nama').html('<span class="text-danger">' + esc(res.error) + '</span>');
                            $feeder.find('.f-kode, .f-kelas, .f-sks, .f-angka, .f-huruf').text('-');
                            return;
                        }
                        if (!res || !res.rows || res.rows.length === 0) {
                            $feeder.find('.f-nama, .f-kode, .f-kelas, .f-sks, .f-angka, .f-huruf').text('-');
                            return;
                        }

                        var r = res.rows[0];
                        $feeder.find('.f-nama').text(r.nama_mk ? r.nama_mk : '-');
                        $feeder.find('.f-kode').text(r.kode ? r.kode : '-');
                        $feeder.find('.f-kelas').text(r.kelas ? r.kelas : '-');
                        $feeder.find('.f-sks').text((r.sks !== '' && r.sks !== null) ? r.sks : '-');
                        $feeder.find('.f-angka').text((r.angka !== '' && r.angka !== null) ? r.angka : '-');
                        $feeder.find('.f-huruf').text((r.huruf !== '' && r.huruf !== null) ? r.huruf : '-');

                        if (r.metode === 'persis') {
                            $feeder.addClass('success');
                        } else if (r.metode === 'mirip') {
                            $feeder.addClass('warning');
                        }
                    }).fail(function () {
                        $feeder.find('.f-nama').html('<span class="text-danger">Gagal</span>');
                        $feeder.find('.f-kode, .f-kelas, .f-sks, .f-angka, .f-huruf').text('-');
                    });
                }

                $(document).off('click.cekNullRow', '.btn-cek-row').on('click.cekNullRow', '.btn-cek-row', function () {
                    cekBaris($(this).closest('tr.siska-row'));
                });

                $(document).off('click.cekNullAll', '#btn-cek-all').on('click.cekNullAll', '#btn-cek-all', function () {
                    var $btn = $(this);
                    var $rows = $('#tabel-null tr.siska-row');
                    if ($rows.length === 0) {
                        return;
                    }

                    $btn.prop('disabled', true);
                    var total = $rows.length;
                    var index = 0;

                    function next() {
                        if (index >= total) {
                            $('#cek-all-progress').text('Selesai.');
                            $btn.prop('disabled', false);
                            return;
                        }
                        $('#cek-all-progress').text('Memeriksa ' + (index + 1) + '/' + total + '...');
                        var $row = $rows.eq(index);
                        index++;
                        cekBaris($row).always(next);
                    }

                    next();
                });

                function fmtVal(v) {
                    if (v === null || v === undefined || v === '') {
                        return '-';
                    }
                    var n = parseFloat(v);
                    if (!isNaN(n) && (typeof v === 'number' || /^-?\d+(\.\d+)?$/.test(String(v).trim()))) {
                        return n.toFixed(2);
                    }
                    return esc(v);
                }

                function angkaNum(v) {
                    if (v === null || v === undefined || v === '') {
                        return null;
                    }
                    var n = parseFloat(v);
                    return isNaN(n) ? null : n;
                }

                function sumberRowSiska(label, nim, opts) {
                    opts = opts || {};
                    return '<tr class="sumber-row">'
                        + '<td colspan="2" class="text-left"><strong>' + esc(label) + '</strong></td>'
                        + '<td>' + esc(nim) + '</td>'
                        + '<td>' + (opts.namaMhs ? esc(opts.namaMhs) : '-') + '</td>'
                        + '<td>' + (opts.kode ? esc(opts.kode) : '-') + '</td>'
                        + '<td>' + (opts.nama ? esc(opts.nama) : '-') + '</td>'
                        + '<td class="text-center">' + (opts.kelas ? esc(opts.kelas) : '-') + '</td>'
                        + '<td class="text-center">' + (opts.sks !== undefined && opts.sks !== '' && opts.sks !== null ? esc(opts.sks) : '-') + '</td>'
                        + '<td class="text-center">' + fmtVal(opts.akhir) + '</td>'
                        + '<td class="text-center">' + fmtVal(opts.huruf) + '</td>'
                        + '</tr>';
                }

                function cekBarisSiska($base) {
                    var nim = $base.attr('data-nim');
                    $base.removeClass('success warning');
                    $base.nextUntil('tr.siska-null-row', 'tr.sumber-row').remove();
                    $base.after('<tr class="sumber-row"><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</td></tr>');

                    var payload                 = {};
                    payload[CSRF_NAME]          = CSRF_HASH;
                    payload.kode_tahun_akademik = KODE_TA;
                    payload.kode_matakuliah     = $base.attr('data-kode-siska');
                    payload.nim                 = nim;

                    return $.ajax({
                        url: '<?= site_url('admin/audit/kelas/cek_null_siska') ?>',
                        type: 'POST',
                        data: payload,
                        dataType: 'json'
                    }).done(function (res) {
                        $base.nextUntil('tr.siska-null-row', 'tr.sumber-row').remove();

                        var khs        = (res && res.khs) ? res.khs : [];
                        var dummy      = (res && res.dummy) ? res.dummy : [];
                        var dummyNilai = (res && res.dummy_nilai) ? res.dummy_nilai : [];
                        var hanyaKhs   = (res && res.hanya_khs) ? true : false;

                        var khsAkhir = (khs.length > 0) ? khs[0].nilai_akhir : null;
                        var fNum = angkaNum($base.attr('data-angka'));
                        var kNum = angkaNum(khsAkhir);
                        var colorClass = (fNum !== null && kNum !== null && Math.abs(fNum - kNum) < 0.001) ? 'success' : 'warning';
                        $base.addClass(colorClass);

                        var ident = {
                            namaMhs: $base.attr('data-nama-mhs'),
                            kode:    $base.attr('data-kode-siska'),
                            nama:    $base.attr('data-nama-siska'),
                            kelas:   $base.attr('data-kelas-siska'),
                            sks:     $base.attr('data-sks')
                        };

                        function hasVal(v) {
                            return v !== null && v !== undefined && v !== '';
                        }

                        var html = '';
                        var found = false;

                        for (var i = 0; i < khs.length; i++) {
                            if (!hasVal(khs[i].nilai_akhir)) { continue; }
                            found = true;
                            html += sumberRowSiska('KHS', nim, {
                                namaMhs: ident.namaMhs, kode: ident.kode, nama: ident.nama, kelas: ident.kelas, sks: ident.sks,
                                akhir: khs[i].nilai_akhir, huruf: khs[i].nilai_huruf
                            });
                        }

                        if (!hanyaKhs) {
                            for (var j = 0; j < dummy.length; j++) {
                                if (!hasVal(dummy[j].na)) { continue; }
                                found = true;
                                html += sumberRowSiska('Dummy Update (L' + dummy[j].level + ')', nim, {
                                    namaMhs: ident.namaMhs, kode: ident.kode, nama: ident.nama, kelas: ident.kelas, sks: ident.sks,
                                    akhir: dummy[j].na, huruf: dummy[j].nilai_huruf
                                });
                            }

                            for (var k = 0; k < dummyNilai.length; k++) {
                                if (!hasVal(dummyNilai[k].dummy_na)) { continue; }
                                found = true;
                                html += sumberRowSiska('Dummy Nilai', nim, {
                                    namaMhs: ident.namaMhs, kode: ident.kode, nama: ident.nama, kelas: ident.kelas, sks: ident.sks,
                                    akhir: dummyNilai[k].dummy_na, huruf: dummyNilai[k].nilai_huruf
                                });
                            }
                        }

                        if (!found) {
                            html += '<tr class="sumber-row"><td colspan="10" class="text-left text-muted">Tidak ada nilai pada KHS/Dummy.</td></tr>';
                        }

                        $base.after(html);
                    }).fail(function () {
                        $base.nextUntil('tr.siska-null-row', 'tr.sumber-row').remove();
                        $base.after('<tr class="sumber-row"><td colspan="10" class="text-center text-danger">Gagal memuat data.</td></tr>');
                    });
                }

                $(document).off('click.cekNullSiskaRow', '.btn-cek-siska-row').on('click.cekNullSiskaRow', '.btn-cek-siska-row', function () {
                    cekBarisSiska($(this).closest('tr.siska-null-row'));
                });

                $(document).off('click.cekNullSiskaAll', '#btn-cek-all-siska').on('click.cekNullSiskaAll', '#btn-cek-all-siska', function () {
                    var $btn = $(this);
                    var $rows = $('#tabel-null-siska tr.siska-null-row');
                    if ($rows.length === 0) {
                        return;
                    }

                    $btn.prop('disabled', true);
                    var total = $rows.length;
                    var index = 0;

                    function next() {
                        if (index >= total) {
                            $('#cek-all-siska-progress').text('Selesai.');
                            $btn.prop('disabled', false);
                            return;
                        }
                        $('#cek-all-siska-progress').text('Memeriksa ' + (index + 1) + '/' + total + '...');
                        var $row = $rows.eq(index);
                        index++;
                        cekBarisSiska($row).always(next);
                    }

                    next();
                });
            })(jQuery);
            </script>
            <?php endif; ?>

            <?php else : ?>
            <div class="callout callout-info flat">
                <h4><i class="fa fa-info-circle"></i> Informasi!</h4>
                <p>Tidak ada data nilai untuk filter yang dipilih.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
