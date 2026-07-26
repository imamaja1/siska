<div class="box col-md-6 col-sm-12">
    <div class="box-header ">
        <h4>Tanggal penginputan nilai UTS</h4>
    </div>               
    <div class="box-body" style="padding: 0 0 25px 0  ">
        <div class="col-md-5">
            <label for="">Mulai tanggal</label>
            <input type="datetime-local" class="form-control" id="tgl_awal_uts" value="<?= $aktivasi->tgl_awal_uts ?>">
        </div>
        <div class="col-md-5">
            <label for="">Selesai tanggal</label>
            <input type="datetime-local" class="form-control" id="tgl_akhir_uts" value="<?= $aktivasi->tgl_akhir_uts ?>">
        </div>
        <div class="col-md-2 " style="margin-top: 23px; ">
            <button class="btn btn-primary " style="width: 100%;" onclick="update_uts()"> Ubah </button> 
        </div>
    </div>
</div>
<div class="box col-md-6 col-sm-12 mb-3">
    <div class="box-header">
        <h4>Tanggal penginputan nilai UAS</h4>
    </div>               
    <div class="box-body" style="padding: 0 0 25px 0">     
        <div class="col-md-5">
            <label for="">Mulai tanggal</label>
            <input type="datetime-local" class="form-control" id="tgl_awal_uas" value="<?= $aktivasi->tgl_awal_uas ?>">
        </div>
        <div class="col-md-5">
            <label for="">Selesai tanggal</label>
            <input type="datetime-local" class="form-control" id="tgl_akhir_uas" value="<?= $aktivasi->tgl_akhir_uas ?>">
        </div>
        <div class="col-md-2 " style="margin-top: 23px; ">
            <button class="btn btn-primary " style="width: 100%;" onclick="update_uas()"> Ubah </button> 
        </div>
    </div>
</div>
