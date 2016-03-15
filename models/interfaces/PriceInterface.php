<?php

namespace app\models\interfaces;

use 

interface PriceInterface
{
    public function getFormattedPrice();
    public function toEur();
    public function isCurrent();
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
    
    /**
     * Returns whether this price is currently available, based on $this->available_from and $this->available_to.
     * @return boolean
     */
    public function isCurrent()
    {
        $today = date('Y-m-d');
        return $this->available_from <= $today && $this->available_to >= $today;
    }
}
