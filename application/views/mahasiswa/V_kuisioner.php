<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pie-chart"></i> Kuisioner</h3>
    </div>
    <div class="box-body">
        <?php if ($this->session->flashdata('info')) : ?>
            <?= $this->session->flashdata('info') ?>
        <?php endif; ?>
        <?php if ($status_kuisioner == 'A') :
            if (count($data) > 0) : ?>
                <h4><i class="fa fa-television"></i> Kuisioner Proses Belajar Mengajar (PBM)</h4>
                <div class="table-responsive mhs-table-wrap">
                    <table class="table mhs-table">
                        <thead>
                        <tr>
                            <th class="mhs-c-no">No.</th>
                            <th>Kode Matakuliah</th>
                            <th>Matakuliah</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($data as $row) : ?>
                            <tr>
                                <td align="center"><?= $i++ ?>.</td>
                                <td align="center"><?= e($row->kode_matakuliah) ?></td>
                                <td><?= e($row->nama_matakuliah) ?></td>
                                <td align="center">
                                    <a href="<?= site_url('mahasiswa/kuisioner/isi_kuisioner/' . $row->kelas_mahasiswa_id) ?>"
                                       data-toggle="modal" data-target="#myModal<?= $row->kelas_mahasiswa_id ?>"
                                       class="btn btn-danger flat btn-sm">
                                        <i class="fa fa-pencil"></i> Isi Kuisioner
                                    </a>
                                </td>
                            </tr>
                            <div class="modal fade" id="myModal<?= $row->kelas_mahasiswa_id ?>" tabindex="-1"
                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="callout callout-success text-center" style="padding: 24px 20px; border-radius: 12px; margin: 16px 0;">
                    <div style="width: 48px; height: 48px; border-radius: 9999px; background: #ECFDF5; border: 2px solid #A7F3D0; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 10px;">
                        <i class="fa fa-check"></i>
                    </div>
                    <h4 style="font-size: 16px; font-weight: 800; color: #065F46; margin: 0 0 4px;">Terima Kasih!</h4>
                    <p style="font-size: 13px; color: #047857; margin: 0;">Anda sudah selesai melakukan pengisian kuisioner Proses Belajar Mengajar (PBM).</p>
                </div>
            <?php endif; ?>
<!--        untuk angkatan baru-->
<?php //if ( substr($this->session->userdata('nim'),0,2) !== '24') : ?>
<?php if ( true ) : ?>
<!--        end untuk angkatan baru-->
            <h4><i class="fa fa-server"></i> Kuisioner Kepuasan Pelayanan</h4>
            <?php if (!$axis) : ?>
            <p align="center"><b>KUISIONER(V 2.0) UNTUK MAHASISWA KEPUASAN PELAYANAN</b></p>
            <p align="justify"><i>Kuisioner ini merupakan salah satu bentuk kerjasama dan partisipasi bersama dalam
                    upaya
                    menigkatkan mutu pelayanan setiap
                    bagian. Pendapat dan masukan dari kuisioner ini merupakan salah satu mekanisme evaluasi terhadap
                    pelaksanaan kegiatan pelayanan
                    setiap bagian berdasar Sistem Manajemen Mutu Universitas Bumigora.</i></p>
            <p align="center"><strong>PETUNJUK:</strong> Pilihlah salah satu radio button pada kolom yang sesuai dimana
                (<b>1</b>
                = Kurang Baik; <b>2</b> = Cukup baik; <b>3</b> = Baik; <b>4</b> = Sangat Baik)
            </p>
            <form id="form-kuisioner-layanan" action="<?= site_url('mahasiswa/kuisioner/simpan_layanan') ?>"
                  method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="table-responsive mhs-table-wrap">
                    <table class="table mhs-table">
                        <thead>
                        <tr>
                            <th>Bagian</th>
                            <th>Pertanyaan</th>
                            <th class="mhs-c-num">1</th>
                            <th class="mhs-c-num">2</th>
                            <th class="mhs-c-num">3</th>
                            <th class="mhs-c-num">4</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($soal_layanan as $item) : ?>
                            <tr style="horiz-align: center;">
                                <td rowspan="<?= $item['rowspan'] + 1 ?>"><strong>Pelayanan
                                        Bagian <?= e($item['nama_bagian']) ?> </strong></td>
                            </tr>
                            <?php foreach ($item['data'] as $row) : ?>
                                <tr>
                                    <td><?= e($row->soal) ?></td>
                                    <td align="center"><label><input required type="radio"
                                                                     name="hasil[<?= $row->id_soal_pelayanan ?>]"
                                                                     value="1"></label>
                                    </td>
                                    <td align="center"><label><input required type="radio"
                                                                     name="hasil[<?= $row->id_soal_pelayanan ?>]"
                                                                     value="2"></label>
                                    </td>
                                    <td align="center"><label><input required type="radio"
                                                                     name="hasil[<?= $row->id_soal_pelayanan ?>]"
                                                                     value="3"></label>
                                    </td>
                                    <td align="center"><label><input required type="radio"
                                                                     name="hasil[<?= $row->id_soal_pelayanan ?>]"
                                                                     value="4"></label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label>Silahkan berikan masukan untuk pelayanan (<i>Jika ada</i>) :</label>
                        <textarea name="masukan" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="form-group pull-right">
                        <div id="loading">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php else : ?>
            <div class="callout callout-success text-center" style="padding: 24px 20px; border-radius: 12px; margin: 16px 0;">
                <div style="width: 48px; height: 48px; border-radius: 9999px; background: #ECFDF5; border: 2px solid #A7F3D0; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 10px;">
                    <i class="fa fa-check"></i>
                </div>
                <h4 style="font-size: 16px; font-weight: 800; color: #065F46; margin: 0 0 4px;">Terima Kasih!</h4>
                <p style="font-size: 13px; color: #047857; margin: 0;">Anda sudah selesai melakukan pengisian kuisioner kepuasan pelayanan.</p>
            </div>
        <?php endif; ?>
<!--        untuk angkatan baru-->
        <?php endif; ?>
<!--        end untuk angkatan baru-->
        <?php else: ?>
            <div class="callout callout-warning text-center" style="padding: 30px 20px; border-radius: 12px; margin: 20px 0;">
                <div style="width: 52px; height: 52px; border-radius: 9999px; background: #FFFBEB; border: 2px solid #FDE68A; color: #D97706; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 12px;">
                    <i class="fa fa-clock-o"></i>
                </div>
                <h4 style="font-size: 17px; font-weight: 800; color: #92400E; margin: 0 0 6px;">Kuisioner Belum Aktif</h4>
                <p style="font-size: 13px; color: #B45309; margin: 0;">Pengisian kuisioner evaluasi belum aktif. Pengisian akan diaktifkan setelah UAS selesai.</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        $('#form-kuisioner-layanan').bind('submit', function (e) {
            var button = $('#loading');
            // Disable the submit button while evaluating if the form should be submitted
            button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');
            var valid = true;

            // Do stuff (validations, etc) here and set
            // "valid" to false if the validation fails

            if (!valid) {
                // Prevent form from submitting if validation failed
                e.preventDefault();

                // Reactivate the button if the form was not submitted
                button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang diproses..</button>');

            }
        });
    </script>
