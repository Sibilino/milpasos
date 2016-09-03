<?php
namespace app\models\forms;

use app\models\Event;
use Yii;
use yii\base\Model;

/**
 * Holds the Ids of the Events selected by the user.
 * @property Event[] $events Returns the Events selected in this EventListForm.
 * @package app\models\forms
 */
class EventListForm extends Model
{
    /**
     * @var array|string Array or dash-separated list of ids of the events that the user selected in the map.
     */
    public $eventIds;

    /**
     * @var Event[]|null Cache of event objects to prevent multiple db aceesses per request.
     **/
    private $_events;
    
    public function rules()
    {
        return [
            [['eventIds'], 'default', 'value' => []],
            [['eventIds'], 'filter', 'filter' => function ($value) {
                return explode('-', $value);
            }, 'when' => function (EventListForm $model, $attribute) {
                return is_string($model->$attribute);
            }], // Transform dash-separated string to array
            ['eventIds', 'each', 'rule' => ['exist', 'targetClass'=>Event::className(), 'targetAttribute'=>'id']],
        ];
    }

    /**
     * Returns the Events found using the filtering conditions in this MapForm, and caches the results.
     * @return Event[]
     **/
    public function getEvents()
    {
        if (!isset($this->_events)) {
            $this->_events = Event::find()->where(['id'=>$this->eventIds])->all();
        }
        return $this->_events;
    }
}
