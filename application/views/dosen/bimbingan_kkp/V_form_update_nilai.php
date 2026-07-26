<form role="form" method="post" action="<?= site_url('dosen/bimbingan_kkp/update_nilai/'.$id) ?>">
    <div class="box-body">
        <div class="form-group">
            <label for=""><span class="small text-blue">BAB I (15%)</span></label>
            <input type="number" value="<?= $data->bab_1 ?>" name="bab_1" step="any" min="0" max="100" required class="form-control input-sm" placeholder="Nilai">
        </div>
        <div class="form-group">
            <label for=""><span class="small text-blue">BAB II (15%)</span></label>
            <input type="number" value="<?= $data->bab_2 ?>" name="bab_2" step="any" required min="0" max="100" class="form-control input-sm" placeholder="Nilai">
        </div>
        <div class="form-group">
            <label for=""><span class="small text-blue">BAB III (25%)</span></label>
            <input type="number" value="<?= $data->bab_3 ?>" name="bab_3" step="any" required min="0" max="100" class="form-control input-sm" placeholder="Nilai">
        </div>
        <div class="form-group">
            <label for=""><span class="small text-blue">BAB IV (30%)</span></label>
            <input type="number" value="<?= $data->bab_4 ?>" name="bab_4" step="any" required min="0" max="100" class="form-control input-sm" placeholder="Nilai">
        </div>
        <div class="form-group">
            <label for=""><span class="small text-blue">BAB V (15%)</span></label>
            <input type="number" value="<?= $data->bab_5 ?>" name="bab_5" step="any" required min="0" max="100" class="form-control input-sm" placeholder="Nilai">
        </div>
        <hr>
        <div class="form-group">
            <label for=""><span class="small text-blue">Kinerja Kerja Praktek</span></label>
            <input type="number" value="<?= $data->kinerja ?>" name="kinerja" step="any" required min="0" max="100" class="form-control input-sm" placeholder="Nilai">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-sm "><i class="fa fa-check-square-o"></i> Update</button>
        </div>
    </div>
</form>