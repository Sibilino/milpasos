milpasos.ajaxReloadBtn = (function ($) {
    function handler(event) {
        $.ajax({
            url: $(this).data('ajax-rld-url'),
            type: 'POST',
            error: alert('Error!'),
            success: function () {
                location.reload(true);
            }
        });
    }
    return {
        register: function (id) {
            $('#'+id).on('click', handler);
        }
    };
})();
