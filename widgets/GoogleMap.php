<?php

namespace app\widgets;

use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This widget renders a Map of the location defined in the model's data, with a marker in the middle.
 * The config requires a model, a lonAttribute and a latAttribute.
 * The Google Maps library also requires a key to be specified in your app's configuration:
 * 'assetManager' => [
 *       'bundles' => [
 *           'app\widgets\assets\MapsAsset' => [
 *                  'key' => 'your_key',
 *               ],
 *           ]
 *       ],
 * @package app\widgets
 */
class GoogleMap extends LocationWidget
{
    /**
     * @var float
     */
    public $defaultLat = 46.523661;
    /**
     * @var float
     */
    public $defaultLon = 6.622270;
    /**
     * @var int
     */
    public $defaultZoom = 5;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        if (!isset($this->attribute)) {
            $this->attribute = $this->latAttribute; // "attribute" is not needed but can raise errors if not set
        }
        parent::init();
    }


    /**
     * @return string The HTML code to be passed to the view.
     */
    protected function renderWidget()
    {
        $mapCenter = Json::encode([
            'lat' => $this->isLatLngSet() ? $this->getLat() : $this->defaultLat,
            'lng' => $this->isLatLngSet() ? $this->getLon() : $this->defaultLon,
        ]);
        $mapId = $this->options['id'];
        
        $script=<<<JS
milpasos.gmaps.addCallback(function () {
    var map = new google.maps.Map(document.getElementById('$mapId'), {
        center: $mapCenter,
        zoom: $this->defaultZoom
    });
    var marker = new google.maps.Marker({
        map: map,
        position: $mapCenter
    });
    milpasos.gmaps.addMap({
        object: map,
        markers: [marker]
    }, '$mapId');
});
JS;
        $this->view->registerJs($script);
        return Html::tag('div', '', $this->options);
    }
}
