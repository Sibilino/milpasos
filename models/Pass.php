<?php

namespace app\models;

use app\models\interfaces\PriceTrait;
use Yii;
use app\models\interfaces\PriceInterface;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pass".
 *
 * @property string $id
 * @property string $price
 * @property string $currency
 * @property string $description
 * @property string $available_from
 * @property string $available_to
 * @property boolean $full
 * @property string $event_id
 * @property PriceInterface $currentLowestPrice
 *
 * @property Event $event
 * @property TemporaryPrice[] $temporaryPrices
 */
class Pass extends ActiveRecord implements PriceInterface
{
    use PriceTrait;
    
    /**
     * @var array The possible currencies for price and any temporary prices, as "3-letterCode" => "displaySymbol".
     */
    public static $currencies = [
        'EUR' => '€',
        'CHF' => 'CHF',
        'USD' => '$',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pass';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full', 'event_id', 'price', 'currency'], 'required'],
            [['price'], 'number', 'min' => 0],
            [['full'], 'boolean'],
            [['available_from', 'available_to', 'price'], 'default', 'value' => null],
            [['available_from', 'available_to'], 'date', 'format' => 'yyyy-MM-dd'],
            [['currency'], 'in', 'range' => array_keys(static::$currencies), 'strict' => true],
            [['event_id'], 'exist', 'targetClass' => Event::className(), 'targetAttribute' => 'id'],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'price' => Yii::t('app', 'Normal Price'),
            'currency' => Yii::t('app', 'Currency'),
            'description' => Yii::t('app', 'Description'),
            'available_from' => Yii::t('app', 'Available From'),
            'available_to' => Yii::t('app', 'Available To'),
            'full' => Yii::t('app', "It's a Full Pass"),
            'event_id' => Yii::t('app', 'Event'),
        ];
    }
    
    /**
     * Returns the lowest price that is currently available for this Pass.
     * @return PriceInterface
     */
    public function getCurrentLowestPrice()
    {
        $prices = array_filter($this->temporaryPrices, function (TemporaryPrice $p) {
            return $p->isCurrent();
        });
        array_push($prices, $this);
        $eurPrices = ArrayHelper::index($prices, function (PriceInterface $p) {
            return $p->toEur();
        });
        ksort($eurPrices, SORT_NUMERIC);
        return reset($eurPrices);
    }

    /**
     * Returns a list of existing TempPrices for this Pass and adds a new TempPrice model "template" at the end.
     * @return TemporaryPrice[]
     */
    public function generatePriceList()
    {
        $prices = $this->getTemporaryPrices()->orderBy('available_from')->all();
        array_walk($prices, function (TemporaryPrice $p) {
            $p->scenario = TemporaryPrice::SCENARIO_IN_PASS;
        });
        array_push($prices, $this->getNextPriceSuggestion());
        return $prices;
    }

    /**
     * Attach the given list of prices to this Pass, saving them to the DB.
     * @param TemporaryPrice[] $prices
     * @return bool Whether all $prices were saved successfully.
     */
    public function updatePriceList(array $prices)
    {
        $errors = 0;
        foreach ($prices as $p) {
            $p->pass_id = $this->id; // Better not to trust pass_id from user form
            if (!$p->save()) {
                $errors++;
            }
        };
        
        return $errors == 0;
    }

    /**
     * Generates a new TemporaryPrice model with default attribute values to easily create a price for the next logical
     * time step.
     * @return TemporaryPrice
     */
    private function getNextPriceSuggestion()
    {
        /* @var $lastPrice TemporaryPrice */
        $lastPrice = $this->getTemporaryPrices()->orderBy('available_from DESC')->one();
        $suggestion = new TemporaryPrice([
            'scenario' => TemporaryPrice::SCENARIO_IN_PASS,
            'pass_id' => $this->id,
        ]);
        if ($lastPrice) {
            $nextFrom = $lastPrice->available_to ? date('Y-m-d', strtotime('+1 day', strtotime($lastPrice->available_to))) : '';
            $months = $lastPrice->getPeriodInMonths();
            $nextTo = $months ? date('Y-m-d', strtotime("+$months months", strtotime($lastPrice->available_to))) : '';
            $suggestion->setAttributes([
                'available_from' => $nextFrom,
                'available_to' => $nextTo,
            ]);
        }
        return $suggestion;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id'])->inverseOf('passes');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemporaryPrices()
    {
        return $this->hasMany(TemporaryPrice::className(), ['pass_id' => 'id'])
            ->inverseOf('pass');
    }
}
