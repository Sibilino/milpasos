<?php

namespace app\models;

use Yii;
use app\components\ManyToManyBehavior;

/**
 * This is the model class for table "artist".
 *
 * @property string $id
 * @property string $name
 * @property string $surname
 * @property string $website
 *
 * @property Dance[] $dances
 * @property Event[] $events
 * @property Group[] $groups
 */
class Artist extends \yii\db\ActiveRecord
{
    /**
     * @var array|string To collect input to update this model's dances. Can be array or string of comma-separated ids.
     */
    public $danceIds = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artist';
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
            [['name'], 'required'],
            [['name', 'surname', 'website'], 'string', 'max' => 250],
            [['danceIds'], 'default', 'value' => []],
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
            'surname' => Yii::t('app', 'Surname'),
            'website' => Yii::t('app', 'Website'),
            'danceIds' => Yii::t('app', 'Dance Styles'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDances()
    {
        return $this->hasMany(Dance::className(), ['id' => 'dance_id'])->viaTable('artist_has_dance', ['artist_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['id' => 'event_id'])->viaTable('event_has_artist', ['artist_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('group_has_artist', ['artist_id' => 'id']);
    }
}
