function DetailMhs(data) {
    document.getElementById("nim_detail").innerHTML = data.nim;
    document.getElementById("npm_detail").innerHTML = data.npm;
    document.getElementById("pendaftaran_detail").innerHTML = data.no_pendaftaran;
    document.getElementById("pendaftaran_ulang_detail").innerHTML = data.no_pendaftaran_ulang;
    document.getElementById("nama_detail").innerHTML = data.nama_mhs;
    document.getElementById("tempat_t_lahir").innerHTML = data.tempat_t_lahir;
    document.getElementById("alamat").innerHTML = data.alamat;
    document.getElementById('propinsi').innerHTML = data.propinsi;
    document.getElementById('telepon').innerHTML = data.telepon;
    var jenis = data.jenis_kelamin;
    if (jenis === "L") {
        document.getElementById('jenis_kelamin').innerHTML = "LAKI-LAKI";
    } else {
        document.getElementById('jenis_kelamin').innerHTML = "PEREMPUAN";
    }
    document.getElementById('agama').innerHTML = data.agama;
    document.getElementById('golongan_darah').innerHTML = data.golongan_darah;

    var warga_negara = data.kewarganegaraan;
    if (warga_negara === "WNI") {
        document.getElementById('kewarganegaraan').innerHTML = "WNI (Wargan Negara Indonesia)";
    } else {
        document.getElementById('kewarganegaraan').innerHTML = "WNA (Wargan Negara Asing)";
    }
    document.getElementById('nama_instansi').innerHTML = data.nama_instansi;
    document.getElementById('email').innerHTML = data.email;

    document.getElementById('nama_ayah').innerHTML = data.nama_ayah;
    document.getElementById('agama_ayah').innerHTML = data.agama_ayah;
    document.getElementById('pekerjaan_ayah').innerHTML = data.pekerjaan_ayah;

    document.getElementById('nama_ibu').innerHTML = data.nama_ibu;
    document.getElementById('agama_ibu').innerHTML = data.agama_ibu;
    document.getElementById('pekerjaan_ibu').innerHTML = data.pekerjaan_ibu;
    document.getElementById('alamat_orangtua').innerHTML = data.alamat_orangtua;
    document.getElementById('kota_orangtua').innerHTML = data.kota_orangtua;
    document.getElementById('no_telepon_orangtua').innerHTML = data.no_telpon_orangtua;

    $("#modal_detail").modal('toggle');

}