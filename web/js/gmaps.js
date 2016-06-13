milpasos.gmaps = (function ($) {
    var mapLibraryReady_ = false;
    var mapCallbacks_ = [];
    var maps_ = [];
    var markers = [];
    var pub = {
        isActive: true,
        /**
         * Calls all functions in mapCallbacks_ and remembers that the maps library is ready.
         */
        initCallback: function () {
            mapLibraryReady_ = true;
            for (var i=0;i<mapCallbacks_.length;i++) {
                mapCallbacks_[i]();
            }
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
        },
        /**
         * Adds the map to the internal data array, so that it is available through getMap().
         * @param map
         * @param id The id to be associated with the map for later retrieval.
         * @param markers Optional array of Marker objects that have already been associated to the map.
         */
        addMap: function (map, id, markers) {
            if (typeof markers === "undefined") {
                markers = [];
            }
            maps_[id] = map;
            markers_[id] = markers;
        },
        /**
         * Returns the map object that was added with the given id, or null if the map id is not found.
         * @param id
         * @returns {*}|null
         */
        getMap: function (id) {
            if (id in maps_) {
                return maps_[id];
            }
            return null;
        },
        /**
         * Adds the marker to the internal array of markers corresponding to the map with the given id.
         * @param marker
         * @param mapId
         * @returns boolean True on success, false when mapId is not found.
         **/
        addMarker: function (marker, mapId) {
            if (mapId in markers_) {
                markers_[mapId].push(marker);
                return true;
            }
            return false;
        },
        /**
         * Returns the array of the markers added with to the map with the given id, or null if the id is not found.
         * @param id
         * @returns array|null
         */
        getMarkers: function (id) {
            if (id in markers_) {
                return markers_[id];
            }
            return null;
        }
    };
    return pub;
})(jQuery);
