<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property string $id
 * @property string $title
 * @property string $url
 * @property string $event_id
 *
 * @property Event $event
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url', 'event_id'], 'required'],
            [['event_id'], 'exist', 'targetClass' => Event::className(), 'targetAttribute' => 'id'],
            [['title', 'url'], 'string', 'max' => 250],
            [['url'], 'unique',
                'targetAttribute' => ['url', 'event_id'],
                'comboNotUnique' => Yii::t('app', "This url already exists."),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'url' => Yii::t('app', 'Url'),
            'event_id' => Yii::t('app', 'Event ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id'])
            ->inverseOf("links");
    }

    /**
     * @return bool Whether this model has been filled with any meaningful data.
     */
    public function isEmpty()
    {
        return empty($this->title) && empty($this->url);
    }
}
