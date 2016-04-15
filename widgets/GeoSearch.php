<?php

namespace app\widgets;

use app\assets\MilpasosAsset;
use app\widgets\assets\MapsAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class GeoSearch extends InputWidget
{
    /**
     * @var string The model attribute that will receive the longitude coordinate.
     */
    public $lonAttribute = 'lon';
    /**
     * @var string The model attribute that will receive the latitude coordinate.
     */
    public $latAttribute = 'lat';
    
    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        if (!$this->hasModel())
            throw new InvalidConfigException(self::className()." requires a Model and an Attribute.");
        if (!in_array($this->lonAttribute, $this->model->attributes()) || !in_array($this->latAttribute, $this->model->attributes()))
            throw new InvalidConfigException("The Model must have '$this->lonAttribute' and '$this->latAttribute' attributes.");
        parent::init();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        $html = Html::activeHiddenInput($this->model, $this->lonAttribute);
        $html .= Html::activeHiddenInput($this->model, $this->latAttribute);
        $html .= Html::activeTextInput($this->model, $this->attribute);
        $mapId = $this->getId();
        $html .= Html::tag('div', '', [
            'id' => $mapId,
            'style' => [
                'width' => '300px',
                'height' => '300px',
            ],
        ]);
        
        MilpasosAsset::register($this->view);

        $inputId = Html::getInputId($this->model, $this->attribute);
        $lonId = Html::getInputId($this->model, $this->lonAttribute);
        $latId = Html::getInputId($this->model, $this->latAttribute);
        if ($this->isLatLngValid()) {
            $currentLatLng = Json::encode([
                'lat' => Html::getAttributeValue($this->model, $this->latAttribute),
                'lng' => Html::getAttributeValue($this->model, $this->lonAttribute),
            ]);
            $mapCenter = $currentLatLng;
        } else {
            $currentLatLng = 'false';
            $mapCenter = '{lat: 46.523661, lng: 6.622270}'; // Centered in Europe
        }

        $script=<<<JS
var input = document.getElementById('$inputId');
var lonInput = document.getElementById('$lonId');
var latInput = document.getElementById('$latId');

milpasos.gmaps.callbacks.push(function () {
    var map = new google.maps.Map(document.getElementById('$mapId'), {
        center: $mapCenter,
        zoom: 5
    });
    
    var marker;
    var currentPosition = $currentLatLng;
    if (currentPosition !== false) {
        marker = new google.maps.Marker({
            map: map,
            position: currentPosition
        });
    }

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        lonInput.value = place.geometry.location.lng();
        latInput.value = place.geometry.location.lat();
        marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location
        });
        map.setCenter(place.geometry.location);
        map.setZoom(10); // About city level
    });
});

input.addEventListener('input', function () {
    lonInput.value = '';
    latInput.value = '';
});
JS;
        $this->view->registerJs($script);
        MapsAsset::register($this->view);
        
        return $html;
    }

    private function isLatLngValid() {
        return Html::getAttributeValue($this->model, $this->lonAttribute) && Html::getAttributeValue($this->model, $this->latAttribute);
    }
}
