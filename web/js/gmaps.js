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
         * Creates the Google Map with the given config and associates it to the given id for later retrieval through getMap().
         * An optional array of marker configs can be given, so that the corresponding markers are created via addMarker().
         * @param id The id to be associated with the map for later retrieval.
         * @param config The configuration to be passed to the map constructor.
         * @param markers Optional array of Marker configuration objects. The 'map' property of each of the configs will be set automatically.
         */
        addMap: function (id, config, markers) {
            if (typeof markers === "undefined") {
                markers = [];
            }
            var module = this;
            module.whenReady(function () {
                data_[id] = {
                    map: new google.maps.Map(document.getElementById(id), config),
                    markers: []
                };
                for (var i=0;i<markers.length;i++) {
                    module.addMarker(id, markers[i]);
                }
            });
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
         * @param config The map property is not necessary; it will be set automatically.
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
