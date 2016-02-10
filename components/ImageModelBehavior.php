<?php

namespace app\components;


use Exception;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\imagine\Image;
use yii\validators\ImageValidator;
use yii\web\UploadedFile;

/**
 * Class ImageModelBehavior
 * @property \yii\db\ActiveRecord $owner
 */
class ImageModelBehavior extends Behavior
{
    public $imageAttr = 'image';
    public $idAttr = 'id';
    public $folder;

    /**
     * @var UploadedFile
     */
    private $_image;

    public function events() {
        return [
            Model::EVENT_AFTER_VALIDATE => 'validateImage',
            ActiveRecord::EVENT_AFTER_FIND => 'populateImageAttr',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveImage',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveImage',
        ];
    }

    public function getImagePath()
    {
        return Yii::getAlias('@webroot')."/$this->folder/thumb-".$this->owner->{$this->idAttr}.".png";
    }

    public function getImageUrl()
    {
        $url = Yii::getAlias('@web')."/$this->folder/thumb-".$this->owner->{$this->idAttr}.".png";
        // Add timestamp to bust browser cache when image is modified
        $timestamp = @filemtime($this->getImagePath());
        if ($timestamp) {
            $url .= "?v=$timestamp";
        }
        return $url;
    }

    public function loadImage($data, $formName = null) {
        $this->_image = UploadedFile::getInstance($this->owner, $this->imageAttr);
        return ($this->_image !== null);
    }

    public function saveImage(AfterSaveEvent $event) {
        if ($this->_image && !$this->owner->hasErrors()) {
            try {
                $originalFile = Yii::getAlias('@webroot')."/$this->folder/".$this->owner->id.'.'.$this->_image->extension;
                $this->_image->saveAs($originalFile);
                Image::thumbnail($originalFile, 150, 150)->save($this->getImagePath());
            } catch (Exception $e) {
                $this->owner->addError($this->imageAttr, "Could not save image: ".$e->getMessage().".");
                return false;
            }
        }
        return true;
    }

    public function populateImageAttr(Event $event) {
        if (file_exists($this->getImagePath())) {
            $this->owner->{$this->imageAttr} = $this->getImageUrl();
        }
    }

    public function validateImage() {
        if ($this->_image) {
            $validator = new ImageValidator([
                'extensions' => 'png, jpg',
                'minWidth' => 100, 'maxWidth' => 500,
                'minHeight' => 100, 'maxHeight' => 500,
            ]);
            $error = '';
            if (!$validator->validate($this->_image, $error)) {
                $this->owner->addError($this->imageAttr, $error);
            }
        }
    }
}
