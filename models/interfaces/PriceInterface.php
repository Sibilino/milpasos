<?php

namespace app\models\interfaces;

use 

interface PriceInterface
{
    public function getFormattedPrice();
    public function toEur();
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
    
    /**
     * Returns $this->price, converted to EUR.
     * @todo Implement actual currency conversion.
     * @return number
     */
    public function toEur()
    {
        return $this->price;
    }
}
