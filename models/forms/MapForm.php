<?php

namespace app\models\forms;

use app\models\Event;
use app\models\Dance;
use Yii;
use yii\base\Model;

/**
 * Represents the data in the user filters and other inputs in the Map page.
 * @package app\models\forms
 */
class MapForm extends Model
{
    /**
     * @var array|string Array or dash-separated list of ids of the events that the user selected in the map.
     */
    public $eventIds;
    /**
     * @var string The beginning of the period during which searched events must be active.
     */
    public $from_date;
    /**
     * @var string The end of the period during which searched events must be active.
     */
    public $to_date;
    /**
     * @var array The list of ids of the dances for which to find events.
     */
    public $danceIds;
    /**
     * @var number The maximum price to pay for a pass of searched events.
     */
    public $maxPrice;

    public function rules()
    {
        return [
            [['eventIds', 'danceIds'], 'default', 'value' => []],
            [['eventIds', 'danceIds'], 'filter', 'filter' => function ($value) {
                return explode('-', $value);
            }, 'when' => function (MapForm $model, $attribute) {
                return is_string($model->$attribute);
            }], // Transform dash-separated string to array
            ['eventIds', 'each', 'rule' => ['exist', 'targetClass'=>Event::className(), 'targetAttribute'=>'id']],
            ['danceIds', 'each', 'rule' => ['exist', 'targetClass'=>Dance::className(), 'targetAttribute'=>'id']],
            [['from_date', 'to_date'], 'date', 'format' => 'yyyy-MM-dd'],
            ['maxPrice', 'number', 'min' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'from_date' => Yii::t('app', 'From date'),
            'to_date' => Yii::t('app', 'To date'),
            'danceIds' => Yii::t('app', 'Dance Styles'),
            'maxPrice' => Yii::t('app', 'Maximum pass price'),
        ];
    }


}
