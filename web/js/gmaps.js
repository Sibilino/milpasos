milpasos.gmaps = (function ($) {
    var mapLibraryReady_ = false;
    var pendingCallbacks_ = [];
    var data_ = {};
    return {
        isActive: true,
        /**
         * Calls all functions in pendingCallbacks_ and remembers that the maps library is ready.
         */
        ready: function () {
            mapLibraryReady_ = true;
            for (var i=0;i<pendingCallbacks_.length;i++) {
                pendingCallbacks_[i]();
            }
            pendingCallbacks_ = [];
        },
        /**
         * Register a callback function to be executed when ready() is called after loading the gmaps library.
         * Useful to execute functions while ensuring that the maps library is ready.
         * @param callback
         */
        whenReady: function (callback) {
            if (!mapLibraryReady_) {
                pendingCallbacks_.push(callback);
            } else {
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
            data_[id] = {
                map: map,
                markers: markers
            };
        },
        /**
         * Returns the map object that was added with the given id, or null if the map id is not found.
         * @param id
         * @returns {*}|null
         */
        getMap: function (id) {
            if (id in data_) {
                return data_[id].map;
            }
            return null;
        },
        /**
         * Creates a marker with the given config and associates it to the map with the given mapId.
         * @param mapId
         * @param config
         * @returns boolean True on success, false when mapId is not found.
         **/
        addMarker: function (mapId, config) {
            config.map = this.getMap(mapId);
            if (config.map !== null) {
                
                this.whenReady(function () {
                    var marker = new google.maps.Marker(config);
                    data_[mapId].markers.push(marker);
                });
                
                return true;
            }
            return false;
        },
        /**
         * Returns the array of the markers added with to the map with the given id, or null if the id is not found.
         * @param mapId
         * @returns array|null
         */
        getMarkers: function (mapId) {
            if (mapId in data_) {
                return data_[mapId].markers;
            }
            return null;
        }
    };
})(jQuery);
