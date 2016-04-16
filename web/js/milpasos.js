milpasos = (function ($) {
    var mapObjects_ = []; // Variable to store data about gmaps objects in a page
    var mapLibraryReady_ = false;
    var mapCallbacks_ = [];
    var pub = {
        isActive: true,
        init: function () {
            // Nothing to init
        },
        gmaps: {
            /**
             * Calls all functions in mapCallbacks_
             */
            initCallback: function () {
                mapLibraryReady_ = true;
                for (var i=0;i<mapCallbacks_.length;i++) {
                    mapCallbacks_[i]();
                }
            },
            addMap: function (map, id) {
                mapObjects_[id] = map;
            },
            getMap: function (id) {
                if (id in mapObjects_) {
                    return mapObjects_[id];
                }
                return null;
            },
            addMarkerTo: function (mapId, marker) {
                if (mapId in mapObjects_) {
                    mapObjects_[mapId].markers.push(marker);
                    return true;
                }
                return false;
            },
            addCallback: function (callback) {
                mapCallbacks_.push(callback);
                if (mapLibraryReady_) {
                    callback();
                }
            }
        }
    };
    
    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(milpasos);
});
