
<div class="box col-md-6 col-sm-12" style="height: 160px; ">
    <div class="box-header ">
        <h4>Aktivasi UTS<h4>
    </div>               
    <div class="box-body ">
        <div class="col-md-5">
            <label for="">Tanggal Awal Aktivasi</label>
            <input type="datetime-local" class="form-control" id="tgl_awal_uts" value="<?= e($aktivasi->tgl_awal_uts) ?>">
        </div>
        <div class="col-md-5">
            <label for="">Tanggal akhir Aktivasi</label>
            <input type="datetime-local" class="form-control" id="tgl_akhir_uts" value="<?= e($aktivasi->tgl_akhir_uts) ?>">
        </div>
        <div class="col-md-2 " style="margin-top: 23px; ">
            <button class="btn btn-primary " style="width: 100%;" onclick="update_uts()"> update </button> 
        </div>
    </div>
</div>
<div class="box col-md-6 col-sm-12" style="height: 160px;">
    <div class="box-header">
        <h4>Aktivasi UAS<h4>
    </div>               
    <div class="box-body">     
        <div class="col-md-5">
            <label for="">Tanggal Awal Aktivasi</label>
            <input type="datetime-local" class="form-control" id="tgl_awal_uas" value="<?= e($aktivasi->tgl_awal_uas) ?>">
        </div>
        <div class="col-md-5">
            <label for="">Tanggal akhir Aktivasi</label>
            <input type="datetime-local" class="form-control" id="tgl_akhir_uas" value="<?= e($aktivasi->tgl_akhir_uas) ?>">
        </div>
        <div class="col-md-2 " style="margin-top: 23px; ">
            <button class="btn btn-primary " style="width: 100%;" onclick="update_uas()"> update </button> 
        </div>
    </div>
</div>
