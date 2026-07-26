<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="pull-right">
                    <a href="#" class="btn btn-sm btn-success btn-flat" onclick="window.location.href = '<?= site_url('admin/keuangan/status_perkuliahan')  ?>'"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12 col-xs-12">
        <div class="box box-primary flat">
            <div class="box-body">
                <?php if (count($data) > 0): ?>
                    <form class="form-horizontal" method="POST" action="<?= site_url('admin/keuangan/status_perkuliahan/tambah_status_semua')  ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <table class="table demo-table" id="example1">
                            <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">Cek</th>
                                <th style="text-align: center;">NIM</th>
                                <th style="text-align: center;">Nama Mahasiswa</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;
                            foreach ($data as $row) { ?>
                                <tr>
                                    <td style="text-align: center;"><input type="checkbox" id="checkbox" name="cekbox[]" class="cek" value="<?= e($row->nim)?>"></td>
                                    <td style="text-align: center;"><?= e($row->nim)  ?></td>
                                    <td><?= e(strtoupper($row->nama_mahasiswa))  ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <hr>
                        <a href="#" onclick="pilih()" class="text-success"><i class="fa fa-check-circle"></i> Pilih semua</a> |
                        <a href="#" onclick="kosong()" class="text-danger"><i class="fa fa-times-circle"></i> Uncek semua</a>
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Status Perkuliahan : </label>
                            <div class="col-sm-2">
                                <select class="form-control" name="status">
                                    <option value="A">Aktif</option>
                                    <option value="C">Cuti</option>
                                    <option value="T">Tanpa Keterangan</option>
                                    <option value="B">Berhenti</option>
                                    <option value="P">Pindah/Transfer</option>
                                    <option value="L">Lulus</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary flat" type="submit" name="submit"><i class="fa fa-check-circle"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <p class="alert alert-warning">Data tidak ditemukan</p>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

 <script type="text/javascript">
 	function pilih() {
 		// body...
 		$('.cek').prop('checked',true);
 	}
 	function kosong() {
 		// body...
 		$('.cek').prop('checked',false);
 	}
 </script>