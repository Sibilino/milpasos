<?php

namespace app\models\forms;

use app\models\Event;
use app\models\Dance;
use app\models\Group;
use Yii;
use yii\base\Model;

/**
 * Represents the data in the user filters and other inputs that control the events shown in the main Map.
 * @property Event[] $events Returns the Events found using the filtering conditions in this MapForm.
 * @package app\models\forms
 */
class MapForm extends Model
{
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
     * @var array The list of ids of the groups for which to find events.
     */
    public $groupIds;
    /**
     * @var number The maximum price to pay for a pass of searched events.
     */
    public $maxPrice;
    /**
     * @var string The code of the currency in which $maxPrice is specified.
     */
    public $currency;
    /**
     * @var string The address specified in the input for lon/lat search.
     */
    public $address;
    /**
     * @var float The longitude of the position at which to center the event search.
     */
    public $lon;
    /**
     * @var float The latitude of the position at which to center the event search.
     */
    public $lat;
    
    /**
     * @var Event[]|null Cache of event objects to prevent multiple db aceesses per request.
     **/
    private $_events;

    public static function createDefault()
    {
        return new MapForm([
            'from_date' => date('Y-m-d'),
            'danceIds' => [],
            'groupIds' => [],
        ]);
    }

    public function rules()
    {
        return [
            [['danceIds', 'groupIds'], 'default', 'value' => []],
            [['danceIds', 'groupIds'], 'filter', 'filter' => function ($value) {
                return explode('-', $value);
            }, 'when' => function (MapForm $model, $attribute) {
                return is_string($model->$attribute);
            }], // Transform dash-separated string to array
            ['danceIds', 'each', 'rule' => ['exist', 'targetClass'=>Dance::className(), 'targetAttribute'=>'id']],
            ['groupIds', 'each', 'rule' => ['exist', 'targetClass'=>Group::className(), 'targetAttribute'=>'id']],
            [['from_date', 'to_date'], 'date', 'format' => 'yyyy-MM-dd'],
            ['maxPrice', 'number', 'min' => 0],
            [['address'], 'string', 'max' => 500],
            [['lon'], 'number', 'min' => -180, 'max' => 180],
            [['lat'], 'number', 'min' => -90, 'max' => 90],
            // Address must be blank if lon lat are also blank
            [['address'], 'compare', 'compareValue' => '', 'enableClientValidation' => false,
                'when' => function (MapForm $model) {
                    return empty($model->lon) && empty($model->lat);
                }, 'message' => Yii::t('app', "Please select an address from the list of suggestions."),
            ],
            ['currency', 'in', 'range' => array_keys(Yii::$app->currencyConverter->currencyLabels), 'strict' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'from_date' => Yii::t('app', 'Show events from'),
            'to_date' => Yii::t('app', 'Show events until'),
            'danceIds' => Yii::t('app', 'Dance Styles'),
            'groupIds' => Yii::t('app', 'Performers'),
            'maxPrice' => Yii::t('app', 'Max price'),
            'currency' => Yii::t('app', 'Currency'),
            'address' => Yii::t('app', 'Address'),
            'lon' => Yii::t('app', 'Lon'),
            'lat' => Yii::t('app', 'Lat'),
        ];
    }

    /**
     * Whether any attributes have been changed from their default value. The currency attribute is ignored.
     * @return boolean
     */
    public function isDirty()
    {
        $default = static::createDefault();
        foreach ($this->attributes as $name => $value) {
            if ($name == 'currency') {
                continue;
            }
            if ($name != "danceIds" && $default[$name] != $value) {
                return true;
            }
            if ($name == "danceIds" && !empty($value) && count($value) != Dance::find()->count()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the Events found using the filtering conditions in this MapForm, and caches the results.
     * @return Event[]
     **/
    public function getEvents()
    {
        if (!isset($this->_events)) {
            $this->_events = Event::find()->orderBy('start_date')->allFromMapForm($this);
        }
        return $this->_events;
    }
}
