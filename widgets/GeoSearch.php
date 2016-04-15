<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * This widget displays an address search bar that shows selected results in a map.
 * @package app\widgets
 */
class GeoSearch extends LocationWidget
{
    /**
     * @var array Html options to be applied to the map square.
     */
    public $mapOptions = [];

    /**
     * Initializes required options and checks their validity.
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!$this->hasModel())
            throw new InvalidConfigException(self::className()." requires a Model and an Attribute.");
        if (!isset($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }
        if (!isset($this->mapOptions['id'])) {
            $this->mapOptions['id'] = $this->options['id'].'-gmapwgt';
        }
    }

    /**
     * Generates the html code and registers the necessary scripts to display the widget.
     * @return string
     * @throws \Exception
     */
    protected function renderWidget()
    {
        $html = Html::activeHiddenInput($this->model, $this->lonAttribute);
        $html .= Html::activeHiddenInput($this->model, $this->latAttribute);
        $html .= Html::activeTextInput($this->model, $this->attribute, $this->options);
        $html .= GoogleMap::widget([
            'model' => $this->model,
            'latAttribute' => $this->latAttribute,
            'lonAttribute' => $this->lonAttribute,
            'options' => $this->mapOptions,
        ]);
        
        $inputId = Html::getInputId($this->model, $this->attribute);
        $lonId = Html::getInputId($this->model, $this->lonAttribute);
        $latId = Html::getInputId($this->model, $this->latAttribute);
        $mapId = $this->mapOptions['id'];

        $script=<<<JS
var input = document.getElementById('$inputId');
var lonInput = document.getElementById('$lonId');
var latInput = document.getElementById('$latId');

milpasos.gmaps.callbacks.push(function () {
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        lonInput.value = place.geometry.location.lng();
        latInput.value = place.geometry.location.lat();
        var map = milpasos.gmaps.getMap('$mapId');
        for (var i=0;i<map.markers.length;i++) {
            map.markers[i].setMap(null); // Remove previous markers
        }
        var marker = new google.maps.Marker({
            position: place.geometry.location,
            map: map.object
        });
        map.markers = [marker];
        map.object.setCenter(place.geometry.location);
    });
});

input.addEventListener('input', function () {
    lonInput.value = '';
    latInput.value = '';
});
JS;
        $this->view->registerJs($script);
        
        return $html;
    }
}
