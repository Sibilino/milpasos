<?php

namespace app\widgets;

use app\widgets\assets\MapsAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This widget displays an address search bar that shows selected results in a map.
 * The config requires a model, an attribute (for the generated input), a lonAttribute and a latAttribute.
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
class GeoSearch extends LocationWidget
{
    /**
     * @var array Html options to be applied to the map square.
     */
    public $mapOptions = [];
    /**
     * @var boolean Whether to ask the user for his location when the model's attribute is empty. Default false.
     */
    public $askForLocation = false;

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
        MapsAsset::register($this->view);
        
        $html = Html::activeHiddenInput($this->model, $this->lonAttribute);
        $html .= Html::activeHiddenInput($this->model, $this->latAttribute);
        $html .= Html::activeTextInput($this->model, $this->attribute, $this->options);
        
        $inputId = Json::encode(Html::getInputId($this->model, $this->attribute));
        $lonId = Json::encode(Html::getInputId($this->model, $this->lonAttribute));
        $latId = Json::encode(Html::getInputId($this->model, $this->latAttribute));
        $mapId = Json::encode($this->mapOptions['id']);

        $script=<<<JS
var input = document.getElementById($inputId);
var lonInput = document.getElementById($lonId);
var latInput = document.getElementById($latId);

milpasos.gmaps.whenReady(function () {
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        lonInput.value = place.geometry.location.lng();
        latInput.value = place.geometry.location.lat();
        var map = milpasos.gmaps.getMap($mapId);
        if (map !== null) {
            milpasos.gmaps.clearMarkers($mapId);
            milpasos.gmaps.addMarker($mapId, {position: place.geometry.location});
            map.setOptions({
                center: place.geometry.location,
                zoom: 18
            });
        }
    });
});

input.addEventListener('input', function () {
    lonInput.value = '';
    latInput.value = '';
});
JS;



        $this->view->registerJs($script);


        if ($this->askForLocation && !$this->model->{$this->attribute}) {
            $yourLocationLabel = Json::encode(\Yii::t('app', "Your current location"));

            $script = <<<JS
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
        var input = document.getElementById($inputId);
        input.value = $yourLocationLabel;
        input.addEventListener('input', function () { this.value = ''; })
        document.getElementById($lonId).value = position.coords.latitude;
        document.getElementById($latId).value = position.coords.latitude;
    });    
}
JS;
            $this->view->registerJs($script);

        }

        $html .= GoogleMap::widget([
            'model' => $this->model,
            'latAttribute' => $this->latAttribute,
            'lonAttribute' => $this->lonAttribute,
            'options' => $this->mapOptions,
        ]);

        return $html;
    }
}
