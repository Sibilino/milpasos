<?php

namespace app\models;

use app\components\ManyToManyBehavior;
use Yii;
use app\components\ImageModelBehavior;

/**
 * This is the model class for table "group".
 *
 * @property string $id
 * @property string $name
 *
 * @property Event[] $events
 * @property Artist[] $artists
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @var array|string To collect input to update this model's artists. Can be array or string of comma-separated ids.
     */
    public $artistIds = [];
    /**
     * @var string
     */
    public $imageUrl;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                // Add functionality to save and load groups from the id array in $artistIds
                'class' => ManyToManyBehavior::className(),
                'relation' => 'artists',
                'idListAttr' => 'artistIds',
            ],
            [
                // Add functionality to save and upload images
                'class' => ImageModelBehavior::className(),
                'folder' => 'img/group',
                'imageAttr' => 'imageUrl',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 250],
            [['artistIds'], 'default', 'value' => []],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'imageUrl',
            'artistIds',
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
            'imageUrl' => Yii::t('app', 'Image'),
            'artistIds' => Yii::t('app', 'Artists'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['id' => 'event_id'])->viaTable('event_has_group', ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtists()
    {
        return $this->hasMany(Artist::className(), ['id' => 'artist_id'])->viaTable('group_has_artist', ['group_id' => 'id']);
    }
}
