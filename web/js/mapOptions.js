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
        var idLists = [];
        e.target.getFeatures().forEach(function (cluster) {
            // Get all features' eventIds in a hyphen-separated string
            idLists.push(cluster.get("features").map(function ($f) {return $f.get("eventId");}).join('-'));
        });
        $('#selection-input').val(idLists.join('-'));
        $('#selection-form').submit();
    });
    return {
        interactions: ol.interaction.defaults().extend([select])
    }
} )(jQuery);
