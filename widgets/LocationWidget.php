<?php

namespace app\widgets;

use app\assets\MilpasosAsset;
use app\widgets\assets\MapsAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

abstract class LocationWidget extends InputWidget
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
        if (!$this->model) {
            throw new InvalidConfigException("This widget must have a Model.");
        }
        if (!in_array($this->lonAttribute, $this->model->attributes()) || !in_array($this->latAttribute, $this->model->attributes()))
            throw new InvalidConfigException("The Model must have '$this->lonAttribute' and '$this->latAttribute' attributes.");
        MilpasosAsset::register($this->view);
        parent::init();
    }

    public function run()
    {
        $html = $this->renderWidget();
        MapsAsset::register($this->view);
        return $html;
    }

    protected function isLatLngSet() {
        return Html::getAttributeValue($this->model, $this->lonAttribute) && Html::getAttributeValue($this->model, $this->latAttribute);
    }

    protected function getLat() {
        return (float)Html::getAttributeValue($this->model, $this->latAttribute);
    }

    protected function getLon() {
        return (float)Html::getAttributeValue($this->model, $this->lonAttribute);
    }

    abstract protected function renderWidget();
    
}