<div style="text-align: center;">
    <?php
    if ($query1->kode_fakultas == '01') {
        $banner = "FT.png";
    } elseif ($query1->kode_fakultas == '02') {
        $banner = "FIB.png";
    } elseif ($query1->kode_fakultas == '03') {
        $banner = "FK.png";
    } elseif ($query1->kode_fakultas == '04') {
        $banner = "FEB.png";
    } elseif ($query1->kode_fakultas == '05') {
        $banner = "FSD.png";
    } elseif ($query1->kode_fakultas == '06') {
        $banner = "FH.png";
    }
    ?>
    <img style="height: 120px;" src="<?= base_url('assets/gambar/kop/' . $banner); ?>">
    <br>
    <br><br>
</div>