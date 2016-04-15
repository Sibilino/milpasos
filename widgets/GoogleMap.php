<?php

namespace app\widgets;

use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class GoogleMap
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
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        if (!isset($this->attribute)) {
            $this->attribute = $this->latAttribute; // "attribute" is not needed but can raise errors if not set
        }
        parent::init();
    }


    /**
     * @return string
     */
    protected function renderWidget()
    {
        $mapCenter = Json::encode([
            'lat' => $this->isLatLngSet() ? $this->getLat() : $this->defaultLat,
            'lng' => $this->isLatLngSet() ? $this->getLon() : $this->defaultLon,
        ]);
        $mapId = $this->options['id'];
        
        $script=<<<JS
milpasos.gmaps.callbacks.push(function () {
    var map = new google.maps.Map(document.getElementById('$mapId'), {
        center: $mapCenter,
        zoom: $this->defaultZoom
    });
    var mapObject = {
        object: map,
        markers: []
    };
    milpasos.gmaps.addMap(mapObject, '$mapId');
JS;
        if ($this->isLatLngSet()) {
            $script .= 'mapObject.markers.push(new google.maps.Marker({map: map, position: $mapCenter}));';
        }

        $script .= '});'
        $this->view->registerJs($script);
        return Html::tag('div', '', $this->options);
    }
}
