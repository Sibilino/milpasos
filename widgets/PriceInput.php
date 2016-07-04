<?php

namespace app\widgets;


use app\widgets\assets\PriceInputBundle;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

/**
 * Widget that displays a number input for the given model and attribute, followed by a dropdown currency selector
 * whose value is also stored in the model (in the $currencyAttr).
 * @package app\widgets
 */
class PriceInput extends InputWidget
{

    /**
     * @var string The attribute of the model to be used for the currency dropdown. Default is 'currency'.
     */
    public $currencyAttr = 'currency';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!key_exists($this->currencyAttr, $this->model->attributes)) {
            throw new InvalidConfigException("The model does not have a '$this->currencyAttr' attribute.");
        }
        parent::init();
    }

    public function run()
    {
        PriceInputBundle::register($this->view);

        echo Html::beginTag('div', ['class'=>'price-input-widget']);
        echo Html::activeInput('number',$this->model, $this->attribute, ArrayHelper::merge(['class'=>'form-control'], $this->options));
        echo Html::activeDropDownList($this->model, $this->currencyAttr, Yii::$app->currencyConverter->currencyLabels, ArrayHelper::merge(['class'=>'form-control'], $this->options));
        echo Html::endTag('div');
    }


}
