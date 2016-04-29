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
            /**
             * Adds the map to the internal data array, so that it is available through getMap().
             * @param map
             * @param id The id to be associated with the map for later retrieval.
             */
            addMap: function (map, id) {
                mapObjects_[id] = map;
            },
            /**
             * Returns the map object that was added with the given id.
             * @param id
             * @returns {*}
             */
            getMap: function (id) {
                if (id in mapObjects_) {
                    return mapObjects_[id];
                }
                return null;
            },
            /**
             * If the map object with id mapId has a markers array, add the given marker to it.
             * @param mapId
             * @param marker
             * @returns {boolean}
             */
            addMarkerTo: function (mapId, marker) {
                if (mapId in mapObjects_ && "markers" in mapObjects_[mapId]) {
                    mapObjects_[mapId].markers.push(marker);
                    return true;
                }
                return false;
            },
            /**
             * Register a callback function to be executed only after initCallback() has been called.
             * Useful to register multiple callbacks to be executed when a maps library is loaded.
             * @param callback
             */
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
