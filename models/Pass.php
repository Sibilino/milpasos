<?php

namespace app\models;

use Yii;
use yii\db\Exception;

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
 *
 * @property Event $event
 * @property TemporaryPrice[] $temporaryPrices
 */
class Pass extends \yii\db\ActiveRecord
{
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
     * Returns a list of existing TempPrices for this Pass and adds a new TempPrice model "template" at the end.
     * @return TemporaryPrice[]
     */
    public function getPriceList()
    {
        $prices = $this->getTemporaryPrices()->orderBy('available_from')->all();
        array_walk($prices, function (TemporaryPrice $p) {
            $p->scenario = TemporaryPrice::SCENARIO_IN_PASS;
        });
        array_push($prices, $this->getNextPriceSuggestion());
        return $prices;
    }

    /**
     * Attach the given list of prices to this Pass, saving them to the DB after removing any existing prices.
     * @param TemporaryPrice[] $prices
     * @return bool Whether the saving operation was successfull (true) or rolled back (false).
     */
    public function updatePriceList(array $prices)
    {
        $pass_id = $this->id;
        array_walk($prices,function (TemporaryPrice $p) use ($pass_id) { // Cannot trust pass_id from user form
            $p->pass_id = $pass_id;
        });

        $transaction = Yii::$app->db->beginTransaction();
        TemporaryPrice::deleteAll(['pass_id'=>$this->id]);

        try {
            array_walk($prices,function (TemporaryPrice $p) use ($pass_id) {
                $p->pass_id = $pass_id;
                $p->isNewRecord = true;
                if (!$p->save()) {
                    throw new Exception("Problem saving prices.");
                };
            });
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
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
