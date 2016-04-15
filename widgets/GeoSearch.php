<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Class GeoSearch
 * @package app\widgets
 */
class GeoSearch extends LocationWidget
{
    /**
     * @var array
     */
    public $mapOptions = [];
    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        if (!$this->hasModel())
            throw new InvalidConfigException(self::className()." requires a Model and an Attribute.");
        if (!isset($this->mapOptions['id'])) {
            $this->mapOptions['id'] = $this->options['id'].'-gmapwgt';
        }

    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function renderWidget()
    {
        $html = Html::activeHiddenInput($this->model, $this->lonAttribute);
        $html .= Html::activeHiddenInput($this->model, $this->latAttribute);
        $html .= Html::activeTextInput($this->model, $this->attribute);
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
