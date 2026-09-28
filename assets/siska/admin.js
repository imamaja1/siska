
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

// Mobile off-canvas sidebar: dimmed backdrop + close on tap
(function ($) {
    $(function () {
        // --- Sidebar performance tuning ---
        // AdminLTE's default 500ms treeview slide + slimScroll wrapper make the
        // sidebar feel heavy (extra DOM, scroll handlers, layout recalc per frame).
        if ($.AdminLTE && $.AdminLTE.options) {
            $.AdminLTE.options.animationSpeed = 150;     // snappier submenu slide
            $.AdminLTE.options.sidebarSlimScroll = false; // native scroll instead of slimScroll
        }
        // The treeview calls layout.fix() after every submenu open; it forces a
        // full sidebar/height recalculation. Keep it lightweight.
        if ($.AdminLTE && $.AdminLTE.layout) {
            var _origFix = $.AdminLTE.layout.fix;
            var _fixTimer = null;
            $.AdminLTE.layout.fix = function () {
                if (_fixTimer) return;
                _fixTimer = setTimeout(function () {
                    _fixTimer = null;
                    if (typeof _origFix === 'function') {
                        _origFix.apply($.AdminLTE.layout, arguments);
                    }
                }, 120);
            };
        }

        // --- Lightweight CSS-driven treeview ---
        // Replaces AdminLTE's jQuery slideUp/slideDown (JS animates height each
        // frame -> layout thrash). The browser animates max-height instead.
        // The CSS only applies once this class is present, so a missing/stale JS
        // never leaves the submenus permanently hidden.
        $('body').addClass('siska-treeview');

        // AdminLTE binds its own treeview on ready; rebind ours afterwards so it wins.
        setTimeout(function () {
            // Highlight the current leaf menu item (2nd/3rd level) by matching each
            // menu link against the browser URL, so submenu items get an active state.
            var currentPath = window.location.pathname.replace(/\/+$/, '');
            var linkParser = document.createElement('a');
            $('.sidebar-menu li > a').each(function () {
                var href = $(this).attr('href');
                if (!href || href === '#' || href.indexOf('javascript:') === 0) {
                    return;
                }
                linkParser.href = href;
                if (linkParser.pathname.replace(/\/+$/, '') === currentPath) {
                    $(this).parent('li').addClass('active');
                }
            });

            // Auto-open the active section (including nested 2nd/3rd level) so the
            // caret and highlighted item match the visible submenu.
            $('.sidebar-menu li.active').each(function () {
                var $li = $(this);
                var $sub = $li.children('.treeview-menu');
                if ($sub.length) {
                    $li.addClass('menu-open');
                    $sub.css('max-height', 'none');
                }
                // open every ancestor section too
                $li.parents('li').each(function () {
                    var $p = $(this);
                    var $ps = $p.children('.treeview-menu');
                    if ($ps.length) {
                        $p.addClass('menu-open');
                        $ps.css('max-height', 'none');
                    }
                });
            });

            $(document).off('click', '.sidebar li a');
            $(document).on('click', '.sidebar-menu li > a', function (e) {
                var $a = $(this);
                var $sub = $a.next('.treeview-menu');
                if (!$sub.length) {
                    return; // leaf link -> navigate normally
                }
                e.preventDefault();
                var $li = $a.parent();

                function closeMenu($l) {
                    var $s = $l.children('.treeview-menu');
                    if (!$s.length) return;
                    $s.css('max-height', $s[0].scrollHeight + 'px');
                    $s[0].offsetHeight; // force reflow so the transition starts from here
                    $s.css('max-height', '0px');
                    $l.removeClass('menu-open');
                }

                if ($li.hasClass('menu-open')) {
                    closeMenu($li);
                } else {
                    $li.siblings('li.menu-open').each(function () { closeMenu($(this)); });
                    $sub.css('max-height', $sub[0].scrollHeight + 'px');
                    $li.addClass('menu-open');
                    // After the transition, drop the cap so nested menus can grow freely
                    clearTimeout($sub.data('mhTimer'));
                    $sub.data('mhTimer', setTimeout(function () {
                        if ($li.hasClass('menu-open')) {
                            $sub.css('max-height', 'none');
                        }
                    }, 320));
                }
            });
        }, 0);

        var $backdrop = $('<div class="siska-sidebar-backdrop" aria-hidden="true"></div>').appendTo('body');

        function closeSidebar() {
            $('body').removeClass('sidebar-open');
            $backdrop.removeClass('is-open');
        }

        // Sync backdrop with AdminLTE's sidebar toggle
        $(document).on('click', '.sidebar-toggle', function () {
            setTimeout(function () {
                $backdrop.toggleClass('is-open', $('body').hasClass('sidebar-open'));
            }, 0);
        });

        $backdrop.on('click', closeSidebar);

        // Close only when a real (leaf) link is tapped — not when toggling a submenu
        $(document).on('click', '.sidebar-menu li > a', function () {
            if (window.innerWidth < 768 && !$(this).next('.treeview-menu').length) {
                closeSidebar();
            }
        });

        $(window).on('resize', function () {
            if (window.innerWidth >= 768) {
                closeSidebar();
            }
        });
    });
})(jQuery);
