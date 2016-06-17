<?php

namespace app\widgets;

use app\widgets\assets\GMapsLibrary;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This widget displays an address search bar that optionally shows selected results in a map.
 * The config requires a model, an attribute (for the generated input), a lonAttribute and a latAttribute.
 * The Google Maps library also requires a key to be specified in your app's configuration:
 * 'assetManager' => [
 *       'bundles' => [
 *           'app\widgets\assets\GMapsLibrary' => [
 *                  'key' => 'your_key',
 *               ],
 *           ]
 *       ],
 * @package app\widgets
 */
class GeoSearch extends LocationWidget
{
    /**
     * @var boolean Whether to show a Google Map box after the search input.
     **/
    public $showMap = true;
    /**
     * @var array Html options to be applied to the map square.
     */
    public $mapOptions = [];
    /**
     * Whether to also show a button that set the user's current location in the lon lat fields.
     * Optional, default false.
     * @var boolean
     */
    public $currentLocationButton = false;
    /**
     * Additional JavaScript function to be assigend to the search field's "place_changed" event. Must not be plain string.
     * The function will have access to the following variables:
     * <ul>
     * <li>input</li>
     * <li>lonInput</li>
     * <li>latInput</li>
     * <li>autocomplete</li>
     * </ul>
     * @var string
     **/
    public $onPlaceChanged = '';
    
    /**
     * @var string Json representation of the string holding the search input's id.
     */
    protected $_inputId;
    /**
     * @var string Json representation of the string holding the lon input's id.
     */
    protected $_lonId;
    /**
     * @var string Json representation of the string holding the lat input's id.
     */
    protected $_latId;
    /**
     * @var string Json representation of the string holding the map div's id.
     */
    protected $_mapId;

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
        $this->_inputId = Json::encode(Html::getInputId($this->model, $this->attribute));
        $this->_lonId = Json::encode(Html::getInputId($this->model, $this->lonAttribute));
        $this->_latId = Json::encode(Html::getInputId($this->model, $this->latAttribute));
        $this->_mapId = Json::encode($this->mapOptions['id']);
    }

    /**
     * Generates the html code and registers the necessary scripts to display the widget.
     * @return string
     * @throws \Exception
     */
    protected function renderWidget()
    {
        GMapsLibrary::register($this->view);
        
        $html = Html::activeHiddenInput($this->model, $this->lonAttribute);
        $html .= Html::activeHiddenInput($this->model, $this->latAttribute);
        $html .= Html::beginTag('div', $this->currentLocationButton ? ['class'=>'input-group'] : []);
        $html .= Html::activeTextInput($this->model, $this->attribute, $this->options);
        
        if ($this->showMap) {
            // If the map has to be shown, better to register it BEFORE the javascript code that may add markers to it.
            $mapHtml = GoogleMap::widget([ // mapHtml is saved, to be appended later to the output string.
                'model' => $this->model,
                'latAttribute' => $this->latAttribute,
                'lonAttribute' => $this->lonAttribute,
                'options' => $this->mapOptions,
            ]);
        }

        $this->registerAutocompleteScript();

        if ($this->currentLocationButton) {
            $html .= $this->locationButton();
        }

        $html .= Html::endTag('div');

        if ($this->showMap) {
            $html .= $mapHtml;
        }

        return $html;
    }
    
    /**
     * Registers the JS code that gives the autocomplete search input its active functionality.
     **/
    protected function registerAutocompleteScript() {
        $customListener = $this->onPlaceChanged ? "autocomplete.addListener('place_changed', $this->onPlaceChanged);" : '';
        $script=<<<JS
var input = document.getElementById($this->_inputId);
var lonInput = document.getElementById($this->_lonId);
var latInput = document.getElementById($this->_latId);

milpasos.gmaps.whenReady(function () {
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        lonInput.value = place.geometry.location.lng();
        latInput.value = place.geometry.location.lat();
        var map = milpasos.gmaps.getMap($this->_mapId);
        if (map !== null) {
            milpasos.gmaps.clearMarkers($this->_mapId);
            milpasos.gmaps.addMarker($this->_mapId, {position: place.geometry.location});
            map.setOptions({
                center: place.geometry.location,
                zoom: 18
            });
        }
    });
    $customListener
});

input.addEventListener('input', function () {
    lonInput.value = '';
    latInput.value = '';
});
JS;

        $this->view->registerJs($script);
    }

    /**
     * @return string Generates the button that sets the user's current location.
     */
    protected function locationButton()
    {
        $this->registerLocationScript();
        $icon = Html::tag('span', '', ['class'=>'glyphicon glyphicon-screenshot']);
        $button = Html::button($icon, ['id' => $this->id.'-loc-btn', 'class' => 'btn btn-default']);
        return Html::tag('span', $button, ['class'=>'input-group-btn']);
    }
    
    /**
     * Registers the JS code that ask the user for their location and sets the position into the lat & lon inputs.
     **/
    protected function registerLocationScript() {
        $yourLocationLabel = Json::encode(\Yii::t('app', "Your current location"));
        $buttonId = Json::encode($this->id.'-loc-btn');

        $script = <<<JS
var locButton = document.getElementById($buttonId);
if (navigator.geolocation) {
    locButton.addEventListener('click', function (e) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var input = document.getElementById($this->_inputId);
            input.value = $yourLocationLabel;
            input.addEventListener('focus', function (e) {
                e.target.removeEventListener(e.type, arguments.callee); // one-time event
                this.value = '';
            });
            document.getElementById($this->_lonId).value = position.coords.latitude;
            document.getElementById($this->_latId).value = position.coords.latitude;
        }); 
    });
} else {
    locButton.disabled = true;
}
JS;
        $this->view->registerJs($script);
    }
}
