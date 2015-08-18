<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property string $id
 * @property string $label
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
            [['label', 'url', 'event_id'], 'required'],
            [['event_id'], 'integer'],
            [['label', 'url'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Label'),
            'url' => Yii::t('app', 'Url'),
            'event_id' => Yii::t('app', 'Event ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }
}
