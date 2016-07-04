<?php

namespace app\components;

use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Configure $rates as an array of currency_code => rate_to_EUR and use the toEur() function to convert an amount.
 */
class MockCurrencyConverter extends Component
{
    /**
     * Array of currency_code => rate_to_EUR
     */
    public $rates = [];
    /**
     * Array of currency_code => display_label
     **/
    public $currencyLabels = [];

    /**
     * Returns the equivalent price in Euros for the given amount in a configured currency.
     * @param number $amount
     * @param string $currency
     * @return number
     * @throws InvalidConfigException If the recuested currency is not in configured in $rates.
     */
    public function toEur($amount, $currency)
    {
        if (!isset($this->rates[$currency])) {
            throw new InvalidConfigException("Conversion rate for currency '$currency' to EUR is not defined.");
        }
        return $amount * $this->rates[$currency];
    }
}
