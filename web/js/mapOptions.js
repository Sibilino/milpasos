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
    milpasos.addMapSelectListener = function (listener) {
        select.on("select", listener);
    };
    
    return {
        interactions: ol.interaction.defaults().extend([select])
    }
} )(jQuery);
