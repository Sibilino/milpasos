?php
namespace app\models\forms;
use app\models\Event;
use Yii;
use yii\base\Model;

/**
 * Holds the Ids of the Events selected by the user.
 * @package app\models\forms
 */
class EventListForm extends Model
{
    /**
     * @var array|string Array or dash-separated list of ids of the events that the user selected in the map.
     */
    public $eventIds;
    
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
}
