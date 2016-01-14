<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "temporary_price".
 *
 * @property int $id
 * @property int $price
 * @property string $available_from
 * @property string $available_to
 * @property int $pass_id
 *
 * @property Pass $pass
 */
class TemporaryPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temporary_price';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number', 'min' => 0],
            [['price', 'pass_id'], 'required'],
            [['available_from', 'available_to'], 'default', 'value' => null],
            [['available_from', 'available_to'], 'date', 'format' => 'yyyy-MM-dd'],
            [['pass_id'], 'exist', 'targetClass' => Pass::className(), 'targetAttribute' => 'id'],
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
            'available_from' => Yii::t('app', 'Available From'),
            'available_to' => Yii::t('app', 'Available To'),
            'pass_id' => Yii::t('app', 'Pass'),
        ];
    }
    
    /**
     * The number of months that this price is valid.
     * @return integer|null Number of months. Returns null if the validity period is unbound or undefined.
     */
    public function getPeriodInMonths()
    {
        if (!$this->available_from || !$this->available_to) {
            return null;
        }
        $from = new DateTime($this->available_from);
        $to = new DateTime($this->available_to);
        $to->add(new DateInterval('P1D')); // Next day
        return $to->diff($from)->m;
    }
    
    /**
     * Generates a new TemporaryPrice model whose attributes represent the "next" logical temporary price.
     * @return app\models\TemporaryPrice
     */
    public function getNext()
    {
        $nextFrom = $this->available_to ? date('Y-m-d', strtotime('+1 day', strtotime($this->available_to))) : '';
        $months = $this->getPeriodInMonths();
        $nextTo = $months ? date('Y-m-d', strtotime("+$months months", strtotime($this->available_to))) : '';
        return new TemporaryPrice([
            'pass_id' => $this->pass_id,
            'available_from' => $nextFrom,
            'available_to' => $nextTo,
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPass()
    {
        return $this->hasOne(Pass::className(), ['id' => 'pass_id'])->inverseOf('temporaryPrices');;
    }
}
