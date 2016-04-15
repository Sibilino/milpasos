milpasos = (function ($) {
    var mapObjects_ = []; // Variable to store data about gmaps objects in a page
    var pub = {
        isActive: true,
        init: function () {
            // Nothing to init
        },
        gmaps: {
            /**
             * Calls all functions in callbacks
             */
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
