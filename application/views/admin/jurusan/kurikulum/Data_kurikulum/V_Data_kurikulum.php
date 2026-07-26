<?= $this->session->flashdata('info') ?>
<div class="box box-solid flat">
    <div class="box-body">
        <strong><?= "Kurikulum " . $nama_kurikulum->nama_kurikulum ?> <i
                    class="fa fa-angle-double-right"></i> <?= $nama_kurikulum->singkatan_program_studi ?> </strong>
        <div class="pull-right">
            <a href="<?= site_url('admin/jurusan/kurikulum/data_kurikulum/excel/'.$kode_nama_kurikulum) ?>"
               class="btn btn-success btn-xs flat" title="Export Excel"><i class="fa fa-file-excel-o"></i> Excel</a>
            <a href="<?= site_url('admin/jurusan/kurikulum/data_kurikulum') ?>"
               class="btn btn-danger btn-xs flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>

    </div>
</div>
<!-- end.box -->
<?php if (count($data) > 0): ?>
    <?php foreach ($data as $row): ?>
        <div class="box box-primary flat">
            <div class="box-body">
                <a class="btn btn-primary btn-xs flat pull-right" id="tambah" data-toggle="modal"
                   onclick="isi_data_kurikulum('<?= $row['semester'] ?>')"><i class="fa fa-plus-circle"></i> Tambah Data
                    Kurikululm</a>
                <p><strong>SEMESTER <?= $row['semester'] ?></strong></p>
                <br>
                <?php if (count($row['data']) > 0) : ?>
                    <table class="table demo-table">
                        <thead>
                        <tr>
                            <th style="text-align: center" id="color" width="20">NO.</th>
                          	<th style="text-align: center" id="color" width="70">ID MK.</th>
                            <th style="text-align: center" id="color" width="200">KODE MATAKULIAH</th>
                            <th style="text-align: center" id="color">NAMA MATAKULIAH</th>
                            <th style="text-align: center" id="color">SKS TEORI</th>
                            <th style="text-align: center" id="color">SKS PRAKTEK</th>
                            <th style="text-align: center" id="color">SKS PRAKTIKUM</th>
                            <th style="text-align: center" id="color" width="150">AKSI</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
							$j_teori = 0;
                            $j_pr = 0;
                            $j_pm = 0;
                        foreach ($row['data'] as $d) {
                           $j_teori += $d->sks_teori;
                                $j_pr += $d->sks_praktek;
                                $j_pm += $d->sks_praktikum;
                            ?>
                            <tr <?= in_array($d->id_matakuliah, $mk_pilihan) ? "style='font-style: italic'" : "" ?> <?=($d->jenis == '1')? 'style="font-style: italic"':"style='font-weight: bold'";?>>
                                <td><?= $i++ ?>.</td>
                              	<td><?= $d->id_matakuliah ?></td>	
                                <td id="kode-matakuliah-<?= $d->kode_kurikulum ?>"><?= $d->kode_matakuliah ?></td>
                                <td>
                                  <?= $d->nama_matakuliah ?>
                                  <?= ($nama_pilihan[$d->id_matakuliah] == true) ? ' - (Kompetensi : ' . $nama_pilihan[$d->id_matakuliah] . ')' : '' ?>
                                  <?= ($d->jenis == 1) ? '- (Matakuliah Pilihan)' : '' ?>
                              	</td>
                                <td style="text-align: center"><?= $d->sks_teori ?></td>
                                <td style="text-align: center"><?= $d->sks_praktek ?></td>
                                <td style="text-align: center"><?= $d->sks_praktikum ?></td>
                                <td style="text-align: center">&nbsp;
                                    <a href="#"
                                       onclick="hapus('<?= site_url('admin/jurusan/kurikulum/data_kurikulum/hapus/' . $d->kode_kurikulum . "/" . $d->kode_nama_kurikulum) ?>')"
                                       class="btn btn-xs btn-danger flat"> <i class="fa fa-trash"></i> Hapus</a>
                                </td>
                                <div style="display: none;"
                                     id="kode-nama-<?= $d->kode_kurikulum ?>"><?= $d->kode_nama_kurikulum ?></div>
                                <div style="display: none;"
                                     id="kode-kurikulum-<?= $d->kode_kurikulum ?>"><?= $d->kode_kurikulum ?></div>
                            </tr>
                        <?php } ?>
                          <tr style="text-align: center; font-style: italic; font-weight: bold;">
                                <td colspan="4">Jumlah</td>
                                <td><?= $j_teori; ?></td>
                                <td><?= $j_pr; ?></td>
                                <td><?= $j_pm; ?></td>
                                <td><?= $j_teori + $j_pr + $j_pm; ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-info alert-dismissible flat">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> Info!</h4>
                        Data tidak ada...
                    </div>
                <?php endif; ?>
                <hr>
            </div>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="alert alert-info alert-dismissible flat">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Info!</h4>
        Data tidak ada...
    </div>
<?php endif; ?>
<!-- end.box -->
<!-- modal tambah prasyarat-->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <form method="POST"
                  action="<?= site_url('admin/jurusan/kurikulum/data_kurikulum/simpan_data_kurikulum') ?>">
                <div class="modal-body" style="height:400px; overflow-y:scroll;">
                    <div class="form-group">
                        <input type="hidden" name="semester" id="semester">
                        <input type="hidden" name="kode_nama_kurikulum" value="<?= $kode_nama_kurikulum ?>">
                        <?php foreach ($data_matakuliah as $row) : ?>
                            <div class="col-sm-12"
                                 style="border-top: 1px solid #61b5ed; border-collapse: collapse;">
                                <label>
                                    <input type="checkbox" name="id_matakuliah[]" value="<?= $row->id_matakuliah ?>"
                                           class="flat-red">
                                    <?= $row->kode_matakuliah . ' - ' . $row->nama_matakuliah ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal">
                        <iclass
                        ="fa fa-times-circle"></i> Close
                    </button>
                    <button type="submit" name="submit" class="btn btn-success flat"><i class="fa fa-check-circle"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- script -->
<script type="text/javascript">
    $(function () {
        $('.dataTables-example').dataTable();
    });
</script>
<script type="text/javascript">
    function editKurikulum(id) {

        var kode_kurikulum = $("#kode-kurikulum-" + id).html();
        var kode_nama = $("#kode-nama-" + id).html();
        var kode_matakuliah = $("#kode-matakuliah-" + id).html();

        $("#edit-kode-kurikulum").val(id);
        $("#edit-kode-nama").val(kode_nama);
        $("#edit-kode-matakuliah").val(kode_matakuliah);


        $("#edit-kurikulum").modal("show");
    }

    function isi_data_kurikulum(semester) {
        $("#semester").val(semester);
        $("#myModal").modal('toggle');
        $("#myModalLabel").html("<b> Semester " + semester + "</b>");
    }

    function hapus(url) {
        swal({
            title: '',
            text: "Anda yaikin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            window.location.href = url;
        });
    }
</script>
