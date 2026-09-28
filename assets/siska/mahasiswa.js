
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
var selec2init = function (context) {
    if (!$.fn.select2) return;
    var $scope = context ? $(context) : $(document);
    var $targets = $scope.is('select') ? $scope : $scope.find('select');
    $targets.each(function () {
        var $this = $(this);
        if ($this.hasClass('select2-hidden-accessible') ||
            $this.closest('.dataTables_length').length ||
            $this.is('[name$="_length"]') ||
            $this.closest('.daterangepicker').length ||
            $this.hasClass('swal2-select') ||
            $this.hasClass('no-select2')) {
            return;
        }
        var width = '100%';
        if ($this.closest('.form-inline').length) {
            width = ($this.attr('style') && $this.attr('style').indexOf('width') !== -1) ? 'resolve' : 'auto';
        }
        var opts = { width: width };
        var $modal = $this.closest('.modal');
        if ($modal.length) {
            opts.dropdownParent = $modal;
        }
        $this.addClass('select2').select2(opts);
    });
};

(function ($) {
    if (!$) return;
    var origHtml = $.fn.html;
    $.fn.html = function () {
        var result = origHtml.apply(this, arguments);
        if (arguments.length > 0) {
            this.each(function () {
                if ($(this).is('select.select2-hidden-accessible')) {
                    $(this).trigger('change.select2');
                }
            });
        }
        return result;
    };
})(jQuery);

$(document).ready(function () {
    $.widget.bridge('uibutton', $.ui.button);
    selec2init();
});

$(document).on('shown.bs.modal', function (e) {
    selec2init(e.target);
});

$(document).ajaxComplete(function () {
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
