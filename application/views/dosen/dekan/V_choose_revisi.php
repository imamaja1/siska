<?php if (count($kelas) > 0) : ?>

<div class="box box-solid box-warning">
    <div class="box-header with-border">Dekan</div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-table" id="example">
                <thead>
                <tr>
                    <th >Kode</th>
                    <th >Prodi</th>
                    <th >Nama Matakuliah</th>
                    <th ><center>SMT<center></th>
                    <th >Dosen Pengampu</th>
                    <th >Pengajuan Nilai</th>
                    <th >Prodi</th>
                    <th >Dekan</th>
                    <th >Tindakan</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (count($kelas) > 0) :
                    foreach ($kelas as $row) : ?>
                        <tr>
                            <td><?= $row->kelas_id; ?></td>
                            <td><?= $row->singkatan_program_studi; ?></td>
                            <td><b><?= $row->kode_matakuliah; ?> - <?= $row->nama_matakuliah; ?></b> - Kelas
                                : <?= $row->nama_kelas; ?></td>
                            <td align="center"><?= $row->semester ?></td>
                            <td>
                                <?php  foreach ($row->dosen_pengampu as $val): ?>
                                    <b><?= $val->nama_dosen ?></b><br>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php if ($row->data_kelas) : ?>
                                    <b>Nilai Ke-<?= $row->data_kelas->level ?></b> : 
                                    <?php if ($row->data_kelas->status_dosen == 'R') : ?>
                                        <span class="label label-warning">Revisi</span>
                                    <?php elseif($row->data_kelas->status_dosen == 'F') : ?>
                                        <span class="label label-warning">Proses Input</span>
                                    <?php elseif($row->data_kelas->status_dekan == 'T') : ?>
                                        <span class="label label-success">Disetujui</span>
                                    <?php else : ?>
                                        <span class="label label-primary">Proses Validasi</span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <span class="label label-danger">Nilai Kosong</span>
                                <?php endif ?>
                            </td>
                            <td><?= nilai_validasi($row->data_kelas->status_prodi ? $row->data_kelas->status_prodi :'F');?></td>
                            <td><?= nilai_validasi($row->data_kelas->status_dekan ? $row->data_kelas->status_dekan :'F');?></td>
                            <td>
                                <div class="btn-group">
                                    <!-- <a href="<?= base_url('dosen/dekan/validasinilai/revisi_nilai_all_mahasiswa/'.$row->kelas_id); ?>" title="Lihat Mahasiswa" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a> -->
                                    <button class="btn btn-primary btn-xs btn-flat" data-toggle="modal" data-target="#ModalNilai" onclick="show_mhs(<?= $row->kelas_id ?>,<?= $row->data_kelas->level ?>)"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-success btn-xs btn-flat" data-toggle="modal" data-target="#ModalNilai" onclick="show_nilai(<?= $row->kelas_id ?>,<?= $row->data_kelas->level ?>)"><i class="fa fa-check-circle"></i></button>
                                    <button id="pesan_dekan_<?= $row->kelas_id ?>" onclick="pesan_uas_dosen('<?= $row->kelas_id ?>')" class="btn btn-xs btn-flat btn-primary badge-notif" 
                                    <?php
                                    if ($pesan_dekan[$key] != 0) {
                                        echo 'data-badge="' . $pesan_dekan[$key] . '"';
                                    }
                                    ?> 
                                    ><i class="fa fa-envelope"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Kelas Belum dibagi di bagian akademik</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
    $('#example').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        // Add more options as needed
    });
});
</script>
<?php else: ?>
    <div class="col-md-12 col-xs-12">
        <div class="callout callout-warning">
            <h4><i class="fa fa-warning"></i> Peringatan</h4>
            <p>Tidak ada kelas untuk priode ini</p>
        </div>
    </div>
<?php endif; ?>

<!-- ================================================================ pesan ======================================= -->
<div class="modal fade" id="modal-pesan" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" id="landing-pesan">

        </div>
    </div>
</div>

<script>
    function pesan_uas_dosen(kelas_id) {
        super_kelas_id = kelas_id;
        var url = "<?= site_url('dosen/penilaian/pesan_uas') ?>/" + kelas_id +"/dekan/dosen";
        $.ajax({
            url: url,
            success: function (res) {
                $("#landing-pesan").html(res);
                $("#modal-pesan").modal('show');
                $("#pesan_dekan_" + kelas_id).addClass('btn-primary').removeClass('btn-danger').removeClass('badge-notif');
                console.log('ada');
            }
        })
    }
</script>