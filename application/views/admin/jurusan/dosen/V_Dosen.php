<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/jurusan/dosen/tambah'); ?>" class="btn btn-xs btn-primary flat"> <i class="fa fa-plus-circle"></i> Tambah</a>
        <?php if (count($data_dosen) > 0): ?>
		<button class="btn btn-flat btn-default btn-xs ">Terdapat <b><?= e($jumlah_data) ?> Record</b></button>
            <div class="pull-right">
                <?= $halaman ?>
            </div>
        <?php else: ?>

        <?php endif; ?>
    </div>
</div>
<div class="box box-primary flat">
    <div class="box-body table-responsive">
        <table class="table demo-table data-table">
            <thead>
                <tr>
                    <th id="th">No.</th>
                    <th id="th">NIK</th>
                    <th id="th">Nama Dosen</th>
                    <th id="th">Homebase</th>
                    <th id="th">Status Dosen</th>
                    <th id="th">Status Login</th>
                    <th id="th">Alamat Email</th>
                    <th id="th">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1 + $this->uri->segment(5);
                foreach ($data_dosen as $d) {
                    ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ ?>.</td>
                        <td id="nik-<?= e($d->kode_dosen) ?>"><?= e($d->nik) ?></td>
                        <td id="nama-dosen-<?= e($d->kode_dosen) ?>"><?= e($d->nama_dosen) ?></td>
                        <td ><?= e($d->nama_program_studi) ?></td>
                        <td style="text-align: center;"><?php
                            $status_dosen = $d->status_dosen;
                            if ($status_dosen == "T") {
                                echo "Tetap";
                            } else {
                                echo "Tidak Tetap";
                            }
                            ?></td>
                        <td style="text-align: center;"><?php
                            $status_login = $d->status_login;
                            if ($status_login == "A") {
                                echo "Aktif";
                            } else {
                                echo "Tidak Aktif";
                            }
                            ?></td>
                        <td id="alamat-email-<?= e($d->kode_dosen) ?>"><?= e($d->alamat_email) ?></td>
                        <td align="center" width="220">
                            <a href="#" onclick="gantipassword('<?= e($d->kode_dosen) ?>')" class="btn btn-xs btn-primary flat"><i class="fa fa-refresh"></i> Reset Sandi</a>
                            <a href="<?= site_url('admin/jurusan/dosen/edit/' . $d->kode_dosen); ?>" class="btn btn-xs btn-info flat"><i class="fa fa-edit"></i> Ubah</a>
                            <a href="#" class="btn btn-xs btn-danger flat" onclick="hapus('<?= e($d->kode_dosen) ?>')"><i class="fa fa-trash"></i> Hapus</a>
                        </td>

                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function hapus(kode_dosen) {
        swal({
            title: '',
            text: "Anda yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: "<?= site_url('admin/jurusan/dosen/hapus') ?>/" + kode_dosen,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        swal('Success!', res.msg, 'success').then(function() {
                            location.reload();
                        });
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    }

    function gantipassword(kode_dosen) {
        swal({
            title: "",
            text: "Anda yakin ingin mereset password dosen ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: 'red',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
            $.ajax({
                url: "<?= site_url('admin/jurusan/dosen/generate_sandi') ?>/" + kode_dosen,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        swal({
                            title: '<i class="fa fa-check-circle" style="color:#27ae60;font-size:28px;vertical-align:middle;margin-right:8px;"></i> Password Berhasil Diubah',
                            html:
                                '<style>' +
                                    '.siska-modal-card{background:#fff;border:1px solid #e9ecef;border-radius:8px;padding:12px 14px;margin-bottom:8px}' +
                                    '.siska-modal-card:last-child{margin-bottom:0}' +
                                    '.siska-modal-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#95a5a6;margin-bottom:3px}' +
                                    '.siska-modal-val{font-size:14px;font-weight:600;color:#2c3e50;word-break:break-all}' +
                                    '.siska-modal-val-email{color:#2980b9}' +
                                    '.siska-modal-pw-box{display:flex;align-items:center;gap:10px;background:#f8f9fa;border:1px solid #dee2e6;border-radius:6px;padding:10px 12px;margin-top:4px}' +
                                    '.siska-modal-pw-text{flex:1;font-family:monospace;font-size:18px;font-weight:700;letter-spacing:1.5px;color:#c0392b;word-break:break-all}' +
                                    '.siska-modal-copy-btn{flex-shrink:0;width:36px;height:36px;border-radius:50%;border:1px solid #dee2e6;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#6c757d;font-size:14px;transition:all .15s}' +
                                    '.siska-modal-copy-btn:hover{background:#e9ecef;color:#2c3e50}' +
                                    '.siska-modal-icon{font-size:15px;color:#7f8c8d;margin-right:8px;vertical-align:middle;width:18px;text-align:center;display:inline-block}' +
                                    '@media(max-width:500px){' +
                                        '.siska-modal-pw-box{flex-direction:column;align-items:stretch}' +
                                        '.siska-modal-copy-btn{align-self:flex-end;width:auto;border-radius:4px;padding:6px 12px;font-size:12px}' +
                                        '.siska-modal-pw-text{font-size:16px}' +
                                    '}' +
                                '</style>' +
                                '<div style="text-align:left;padding:4px 0;">' +
                                    '<div class="siska-modal-card">' +
                                        '<div class="siska-modal-label"><i class="fa fa-user siska-modal-icon"></i>Nama Dosen</div>' +
                                        '<div class="siska-modal-val">' + res.nama_dosen + '</div>' +
                                    '</div>' +
                                    '<div class="siska-modal-card">' +
                                        '<div class="siska-modal-label"><i class="fa fa-envelope siska-modal-icon"></i>Email</div>' +
                                        '<div class="siska-modal-val siska-modal-val-email">' + res.alamat_email + '</div>' +
                                    '</div>' +
                                    '<div class="siska-modal-card">' +
                                        '<div class="siska-modal-label"><i class="fa fa-key siska-modal-icon"></i>Password Baru</div>' +
                                        '<div class="siska-modal-pw-box">' +
                                            '<span class="siska-modal-pw-text" id="siska-pw-text">' + res.password_string + '</span>' +
                                            '<button type="button" class="siska-modal-copy-btn" id="siska-copy-btn"><i class="fa fa-clipboard"></i></button>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>',
                            type: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                            didOpen: function(modal) {
                                var copyBtn = modal.querySelector('#siska-copy-btn');
                                var pwText = modal.querySelector('#siska-pw-text');
                                if (copyBtn && pwText) {
                                    copyBtn.addEventListener('click', function() {
                                        var self = this;
                                        navigator.clipboard.writeText(pwText.innerText).then(function() {
                                            self.innerHTML = '<i class="fa fa-check"></i>';
                                            self.style.color = '#27ae60';
                                            setTimeout(function() {
                                                self.innerHTML = '<i class="fa fa-clipboard"></i>';
                                                self.style.color = '#6c757d';
                                            }, 2000);
                                        });
                                    });
                                }
                            }
                        });
                    } else {
                        swal('Gagal!', res.msg, 'error');
                    }
                }
            });
        });
    }
</script>