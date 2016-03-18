<?php

namespace app\models;

use app\models\forms\MapForm;
use Yii;
use app\behaviors\ImageModelBehavior;
use app\behaviors\ManyToManyBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "event".
 *
 * @property string $id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property string $address
 * @property double $lon
 * @property double $lat
 * @property string $website
 * @property array $danceIds
 *
 * @property Artist[] $artists
 * @property Dance[] $dances
 * @property Group[] $groups
 * @property Link[] $links
 * @property Pass[] $passes
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @var array|string To collect input to update this Event's dances. Can be array or string of comma-separated ids.
     */
    public $danceIds = [];
    /**
     * @var array|string To collect input to update this Event's groups. Can be array or string of comma-separated ids.
     */
    public $groupIds = [];
    /**
     * @var string
     */
    public $imageUrl;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                // Add functionality to save and load dances from the id array in $danceIds
                'class' => ManyToManyBehavior::className(),
                'relation' => 'dances',
                'idListAttr' => 'danceIds',
            ],[
                // Add functionality to save and load dances from the id array in $groupIds
                'class' => ManyToManyBehavior::className(),
                'relation' => 'groups',
                'idListAttr' => 'groupIds',
            ],
            [
                'class' => ImageModelBehavior::className(),
                'folder' => 'img/event',
                'imageAttr' => 'imageUrl',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'start_date', 'end_date', 'lon', 'lat'], 'required'],
            [['start_date', 'end_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['lon'], 'number', 'min' => -180, 'max' => 180],
            [['lat'], 'number', 'min' => -90, 'max' => 90],
            [['name', 'website'], 'string', 'max' => 250],
            ['website', 'url', 'defaultScheme' => 'http'],
            [['address'], 'string', 'max' => 500],
            [['address'], 'required', 'message' => "Please select an address from the list of suggestions."],
            [['danceIds'], 'default', 'value' => []],
            [['groupIds'], 'default', 'value' => []],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'danceIds',
            'groupIds',
            'imageUrl',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'address' => Yii::t('app', 'Address'),
            'lon' => Yii::t('app', 'Lon'),
            'lat' => Yii::t('app', 'Lat'),
            'website' => Yii::t('app', 'Main Website'),
            'danceIds' => Yii::t('app', 'Dance Styles'),
            'groupIds' => Yii::t('app', 'Performers'),
            'imageUrl' => Yii::t('app', 'Image'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $loaded = parent::load($data, $formName);
        return ($this->loadImage($data) || $loaded);
    }

    /**
     * @inheritdoc
     * @return EventQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtists()
    {
        return $this->hasMany(Artist::className(), ['id' => 'artist_id'])->viaTable('event_has_artist', ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDances()
    {
        return $this->hasMany(Dance::className(), ['id' => 'dance_id'])->viaTable('event_has_dance', ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('event_has_group', ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Link::className(), ['event_id' => 'id'])
            ->inverseOf('event');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasses()
    {
        return $this->hasMany(Pass::className(), ['event_id' => 'id'])
            ->inverseOf('event');
    }

    /**
     * Returns the best available price for a pass for this Event.
     * @return TemporaryPrice|null
     */
    public function bestAvailablePrice()
    {
        return array_reduce($this->passes, function ($carry, Pass $pass) {
            $price = $pass->bestAvailablePrice();
            if ($carry !== null) {
                $carryEuros = Yii::$app->currencyConverter->toEur($carry->price, $carry->currency);
                if ($carryEuros < Yii::$app->currencyConverter->toEur($price->price, $price->currency)) {
                    return $carry;
                }
            }
            return $price;
        });
    }

}

class EventQuery extends ActiveQuery
{
    /**
     * Selects the Events that are still available.
     * @return $this
     */
    public function available()
    {
        return $this->andWhere(['>=','end_date', date('Y-m-d')]);
    }

    /**
     * Selects the Events that correspond to the filtering data passed in $form.
     * @param MapForm $form
     * @return $this
     */
    public function searchCharacteristics(MapForm $form)
    {
        return $this
            ->joinWith(['dances', 'groups'], false) // Do not eager load these potentially heavy relations
            ->joinWith(['passes', 'passes.temporaryPrices'])
            ->andFilterWhere(['<=','start_date', $form->to_date])
            ->andFilterWhere(['>=','end_date', $form->from_date])
            ->andFilterWhere(['dance.id' => $form->danceIds])
            ->andFilterWhere(['group.id' => $form->groupIds])
            ->andWhere(['pass.full' => 1])
        ;
    }

    /**
     * @param MapForm $form
     * @return array|\yii\db\ActiveRecord[]
     */
    public function allFromMapForm(MapForm $form)
    {
        $events = $this->searchCharacteristics($form)->all();
        if ($form->maxPrice) {
            $maxEuros = Yii::$app->currencyConverter->toEur($form->maxPrice, $form->currency);
            $events = array_filter($events, function (Event $e) use ($maxEuros) {
                $price = $e->bestAvailablePrice();
                return $price !== null && Yii::$app->currencyConverter->toEur($price->price, $price->currency) <= $maxEuros;
            });
        }
        return $events;
    }
}
