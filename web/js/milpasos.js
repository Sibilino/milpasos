milpasos = (function ($) {
    var pub = {
        isActive: true,
        init: function () {
            // Nothing to init
        },
        
        // Root module for Milpasos. Only holds submodules, like:
        // milpasos.submodule1 = (function ($) { //Module content... })(jQuery);
    };
    
    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(milpasos);
});
