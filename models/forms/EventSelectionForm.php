<?php

namespace app\models\forms;

use app\models\Event;
use yii\base\Model;

/**
 * Represents the user selection of events in the map-based search page.
 * @package app\models\forms
 */
class EventSelectionForm extends Model
{
    /**
     * @var array The list of id of the events that the user selected in the map.
     */
    public $ids = [];

    public function rules()
    {
        return [
            ['ids', 'each', 'rule' => ['exist', 'targetClass'=>Event::className(), 'targetAttribute'=>'id']],
            ['ids', 'required'],
        ];
    }
}