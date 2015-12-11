<?php

namespace app\models\forms;

use app\models\Event;
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

    public function rules()
    {
        return [
            ['eventIds', 'default', 'value' => []],
            ['eventIds', 'filter', 'filter' => function ($value) {
                return explode('-', $value);
            }, 'when' => function (MapForm $model) {
                return is_string($model->eventIds);
            }], // Transform dash-separated string to array
            ['eventIds', 'each', 'rule' => ['exist', 'targetClass'=>Event::className(), 'targetAttribute'=>'id']],
        ];
    }
}