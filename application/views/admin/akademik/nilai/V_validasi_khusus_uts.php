<div class="box box-solid">
    <div class="box-header with-border">
        <form method="post" action="<?= site_url('admin/akademik/validasikhusus_uts/cari'); ?>">
            <div class="row">
                <div class="col-md-3">
                    <select name="kd_fk" class="form-control">
                        <option disabled>--Pilih Fakultas--</option>
                        <?php foreach ($kode_fakultas as $kd_fk): ?>
                            <?php if ($match_kode_fakultas == $kd_fk->kode_fakultas): ?>
                                <option selected value="<?= $kd_fk->kode_fakultas ?>"><?= $kd_fk->nama_fakultas ?></option>
                            <?php else: ?>
                                <option value="<?= $kd_fk->kode_fakultas ?>"><?= $kd_fk->nama_fakultas ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="kode_tahun_akademik" class="form-control select2">
                            <option value="" selected disabled>Tahun Akademik</option>
                            <?php foreach ($tahun_akademik as $row) : ?>
                                <option value="<?= $row->kode_tahun_akademik ?>"><?= $row->tahun_akademik ?>
                                    - <?= $row->semester == 0 ? "GENAP" : "GANJIL" ?></option>
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
                            <td><?= $row->kelas_id; ?></td>
                            <td><?= $row->singkatan_program_studi; ?></td>
                            <td><?= $row->kode_matakuliah; ?> - <?= $row->nama_matakuliah; ?> - Kelas
                                : <?= $row->nama_kelas; ?></td>
                            <td><?= $row->nama_dosen; ?></td>
                            <td>
                                <?php if ($row->param_uts == 1):?>
                                    <?= nilai_validasi_telat($row->status_nilai_uts) ?>
                                <?php else:?>
                                    <?= nilai_validasi($row->status_nilai_uts) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row->param_uts == 1):?>
                                    <?= nilai_validasi_telat($row->validasi_nilai_uts) ?>
                                <?php else:?>
                                    <?= nilai_validasi($row->validasi_nilai_uts) ?>
                                <?php endif; ?>
                            </td>
                            <td><?php if ($row->param_uts == 1):?>
                                    <?= nilai_validasi_telat($row->validasi_dekan_uts) ?>
                                <?php else:?>
                                    <?= nilai_validasi($row->validasi_dekan_uts) ?>
                                <?php endif; ?>
                            </td>
                          	<td> 
                               <button class="btn btn-primary btn-xs btn-flat" disabled>
                                  <?= $row->jum_mhs; ?>
                              </button>
                                <a class="btn btn-primary btn-xs btn-flat"
                                   href="#" onclick='mhs("<?= $row->kelas_id ?>")' data-toggle="modal" data-target="#mhs">
                                     View
                                </a>
                            </td>
                            <td>
                                <?php if($row->valid_uts == "2" || $row->status_nilai_uts == "T" && $row->validasi_nilai_uts == "T" && $row->validasi_dekan_uts == "T") {
                                    ?>
                                        <button class="btn btn-danger btn-xs btn-flat" href="#" disabled>
                                            <i class="fa fa-check-circle"></i>
                                            Selesai
                                        </button>
                              			<a class="btn btn-primary btn-xs btn-flat"
                                           href="<?= site_url('admin/akademik/cetak_nilai_uts/index/' . $row->kelas_id) ?>">
                                            <i class="fa fa-print"></i>
                                            Cetak
                                        </a>
                                    <?php 
                                }else{
                                    ?>
                                        <button class="btn btn-warning btn-xs btn-flat" href="#" disabled>
                                            <i class="fa fa-check-circle"></i>
                                            Tunggu
                                        </button>
                                    <?php 
                                }
                                ?>
                                <!-- <?php
                                if (($row->validasi_nilai_uts == "T") && ($row->validasi_dekan_uts == "T")) {
                                    ?>
                                    <a class="btn btn-primary btn-xs btn-flat"
                                       href="<?= site_url('admin/akademik/cetak_nilai_uts/index/' . $row->kelas_id) ?>">
                                        <i class="fa fa-print"></i>
                                        Cetak
                                    </a>
                                    <?php
                                }
                                ?> -->
                                <div class="custom-control custom-checkbox pull-right">
                                    <input class="custom-control-input" type="checkbox" id="Checkbox" onchange='cek("<?= $row->kelas_id ?>")' <?php if($row->cek_uts == 2){ echo 'checked'; } ?> >				
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
    $('.data-table').DataTable({
        columnDefs: [{targets: 'no-sort', orderable: false}],
        order: [[4, 'desc'], [5, 'desc'], [6, 'asc']]
    });
    function cek(id){
         $.ajax({
            url: "<?= site_url('admin/akademik/validasikhusus_uts/cek_uts/')?>/"+id, 
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
                url: "<?= site_url('admin/akademik/nilai/validasi_nilai/')?>/"+id, 
                success: function(result){
                    window.location.reload(true);
                }
            });
        });
    }
  	function mhs(id){
         $.ajax({
            url: "<?= site_url('admin/akademik/validasikhusus_uts/mhs/')?>/"+id, 
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