<script>
var csrfName = '<?= $this->security->get_csrf_token_name() ?>';
var csrfHash = '<?= $this->security->get_csrf_hash() ?>';

window.siskaPostAction = function(url) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = csrfName;
    input.value = csrfHash;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
};

$(document).ready(function() {
    $(document).on('submit', 'form', function() {
        var method = ($(this).attr('method') || 'get').toLowerCase();
        if (method === 'get' || method === 'head') {
            return;
        }
        if (!$(this).find('input[name="' + csrfName + '"]').length) {
            $('<input>').attr({type: 'hidden', name: csrfName, value: csrfHash}).appendTo($(this));
        }
    });
});

$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
    if (options.type && options.type.toUpperCase() !== 'GET' && options.type.toUpperCase() !== 'HEAD') {
        if (typeof originalOptions.data === 'string') {
            if (originalOptions.data.indexOf(csrfName + '=') === -1) {
                options.data = (originalOptions.data ? originalOptions.data + '&' : '') + csrfName + '=' + encodeURIComponent(csrfHash);
            }
        } else if (originalOptions.data instanceof FormData) {
            if (!originalOptions.data.has(csrfName)) {
                originalOptions.data.append(csrfName, csrfHash);
            }
        } else {
            options.data = $.param($.extend(originalOptions.data || {}, (function() {
                var d = {};
                d[csrfName] = csrfHash;
                return d;
            })()));
        }
    }
});
</script>
