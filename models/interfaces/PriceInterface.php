<?php

namespace app\models\interfaces;

use Yii;
use yii\base\InvalidConfigException;

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
     * @todo Implement currency conversion with online rates.
     * @return number
     * @throws InvalidConfigException If the currency cannot be converted.
     */
    public function toEur()
    {
        $rates = [
            'EUR' => 1,
            'CHF' => 0.91,
            'USD' => 0.9,
        ];
        if (!isset($rates[$this->currency])) {
            throw new InvalidConfigException("Conversion rate for currency '$this->currency' to EUR is not defined.");
        }
        return $this->price * $rates[$this->currency];
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
