<form action="<?= site_url('admin/keuangan/status_perkuliahan/update/'.$id) ?>" method="post">
    <div class="form-group">
        <div class="input-group">
            <select name="status_perkuliahan" style="width:200px;" class="form-control">
                <option value="" selected disabled >Pilih</option>
                <option value="A" <?= $data->status_perkuliahan == 'A' ? "selected" : '' ?> >AKTIF</option>
                <option value="C" <?= $data->status_perkuliahan == 'C' ? "selected" : '' ?> >CUTI</option>
                <option value="T" <?= $data->status_perkuliahan == 'T' ? "selected" : '' ?> >TANPA KETERANGAN</option>
                <option value="B" <?= $data->status_perkuliahan == 'B' ? "selected" : '' ?> >BERHENTI</option>
                <option value="P" <?= $data->status_perkuliahan == 'P' ? "selected" : '' ?> >PINDAH/TRANSFER</option>
                <option value="L" <?= $data->status_perkuliahan == 'L' ? "selected" : '' ?> >LULUS</option>
            </select>
            <span class="input-group-btn">
                      <button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i></button>
                </span>
        </div>
    </div>
</form>