<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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
            [['danceIds'], 'filter', 'filter' => function ($value) {
                return explode(',', $value);
            }, 'when' => function ($model) {
                return is_string($model->danceIds);
            }], // Transform comma-separated string to array
            [['danceIds'], 'each', 'rule' => ['exist', 'targetClass'=>Dance::className(), 'targetAttribute'=>'id']],
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
     * Load $danceIds form this Event's associated dances.
     */
    public function afterFind()
    {
        $this->danceIds = ArrayHelper::getColumn($this->dances, 'id');
        parent::afterFind();
    }

    /**
     * Updates this event's associated dances so that the new selection is the one in $this->danceIds.
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->unlinkAll('dances', true);
        foreach ($this->danceIds as $id) {
            $this->link('dances', Dance::findOne($id));
        }
        parent::afterSave($insert, $changedAttributes);
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
