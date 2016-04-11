milpasos = (function ($) {
    var pub = {
        isActive: true,
        init: function () {
            // Nothing to init
        },
        gmaps: {
            callback: function () {}
        }
    };
    
    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(milpasos);
});
