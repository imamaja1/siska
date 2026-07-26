<div class="box box-solid">
    <div class="box-header with-border">
        <form method="post" action="<?= site_url('admin/akademik/validasi_khusus/cari'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
                <div class="col-md-3">
                    <select name="kd_fk" class="form-control select2">
                        <option disabled>--Pilih Fakultas--</option>
                        <?php foreach ($kode_fakultas as $kd_fk): ?>
                            <?php if ($match_kode_fakultas == $kd_fk->dekan): ?>
                                <option selected value="<?= e($kd_fk->dekan) ?>"><?= e($kd_fk->nama_fakultas) ?></option>
                            <?php else: ?>
                                <option value="<?= e($kd_fk->dekan) ?>"><?= e($kd_fk->nama_fakultas) ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="kode_tahun_akademik" class="form-control select2" id='ta'>
                            <option value="" selected disabled>Tahun Akademik</option>
                            <?php foreach ($tahun_akademik as $row) : ?>
                                <option value="<?= e($row->kode_tahun_akademik) ?>" <?= e($row->kode_tahun_akademik == $ta ? 'selected' :'') ?>><?= e($row->tahun_akademik) ?>
                                    - <?= e($row->semester == 0 ? "GENAP" : "GANJIL") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>


        </form>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table data-nilai2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Prodi</th>
                        <th>Nama MK</th>
                        <th>Dosen Pengampu</th>
                        <th>Pengajuan Nilai</th>
                        <th>Dosen</th>
                        <th>Prodi</th>
                        <th>Dekan</th>
                        <th>Mahasiswa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($kelas as $row) {
                      	if(!$row->nama_dosen){
                          continue;
                        }
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= e($row->kelas_id); ?></td>
                            <td><?= e($row->singkatan_program_studi); ?></td>
                            <td><?= e($row->kode_matakuliah); ?> - <?= e($row->nama_matakuliah); ?> - Kelas
                                : <?= e($row->nama_kelas); ?></td>
                            <td><?= e($row->nama_dosen); ?></td>
                            <td>
                                <?php foreach ($row->nilai_validasi as $key => $value): ?>
                                    <?php if ($value) : ?>
                                        <b>Nilai Ke-<?= e($value->level) ?></b> :
                                    <?php else : ?>
                                        <span class="label label-success"> bangke </span>
                                    <?php endif; ?>
                                    <br>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php foreach ($row->nilai_validasi as $key => $value): ?>
                                    <?php if ($value) : ?>
                                        <?php if ($value->status_dosen == 'R') : ?>
                                            <span class="label label-warning mt-1"> <i class="fa fa-undo" style="margin-right:3px" aria-hidden="true"></i>Revisi</span>
                                        <?php elseif($value->status_dosen == 'F') : ?>
                                            <span class="label label-default  mt-1"> <i class="fa fa-times" style="margin-right:3px" aria-hidden="true"></i>Pengisian</span>
                                        <?php elseif($value->status_dosen == 'T') : ?>
                                            <span class="label label-success mt-1"><i class="fa fa-check" style="margin-right:3px" aria-hidden="true"></i>Selesai</span>
                                        <?php endif ?>
                                
                                    <?php endif; ?>
                                    <br>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php foreach ($row->nilai_validasi as $key => $value): ?>
                                    <?php if ($value) : ?>
                                        <?php if ($value->status_prodi == 'R') : ?>
                                            <span class="label label-warning mt-1"> <i class="fa fa-undo" aria-hidden="true"></i> Revisi</span>
                                        <?php elseif($value->status_prodi == 'F') : ?>
                                            <span class="label label-default  mt-1"> <i class="fa fa-times" aria-hidden="true"> </i> Proses</span>
                                        <?php elseif($value->status_prodi == 'T') : ?>
                                            <span class="label label-success mt-1"><i class="fa fa-check" aria-hidden="true"></i> Selesai</span>
                                        <?php endif ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php foreach ($row->nilai_validasi as $key => $value): ?>
                                    <?php if ($value) : ?>
                                        <?php if ($value->status_dekan == 'R') : ?>
                                            <span class="label label-warning mt-"> <i class="fa fa-undo" aria-hidden="true"></i> Revisi</span>
                                        <?php elseif($value->status_dekan == 'F') : ?>
                                            <span class="label label-default  mt-1"> <i class="fa fa-times" aria-hidden="true"> </i> Proses</span>
                                        <?php elseif($value->status_dekan == 'T') : ?>
                                            <span class="label label-success mt-1"><i class="fa fa-check" aria-hidden="true"></i> Selesai</span>
                                        <?php endif ?>
                                    <?php endif; ?>
                                    <br>
                                <?php endforeach; ?>
                            </td>
                          	<td> 
                                <div class=" btn-goup" style="display: flex;" >
                                    <a class="btn btn-primary btn-xs btn-flat mr-1" href="#" onclick='mhs("<?= e($row->kelas_id) ?>")' data-toggle="modal" data-target="#mhs"> 
                                        <i class="fa fa-users" aria-hidden="true"></i> View
                                    </a>
                                    <a class=" btn btn-success btn-xs btn-flat" href="#" onclick='show_nilai_validasi("<?= e($row->kelas_id) ?>")' data-toggle="modal" data-target="#ModalNilaiValidasi">
                                        <i class="fa fa-file-text-o" aria-hidden="true"></i> Nilai
                                    </a>
                                </div>
                            </td>
                            <!-- <td>
                                <?php
                                if (isset($row->param_uas) && ($row->param_uas == "1")) {
                                    ?>
                                        <a class="btn btn-info btn-xs btn-flat"
                                        href="#" onclick='validasi("<?= $row->kelas_id ?>")'>
                                            <i class="fa fa-check-circle"></i>
                                            Validasi
                                        </a>
                                    <?php
                                }
                                ?>
                                <?php
                                if (isset($row->validasi_nilai, $row->validasi_dekan) && ($row->validasi_nilai == "T") && ($row->validasi_dekan == "T")) {
                                    ?>
                                    <a class="btn btn-primary btn-xs btn-flat"
                                       href="<?= site_url('admin/akademik/cetak_nilai/index/' . $row->kelas_id) ?>">
                                        <i class="fa fa-print"></i>
                                        Cetak
                                    </a>
                                    <?php
                                }
                                ?>
                            </td> -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="mhs" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">Data Mahsiwa</h5>
      </div>
      <div class="modal-body">
            <div id='data_mhs'></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal-lg" id="ModalNilaiValidasi" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Nilai Mahasiswa Tervalidasi Oleh Kaprodi dan Dekan</h3>
      </div>
      <div class="modal-body">
        <div id='nilai_mahasiswa_tervalidasi'></div>
        <!-- <p>Modal body text goes here.</p> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/sweetalert/dist/sweetalert2.min.js') ?>"></script>
<script>
    $('.data-table').DataTable({
        columnDefs: [{targets: 'no-sort', orderable: false}],
        order: [[4, 'desc'], [5, 'desc'], [6, 'asc']]
    });
    $('.data-table').DataTable({
        columnDefs: [{targets: 'no-sort', orderable: false}],
        order: [[4, 'desc'], [5, 'desc'], [6, 'asc']]
    });
    function cek(id){
         $.ajax({
            url: "<?= site_url('admin/akademik/validasikhusus_uas/cek_uas/')?>/"+id, 
            success: function(result){
                console.log(result);
            }
        });
    }
    function validasi(id){
        swal({
            title: '',
            html: "<strong>Anda Yakin Ingin Mevalidasi Nilai ?</strong>",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then(function () {
             $.ajax({
                url: "<?= site_url('admin/akademik/nilai/validasi_nilai_uas/')?>/"+id, 
                success: function(result){
                    window.location.reload(true);
                }
            });
        });
    }
  	function mhs(id){
         $.ajax({
            url: "<?= site_url('admin/akademik/validasi_khusus/mhs/')?>/"+id, 
            beforeSend : function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#data_mhs").html(html);
            },
            success: function(result){
                $('#data_mhs').html(result); 
            }
        });
    }
    function show_nilai_validasi(param1) {
    $.ajax({
        url: '<?= site_url('admin/akademik/validasi_khusus/revisi_nilai_divalidasi')?>',
        type: 'post',
        data: {
            "kelas": param1,
            "kode_ta" : $("#ta").val(),
        },
        beforeSend : function () {
            var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
            $("#nilai_mahasiswa_tervalidasi").html(html);
        },
        success: function (res) {
            $("#nilai_mahasiswa_tervalidasi").html(res);
        },
        error: function () {
            console.log('gagal');
        }
    })
}
</script>

</script>