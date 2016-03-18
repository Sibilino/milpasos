<?php

namespace app\models;

use DateInterval;
use DateTime;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "temporary_price".
 *
 * @property int $id
 * @property int $price
 * @property string $available_from
 * @property string $available_to
 * @property int $pass_id
 * @property string $currency 3-letter currency code from parent Pass. Read-only.
 *
 * @property Pass $pass
 */
class TemporaryPrice extends ActiveRecord
{
    /**
     * @var string The scenario to be used to represent a price that is being edited through the Pass form.
     */
    const SCENARIO_IN_PASS = 'in_pass';
    
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
            [['price'], 'required'],
            [['available_from', 'available_to'], 'default', 'value' => null],
            [['available_from', 'available_to'], 'date', 'format' => 'yyyy-MM-dd'],
            // pass_id must not be assigned from $_POST when this TempPrice is edited from a Pass form
            [['pass_id'], 'exist', 'targetClass' => Pass::className(), 'targetAttribute' => 'id',
                'skipOnEmpty' => false, 'except' => static::SCENARIO_IN_PASS],
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
     * @return string 3-letter currency code from parent Pass.
     */
    public function getCurrency()
    {
        return $this->pass->currency;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPass()
    {
        return $this->hasOne(Pass::className(), ['id' => 'pass_id'])->inverseOf('temporaryPrices');
    }
}
