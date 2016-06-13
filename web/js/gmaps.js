milpasos.gmaps = (function ($) {
    var mapLibraryReady_ = false;
    var mapCallbacks_ = [];
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
        }
        
    };
    return pub;
})(jQuery);
