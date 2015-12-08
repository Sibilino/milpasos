<?php

namespace app\components;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\helpers\ArrayHelper;

/**
 * Behavior to be attached to an ActiveRecord model that manages a M:N relation with an "id" primary key.
 * The ids of the related records are automatically loaded and saved from an array attribute in the model.
 * In addition, the ids in the array will be validated to ensure their records exist in the DB.
 * @property ActiveRecord $owner
 * @package app\components
 */
class ManyToManyBehavior extends Behavior
{
    /**
     * @var string The name of the relation to manage (must be lowercase).
     */
    public $relation;
    /**
     * @var string The attribute of the model with the array of ids to save or to be loaded from the DB.
     */
    public $idListAttr;

    /**
     * Attaches handles to make the owning ActiveRecord automatically perform the following actions:
     * <ul>
     * <li>Validating the existance in the DB of each id in $owner->$idListAttr.</li>
     * <li>After any find(), loading the related record's ids into $owner->$idListAttr.</li>
     * <li>After save(), saving the related records defined by the ids in $owner->$idListAttr.</li>
     * </ul>
     * @return array
     */
    public function events() {
        return [
            Model::EVENT_AFTER_VALIDATE => 'validateIdList',
            ActiveRecord::EVENT_AFTER_FIND => 'loadIdList',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveRelation',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveRelation',
        ];
    }

    /**
     * Validates that the ids in the owner's id array correspond to existing records in the target table.
     * @param Event $event
     */
    public function validateIdList(Event $event) {
        $model = $this->owner;
        $existingIds = ArrayHelper::getColumn($this->getModelsFromIds(), 'id');
        $attribute = $this->idListAttr;
        
        $notFound = array_diff($model->$attribute, $existingIds);
        if ($notFound) {
            $model->addError($attribute, Yii::t('app', 'Some values in {attribute} are invalid.', [
                '{attribute}' => $model->getAttributeLabel($attribute),
            ]));
        }
    }

    /**
     * Loads the ids of the related records into the id array attribute.
     * @param Event $event
     */
    public function loadIdList(Event $event) {
        $model = $this->owner;
        $model->{$this->idListAttr} = ArrayHelper::getColumn($model->{$this->relation}, 'id');
    }

    /**
     * Saves the records defined in the id array attribute, clearing all previous assignments.
     * @param AfterSaveEvent $event
     */
    public function saveRelation(AfterSaveEvent $event) {
        $model = $this->owner;
        $model->unlinkAll($this->relation, true); // Delete all existing links
        foreach ($this->getModelsFromIds() as $record) {
            $model->link($this->relation, $record);
        }
    }
    
    /**
     * Returns the records existing in the DB with the ids in the owner's id array.
     * @return ActiveRecord[]
     */
    private function getModelsFromIds() {
        $ids = $this->owner->{$this->idListAttr};
        $relationClass = $this->owner->getRelation($this->relation)->modelClass;
        // Return RelationClass::findAll($ids);
        return call_user_func([$relationClass, 'findAll'], ['id' => $ids]);
    }
}
