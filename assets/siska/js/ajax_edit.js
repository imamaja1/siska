
function editSumberdana(id) {

    var sumberdana = $("#sumberdana-" + id).html();

    $("#nama_sumberdana").val(sumberdana);
    $("#no-sumberdana").val(id);

    $("#ubahData").modal("show");
}

function editTernak(id) {

    // ambil data kegiatan
    var jenisternak = $("#jenisternak-" + id).html();
    var satuanternak = $("#satuanternak-" + id).html();
    var keteranganternak = $("#keteranganternak-" + id).html();

    // tampilkan kegiatan ke inputbox
    $("#jenis_ternak").val(jenisternak);
    $("#satuan").val(satuanternak);
    $("#ket_jenis").val(keteranganternak);
    $("#no-ternak").val(id);

    // tampilkan modal edit
    $("#ubahData").modal("show");

}

function editBangunan(id) {

    // ambil data kegiatan
    var jenisbangunan = $("#jenisbangunan-" + id).html();
    var satuanbangunan = $("#satuanbangunan-" + id).html();


    // tampilkan kegiatan ke inputbox
    $("#jenis_bangunan").val(jenisbangunan);
    $("#satuan").val(satuanbangunan);

    $("#no-bangunan").val(id);

    // tampilkan modal edit
    $("#ubahData").modal("show");

}

function editAlatdanmesin(id) {

    // ambil data kegiatan
    var jenisalatdanmesin = $("#jenisalatdanmesin-" + id).html();
    var satuanalatdanmesin = $("#satuanalatdanmesin-" + id).html();


    // tampilkan kegiatan ke inputbox
    $("#jenis_alsin").val(jenisalatdanmesin);
    $("#satuan").val(satuanalatdanmesin);

    $("#no-alatdanmesin").val(id);

    // tampilkan modal edit
    $("#ubahData").modal("show");

}
