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

        $this->view->registerJs("var geocoder = new google.maps.Geocoder();");

        $this->clientOptions = ArrayHelper::merge([
            'source' => new JsExpression("function (request, response) {
                geocoder.geocode({'address': request.term}, function(results, status) {
                    var resultAddresses = [];
                    for (var i=0; i<results.length; i++) {
                        resultAddresses.push(results[i].formatted_address);
                    }
                    response(resultAddresses);
                });
            }"),
        ], $this->options);

        // TODO: Upon choosing a suggestion, fill lon & lat inputs.
        // TODO: Generate hidden lon & lat inputs.

        parent::run();
    }
}