<div class="box box-solid flat">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th id="th" width="3%">NO.</th>
                    <th id="th">NIM</th>
                    <th id="th">CEK</th>
                    <th id="th">NAMA MAHASISWA</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($semua_mhs as $row) : ?>
                    <tr>
                        <td align="center"><?= $i++ ?>.</td>
                        <td align="center"><?= e($row->nim) ?></td>
                        <td align="center">
                            <input type="checkbox" name="kode_krs_detail"
                                   value="<?= e($row->kode_krs_detail) ?>">
                        </td>
                        <td><?= e($row->nama_mahasiswa) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <hr>
            <div class="form-group">
                <div class="col-sm-4 col-xs-12" style="padding: 0px">
                    <label> Pindah ke : </label>
                    <div class="input-group">
                        <select required name="kelas_id" class="form-control">
                            <option value="" selected disabled>Pilih</option>
                            <?php foreach ($nama_kelas as $row) : ?>
                                <option value="<?= e($row->kelas_id) ?>">Kelas
                                    - <?= e($row->nama_kelas) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="input-group-btn">
                      <button type="submit" name="submit" class="btn btn-danger btn-flat"><i
                                  class="fa fa-arrow-circle-right"></i></button>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
