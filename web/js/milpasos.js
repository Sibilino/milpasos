milpasos = (function ($) {
    var pub = {
        isActive: true,
        init: function () {
            // Nothing to init
        },
        gmaps: {
            initCallback: function () {
                for (var i=0;i<pub.gmaps.callbacks.length;i++) {
                    pub.gmaps.callbacks[i]();
                }
            },
            callbacks: []
        }
    };
    
    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(milpasos);
});
