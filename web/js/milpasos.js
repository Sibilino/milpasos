milpasos = (function ($) {
    var mapObjects_ = [];
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
            callbacks: [],
            addMap: function (map, id) {
                mapObjects_[id] = map;
            },
            getMap: function (id) {
                if (id in mapObjects_) {
                    return mapObjects_[id];
                }
                return null;
            }
        }
    };
    
    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(milpasos);
});
