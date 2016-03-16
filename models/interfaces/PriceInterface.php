<?php

namespace app\models\interfaces;

use Yii;

/**
 * Interface PriceInterface ensures that an object has some currency-related methods.
 * The required methods can be easily provided by app\models\interface\PriceTrait.
 * @package app\models\interfaces
 */
interface PriceInterface
{
    public function getFormattedPrice();
    public function toEur();
    public function isCurrent();
}

/**
 * Provides the methods that implement a PriceInterface, using the following properties of the receiving object:
 * <ul>
 * <li>$price</li>
 * <li>$currency</li>
 * <li>$available_from</li>
 * <li>$available_to</li>
 * </ul>
 * @package app\models\interfaces
 */
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
     * Returns $this->price, converted to EUR. Uses the app's "currencyConverter" component.
     * @return number
     */
    public function toEur()
    {
        return Yii::$app->currencyConverter->toEur($this->price, $this->currency);
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
