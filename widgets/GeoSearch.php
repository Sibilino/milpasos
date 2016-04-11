<?php

namespace app\widgets;

use app\assets\MilpasosAsset;
use app\widgets\assets\MapsAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
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
        echo Html::activeHiddenInput($this->model, $this->lonAttribute);
        echo Html::activeHiddenInput($this->model, $this->latAttribute);
        echo Html::activeTextInput($this->model, $this->attribute);
        
        MilpasosAsset::register($this->view);
        $inputId = Html::getInputId($this->model, $this->attribute);
        $lonId = Html::getInputId($this->model, $this->lonAttribute);
        $latId = Html::getInputId($this->model, $this->latAttribute);
        $script=<<<JS
milpasos.gmaps.callback = function () {
    var autocomplete = new google.maps.places.Autocomplete(document.getElementById('$inputId'));
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        document.getElementById('$lonId').value = place.geometry.location.lng();
        document.getElementById('$latId').value = place.geometry.location.lat();
    });
};
JS;
        $this->view->registerJs($script);
        MapsAsset::register($this->view);
    }
}
