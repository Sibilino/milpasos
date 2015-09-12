<?php

namespace app\widgets;

use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

class GeoComplete extends AutoComplete
{

    public function run()
    {
        MapsAsset::register($this->view);

        // TODO: Move javascript to asset bundle.

        $this->view->registerJs("var geocoder = new google.maps.Geocoder();");

        $this->clientOptions = ArrayHelper::merge([
            'source' => new JsExpression("function (request, response) {
                geocoder.geocode({'address': request.term}, function(results, status) {
                    $('#hidden-coords input').remove();
                    var resultAddresses = [];
                    for (var i=0; i<results.length; i++) {
                        var result = results[i];
                        resultAddresses.push(result.formatted_address);
                        $('#hidden-coords')
                            .append('<input result-ref=\"'+result.formatted_address+'\" type=\"hidden\" name=\"Event[lon]\" value=\"'+result.geometry.location.lng()+'\">')
                            .append('<input result-ref=\"'+result.formatted_address+'\" type=\"hidden\" name=\"Event[lat]\" value=\"'+result.geometry.location.lat()+'\">')
                        ;
                    }
                    response(resultAddresses);
                });
            }"),
            'select' => new JsExpression("function (event,ui) {
                $('#hidden-coords input').each(function () {
                    if ($(this).attr('result-ref') != ui.item.label) {
                        $(this).remove();
                    }
                });
            }"),
        ], $this->options);

        parent::run();
    }
}