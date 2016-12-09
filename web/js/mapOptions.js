sibilino.olwidget.mapOptions["main-map"] = (function ($) {
    var select = new ol.interaction.Select({
        style: function (feature, resolution) {
            return [
                new ol.style.Style({
                    image: new ol.style.Circle({
                        radius: 15,
                        fill: new ol.style.Fill({
                            color: '#FF0000'
                        }),
                        stroke: new ol.style.Stroke({
                            color: '#000000'
                        })
                    }),
                    text: new ol.style.Text({
                        text: feature.get("features").length.toString(),
                        fill: new ol.style.Fill({
                            color: '#FFFFFF'
                        }),
                        font: '14px Helvetica'
                    })
                })
            ];
        }
    });
    
    /**
     * Extracts the Event ids of any Events in the given selection target.
     * @return Array of integers
     */
    var extractEventIds = function (target) {
        var eventIds = [];
        var features = target.getFeatures();
        if (features.getLength() > 0) {
            features.forEach(function (cluster) {
                // Get all features' eventIds
                eventIds = cluster.get("features").map(function ($f) {return $f.get("eventId");});
            });
        }
        return eventIds;
    }
    
    /**
     * Publish "select" event of the map's Select interaction into a public module.
     * Usage: milpasos.eventMap.addEventSelectListener(your_listener_func);
     * @param listener
     */
    milpasos.eventMap = {
        addEventSelectListener: function (listener) {
            select.on("select", function (e) {
                listener(extractEventIds(e.target));
            });
        }
    };
    
    return {
        interactions: ol.interaction.defaults().extend([select])
    }
} )(jQuery);
