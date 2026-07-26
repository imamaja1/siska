<div class="box box-primary">
    <div class="box-header">
        <h4><i class="fa fa-pie-chart"></i> Kuisioner</h4>
    </div>
    <div class="box-body">
        <?php if ($this->session->flashdata('info')) : ?>
            <?= $this->session->flashdata('info') ?>
        <?php endif; ?>
        <?php if ($status_kuisioner == 'A') :
            if (count($data) > 0) : ?>
                <h4><i class="fa fa-television"></i> Kuisioner Proses Belajar Mengajar (PBM)</h4>
                <div class="table-responsive">
                    <table class="table demo-table">
                        <thead>
                        <tr>
                            <th id="th">NO.</th>
                            <th id="th">KODE MATAKULIAH</th>
                            <th id="th">MATAKULIAH</th>
                            <th id="th">aksi</th>
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
                <p align="center" style="font-size: 20pt; color: #2ca02c"><strong>Terimakasih.</strong></p>
                <p align="center" style="font-size: large;"><strong>Anda sudah selesai melakukan pengisian
                        kuisioner Proses Belajar Mengajar (PBM).</strong></p>
                <p align="center"><img class="img-responsive img-circle" style="width: 50px"
                                       src="<?= base_url('assets/gambar/done.png') ?>"
                                       alt=""></p>
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
                <div class="table-responsive">
                    <table class="table demo-table">
                        <thead>
                        <tr>
                            <th id="th" width="20%">BAGIAN</th>
                            <th id="th">PERTANYAAN</th>
                            <th id="th" width="5%">1</th>
                            <th id="th" width="5%">2</th>
                            <th id="th" width="5%">3</th>
                            <th id="th" width="5%">4</th>
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
            <p align="center" style="font-size: 20pt; color: #2ca02c"><strong>Terimakasih.</strong></p>
            <p align="center" style="font-size: large;"><strong>Anda sudah selesai melakukan pengisian
                    kuisioner kepuasan pelayanan.</strong></p>
            <p align="center"><img class="img-responsive img-circle" style="width: 50px"
                                   src="<?= base_url('assets/gambar/done.png') ?>"
                                   alt=""></p>
        <?php endif; ?>
<!--        untuk angkatan baru-->
        <?php endif; ?>
<!--        end untuk angkatan baru-->
        <?php else: ?>
            <p align="center" style="font-size: xx-large;color: #A70000"><strong>Nonaktif.</strong></p>
            <p align="center" style="font-size: large;">Pengisian kuisioner belum <strong>Aktif</strong>,
                pengisian
                kuisioner akan <strong>diaktifkan</strong> setelah <strong>UAS</strong> selesai</p>
            <p align="center"><img class="img-responsive img-circle"
                                   src="<?= base_url('assets/gambar/nonaktif.png') ?>"
                                   alt=""></p>
        <?php endif; ?>
    </div>

    <script>
        $('#form-kuisioner-layanan').bind('submit', function (e) {
            var button = $('#loading');
            // Disable the submit button while evaluating if the form should be submitted
            button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang di proses..</button>');
            var valid = true;

            // Do stuff (validations, etc) here and set
            // "valid" to false if the validation fails

            if (!valid) {
                // Prevent form from submitting if validation failed
                e.preventDefault();

                // Reactivate the button if the form was not submitted
                button.html('<button class="btn btn-default btn-sm flat" disabled><i class="fa fa-refresh fa-spin"></i> Permintaan sedang di proses..</button>');

            }
        });
    </script>
