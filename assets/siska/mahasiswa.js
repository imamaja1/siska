
$(function () {
    // Daterange Picker
    $('#tanggal').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        format: 'YYYY-MM-DD'
    });

    // Data Table
    $("#data").dataTable({
        scrollX: true
    });
});

$(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
});
var selec2init = function () {
    $(".select2").select2();
}



$(document).ready(function () {
    $.widget.bridge('uibutton', $.ui.button);
    selec2init();
});

function konfirmasiKeluar(url) {
    swal({
        title: "",
        text: "Anda yakin ingin keluar dari sistem ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'red',
        cancelButtonText: 'Tidak',
        confirmButtonText: 'Ya',
        closeOnConfirm: false
    }).then(function () {
        window.location.href = url;
    })
}
