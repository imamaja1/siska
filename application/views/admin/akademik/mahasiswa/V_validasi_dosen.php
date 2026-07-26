<div class="box box-solid flat">
    <div class="box-body">
        <a href="<?= site_url('admin/akademik/mahasiswa'); ?>" class="btn btn-success btn-xs flat"><i class="fa fa-arrow-left"></i> Kembali</a>
        <?= isset($jumlah_data) ? $jumlah_data : ''; ?>
        <div class="pull-right">
            <?= isset($pagination) ? $pagination : ''; ?>
        </div>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">
        <h5><b>Pencarian Data Mahasiswa Atif</b></h5><hr>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?= site_url('admin/akademik/mahasiswa/Mahasiswa_Validasi_KRS'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-3">Tahun Akademik :</label>
                <div class="col-sm-4">
                    <select name="ta" id="" class="form-control">
                        <?php foreach ($data_ta as $key => $value) : ?>
                            <option value="<?= $value->kode_tahun_akademik ?>" <?= ($value->kode_tahun_akademik == $ta ? 'selected' :'')  ?>><?= $value->tahun_akademik.' Semester '.($value->semester == 0 ? 'Genap' : 'Ganjil') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
             <div class="form-group">
                <label class="control-label col-sm-3">Program Studi :</label>
                <div class="col-sm-4">
                     <select name="prodi" id="" class="form-control">
                        <?php foreach ($data_prodi as $key => $value) : ?>
                            <option value="<?= $value->kode_program_studi ?>" <?= ($value->kode_program_studi == $prodi ? 'selected' :'')  ?>><?= $value->nama_program_studi ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary flat"><i class="fa fa-cog"></i>  Proses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="box box-primary flat">
    <div class="box-header">
        <h4> 
            Data Mahasiswa Validasi KRS 
            <a href="<?= site_url('admin/akademik/mahasiswa/Excel_Validasi_KRS/'.$ta.'/'.$prodi); ?>" class="btn btn-success  flat pull-right"><i class="fa fa-file"></i> Excel</a> 
        </h4>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table demo-table " id="example1">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nim</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Nama Dosen Wali</th>
                        <th scope="col">Validasi Dosen</th>
                      	<th scope="col">Validasi SKS (Keuangan)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_mhs as $key => $value) : ?>
                    <tr>
                        <th scope="row"><?= $key+1 ?></th>
                        <td><?= $value->nim ?></td>
                        <td><?= $value->nama_mahasiswa ?></td>
                        <td><?= $value->nama_dosen ?></td>
                        <td><?= $value->status_cetak == "A" ? '<span class="badge btn-success"> Divalidasi</span>':'<span class="badge btn-danger">Belum Divaldiasi</span>' ?></td>
                      	<td><?= $value->pembayaran_sks != '0' ? '<span class="badge btn-success"> Divalidasi</span>':'<span class="badge btn-danger">Belum Divaldiasi</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
