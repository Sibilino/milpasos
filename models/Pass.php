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
 * @property string $event_id
 *
 * @property Event $event
 */
class Pass extends \yii\db\ActiveRecord
{
    /**
     * @var array The possible currencies for price, as "3-letterCode" => "displaySymbol".
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
            [['price', 'event_id'], 'required'],
            [['price'], 'number'],
            [['available_from', 'available_to'], 'default', 'value' => null],
            [['available_from', 'available_to'], 'date', 'format' => 'yyyy-MM-dd'],
            [['currency'], 'in', 'range' => array_keys(static::$currencies), 'strict' => true],
            [['event_id'], 'integer'],
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
            'price' => Yii::t('app', 'Price'),
            'currency' => Yii::t('app', 'Currency'),
            'description' => Yii::t('app', 'Description'),
            'available_from' => Yii::t('app', 'Available From'),
            'available_to' => Yii::t('app', 'Available To'),
            'event_id' => Yii::t('app', 'Event ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }
}
