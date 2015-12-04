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
        $("#selection-form").empty();
        e.target.getFeatures().forEach(function (cluster) {
            cluster.get("features").forEach(function (feature) {
                var eventId = feature.get("eventId");
                if (eventId) {
                    $("#selection-form").append('<input type="hidden" name="EventSelectionForm[ids][]" value="' + eventId + '">');
                }
            });
        });
        $("#selection-form").submit();
    });
    return {
        interactions: ol.interaction.defaults().extend([select])
    }
} )(jQuery);