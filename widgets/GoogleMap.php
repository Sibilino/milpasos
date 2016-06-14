<?php

namespace app\widgets;

use app\widgets\assets\MapsAsset;
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
     * @var int The zoom level for the map when no markers are visible.
     */
    public $defaultZoom = 5;
    /**
     * @var int The zoom level for the map when a marker is visible.
     **/
    public $markerZoom = 12;

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
        MapsAsset::register($this->view);
        
        $mapCenter = [
            'lat' => $this->isLatLngSet() ? $this->getLat() : $this->defaultLat,
            'lng' => $this->isLatLngSet() ? $this->getLon() : $this->defaultLon,
        ];
        
        $mapId = Json::encode($this->options['id']);
        $config = Json::encode([
            'center' => $mapCenter,
            'zoom' => $this->isLatLngSet() ? $this->markerZoom : $this->defaultZoom,
        ]);
        $markers = Json::encode($this->isLatLngSet() ? [['position'=>$mapCenter]] : []);
        
        $this->view->registerJs("milpasos.gmaps.addMap($mapId, $config, $markers);");

        return Html::tag('div', '', $this->options);
    }
}
