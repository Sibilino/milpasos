<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "pass".
 *
 * @property string $id
 * @property string $price
 * @property string $currency
 * @property string $description
 * @property boolean $full
 * @property string $event_id
 *
 * @property Event $event
 * @property TemporaryPrice[] $temporaryPrices
 */
class Pass extends ActiveRecord
{
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
        $currencies = Yii::$app->params['currencies'];
        return [
            [['full', 'event_id', 'price', 'currency'], 'required'],
            [['price'], 'number', 'min' => 0],
            [['full'], 'boolean'],
            [['currency'], 'in', 'range' => array_keys($currencies), 'strict' => true],
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
            'full' => Yii::t('app', "It's a Full Pass"),
            'event_id' => Yii::t('app', 'Event'),
        ];
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
