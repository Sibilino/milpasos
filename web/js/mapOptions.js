sibilino.olwidget.mapOptions["main-map"] = (function ($) {
    var select = new ol.interaction.Select({
        style: function (feature, resolution) {
            return [
                new ol.style.Style({
                    image: new ol.style.Circle({
                        radius: 10,
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
                        })
                    })
                })
            ];
        }
    });
    select.on("select", function (e) {
        e.target.getFeatures().forEach(function (cluster) {
            // Get all features' eventIds in a hyphen-separated string
            var eventIds = cluster.get("features").map(function ($f) {return $f.get("eventId");});
            milpasos.EventViewer.selectEvents(eventIds);
        });
    });
    return {
        interactions: ol.interaction.defaults().extend([select])
    }
} )(jQuery);
