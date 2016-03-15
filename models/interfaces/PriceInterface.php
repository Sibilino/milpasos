<?php

namespace app\models\interfaces;

use 

interface PriceInterface
{
    public function getFormattedPrice();
}

trait PriceTrait
{
    /**
     * Returns the formatted string that represents $this->price, expressed in $this->currency.
     * @return string
     */
    public function getFormattedPrice()
    {
        return Yii::$app->formatter->asCurrency($this->price, $this->currency);
    }
}
