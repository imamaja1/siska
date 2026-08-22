<div class="box box-solid">
    <div class="box-header with-border">
        <form method="post" action="<?= site_url('admin/akademik/validasikhusus/cari'); ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="row">
                <div class="col-md-3">
                    <select name="kd_fk" class="form-control">
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
                        <select name="kode_tahun_akademik" class="form-control select2">
                            <option value="" selected disabled>Tahun Akademik</option>
                            <?php foreach ($tahun_akademik as $row) : ?>
                                <option value="<?= e($row->kode_tahun_akademik) ?>"><?= e($row->tahun_akademik) ?>
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
                        <th>Dosen</th>
                        <th>Nilai</th>
                        <th>Prodi</th>
                        <th>Dekan</th>
                        <th>Mahasiswa</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($kelas as $row) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= e($row->kelas_id); ?></td>
                            <td><?= e($row->singkatan_program_studi); ?></td>
                            <td><?= e($row->kode_matakuliah); ?> - <?= e($row->nama_matakuliah); ?> - Kelas
                                : <?= e($row->nama_kelas); ?></td>
                            <td><?= e($row->nama_dosen); ?></td>
                            <td><?= nilai_validasi($row->status_nilai) ?></td>
                            <td><?= nilai_validasi($row->validasi_nilai); ?></td>
                            <td><?= nilai_validasi($row->validasi_dekan); ?></td>
                          	<td> 
                               <button class="btn btn-primary btn-xs btn-flat" disabled>
                                  <?= e($row->jum_mhs); ?>
                              </button>
                                <a class="btn btn-primary btn-xs btn-flat"
                                   href="#" onclick='mhs("<?= e($row->kelas_id) ?>")' data-toggle="modal" data-target="#mhs">
                                     View
                                </a>
                            </td>
                            <td>
                                <?php
                                if (isset($row->param_uas) && ($row->param_uas == "1")) {
                                    ?>
                                        <a class="btn btn-info btn-xs btn-flat"
                                        href="#" onclick='validasi("<?= e($row->kelas_id) ?>")'>
                                            <i class="fa fa-check-circle"></i>
                                            Validasi
                                        </a>
                                    <?php
                                }
                                ?>
                                <?php
                                if (($row->validasi_nilai == "T")) {
                                    ?>
                                    <a class="btn btn-primary btn-xs btn-flat"
                                       href="<?= site_url('admin/akademik/cetak_nilai/index/' . $row->kelas_id) ?>">
                                        <i class="fa fa-print"></i>
                                        Cetak
                                    </a>
                                    <?php
                                }
                                ?>
                                <div class="custom-control custom-checkbox pull-right">
                                    <input class="custom-control-input" type="checkbox" id="Checkbox" onchange='cek("<?= e($row->kelas_id) ?>")' <?php if($row->cek_uas == 2){ echo 'checked'; } ?> >				
                              	</div>
                            </td>
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

<script src="<?= base_url('assets/sweetalert/dist/sweetalert2.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        $('.data-table').DataTable({
            columnDefs: [{targets: 'no-sort', orderable: false}],
            order: [[4, 'desc'], [5, 'desc'], [6, 'asc']]
        });
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
            url: "<?= site_url('admin/akademik/validasikhusus_uas/mhs/')?>/"+id, 
            beforeSend : function () {
                var html = "<p style='text-align: center'><img src='<?= base_url("assets/siska/img/logo-ubg.gif") ?>' alt=''></p>";
                $("#data_mhs").html(html);
            },
            success: function(result){
                $('#data_mhs').html(result); 
            }
        });
    }
</script>

</script>