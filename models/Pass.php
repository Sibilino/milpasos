<?php

namespace app\models;

use Yii;

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
            ['full', 'event_id', 'price', 'currency'], 'required'],
            [['price'], 'number', 'min' => 0],
            [['full'], 'boolean'],
            [['available_from', 'available_to', 'price'], 'default', 'value' => null],
            [['available_from', 'available_to'], 'date', 'format' => 'yyyy-MM-dd'],
            [['currency'], 'in', 'range' => array_keys(static::$currencies), 'strict' => true],
            [['event_id'], 'exist', 'targetClass' => Event::className(), 'targetAttribute' => 'id'],
            [['description'], 'string', 'max' => 1000]
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
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id'])->inverseOf('passes');;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemporaryPrices()
    {
        return $this->hasMany(TemporaryPrice::className(), ['pass_id' => 'id'])->inverseOf('pass');
    }
}
