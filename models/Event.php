<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\components\ManyToManyBehavior;

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
            [['start_date', 'end_date'], 'safe'],
            [['lon'], 'number', 'min' => -180, 'max' => 180],
            [['lat'], 'number', 'min' => -90, 'max' => 90],
            [['name'], 'string', 'max' => 250],
            [['address'], 'string', 'max' => 500],
            [['address'], 'required', 'message' => "Please select an address from the list of suggestions."],
            [['danceIds'], 'default', 'value' => []],
            [['danceIds'], 'filter', 'filter' => function ($value) {
                return explode(',', $value);
            }, 'when' => function ($model) {
                return is_string($model->danceIds);
            }], // Transform comma-separated string to array
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'danceIds',
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
            'danceIds' => Yii::t('app', 'Dance Styles'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtists()
    {
        return $this->hasMany(Artist::className(), ['id' => 'artist_id'])->viaTable('event_has_artist', ['event_id' => 'id'])
            ->inverseOf('events');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDances()
    {
        return $this->hasMany(Dance::className(), ['id' => 'dance_id'])->viaTable('event_has_dance', ['event_id' => 'id'])
            ->inverseOf('events');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('event_has_group', ['event_id' => 'id'])
            ->inverseOf('events');
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

    public function getNewLink()
    {
        if (!isset($this->_newLink))
            $this->_newLink = new Link(['event_id' => $this->id]);
        return $this->_newLink;
    }

    public function setNewLink(Link $value)
    {
        $this->_newLink = $value;
    }

}
