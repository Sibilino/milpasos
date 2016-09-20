<?php

namespace app\models;

use app\behaviors\ImageModelBehavior;
use Yii;
use app\behaviors\ManyToManyBehavior;

/**
 * This is the model class for table "artist".
 *
 * @property string $id
 * @property string $name
 * @property string $real_name
 * @property string $real_surname
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
     * @var array|string To collect input to update this model's groups. Can be array or string of comma-separated ids.
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
            [
                // Add functionality to save and load groups from the id array in $groupIds
                'class' => ManyToManyBehavior::className(),
                'relation' => 'groups',
                'idListAttr' => 'groupIds',
            ],
            [
                // Add functionality to save and upload images
                'class' => ImageModelBehavior::className(),
                'folder' => 'img/artist',
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
            [['name', 'real_name', 'real_surname', 'website'], 'string', 'max' => 250],
            ['website', 'url', 'defaultScheme' => 'http'],
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
            'imageUrl'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Artistic Name'),
            'real_name' => Yii::t('app', 'Real Name'),
            'real_surname' => Yii::t('app', 'Real Surname'),
            'website' => Yii::t('app', 'Website'),
            'danceIds' => Yii::t('app', 'Dance Styles'),
            'groupIds' => Yii::t('app', 'Groups'),
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
