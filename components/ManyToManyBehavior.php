<?php

namespace app\components;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\helpers\ArrayHelper;
use yii\validators\EachValidator;

/**
 * Behavior to be attached to an ActiveRecord model that manages a M:N relation with an "id" primary key.
 * The ids of the related records are automatically loaded and saved from an array attribute in the model.
 * In addition, a new rule will be added to the model so that each id in the array must exist in the DB.
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
     * @var string The attribute of the model that with the array of ids to save or loaded from the DB.
     */
    public $idListAttr;

    /**
     * Attaches handles to make the owning ActiveRecord automatically perform the following actions:
     * <ul>
     * <li>Adding a validation rule that checks existance of each id in $owner->$idListAttr.</li>
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
        $relationClass = $this->owner->getRelation($this->relation)->modelClass;
        // TODO: Replace each + exist validators for one findAll()
        $validator = new EachValidator([
            'rule' => ['exist', 'targetClass' => $relationClass, 'targetAttribute'=>'id'],
        ]);
        $validator->validateAttribute($this->owner, $this->idListAttr);
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
        
        $ids = $model->{$this->idListAttr};
        $relationClass = $this->owner->getRelation($this->relation)->modelClass;
        $relatedModels = call_user_func([$relationClass, 'findAll'], ['id' => $ids]);
        foreach ($relatedModels as $record) {
            $model->link($this->relation, $record);
        }
    }
}
