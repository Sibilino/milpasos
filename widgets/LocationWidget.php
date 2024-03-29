<?php

namespace app\widgets;

use app\assets\MilpasosAsset;
use app\widgets\assets\GMapsLibrary;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class LocationWidget
 * @package app\widgets
 */
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
     * @var string If given, the GMaps places library language will be set to this value.
     */
    public $forceLanguage;

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

        if ($this->forceLanguage) {
            Yii::$app->assetManager->bundles[GMapsLibrary::className()]['language'] = $this->forceLanguage;
        }
        
        parent::init();
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $html = $this->renderWidget();
        GMapsLibrary::register($this->view);
        return $html;
    }

    /**
     * @return bool
     */
    protected function isLatLngSet() {
        return Html::getAttributeValue($this->model, $this->lonAttribute) !== null && Html::getAttributeValue($this->model, $this->latAttribute) !== null;
    }

    /**
     * @return float
     */
    protected function getLat() {
        return (float)Html::getAttributeValue($this->model, $this->latAttribute);
    }

    /**
     * @return float
     */
    protected function getLon() {
        return (float)Html::getAttributeValue($this->model, $this->lonAttribute);
    }

    /**
     * Generates the html code and registers the necessary scripts to display the widget.
     * @return string
     * @throws \Exception
     */
    abstract protected function renderWidget();
    
}
