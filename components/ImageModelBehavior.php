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
 * This behavior adds functionality to an ActiveRecord so that one attribute can be used to store images.
 * <ol>
 * <li>Configure this Behavior's $folder attribute with the relative path (from @webroot) where to store images.</li>
 * <li>Declare an attribute in the active record and configure it in this Behavior's $imageAttr.</li>
 * <li>Override the record's load() function to call $this->loadImage($data).</li>
 * The ActiveRecord's $imageAttr attribute is automatically updated with the URL of the corresponding image, both when
 * loading the record or when saving it.
 * </ol>
 *
 * @property \yii\db\ActiveRecord $owner
 */
class ImageModelBehavior extends Behavior
{
    /**
     * @var string The atribute of the owner ActiveRecord that will be updated with the URL of the stored image.
     */
    public $imageAttr = 'image';
    /**
     * @var string The attribute of the owner ActiveRecord that will be used to assign a unique id to the stored image.
     */
    public $idAttr = 'id';
    /**
     * @var string The relative path, from @webroot, to be used to save images. For example: 'img/artist'.
     */
    public $folder;
    /**
     * @var int The height to which the image will be converted upon saving.
     */
    public $height = 150;
    /**
     * @var int The width to which the image will be converted upon saving.
     */
    public $width = 150;

    /**
     * @var array The configuration array to be used to validate an uploaded image.
     */
    public $imageRules = [
        'extensions' => 'png, jpg',
        'minWidth' => 100, 'maxWidth' => 1000,
        'minHeight' => 100, 'maxHeight' => 1000,
    ];

    /**
     * @var UploadedFile Internal variable where image uploads are stored before being saved.
     */
    private $_image;

    /**
     * Attaches handlers to the following events:
     * <ul>
     * <li>After validating the ActiveRecord owner, validates the uploaded image.</li>
     * <li>After loading the ActiveRecord owner, populates the image URL in the owner's attribute.</li>
     * <li>After saving the ActiveRecord owner, saves any uploaded image to disk.</li>
     * </ul>
     * @return array
     */
    public function events() {
        return [
            Model::EVENT_AFTER_VALIDATE => 'validateImage',
            ActiveRecord::EVENT_AFTER_FIND => 'populateImageAttr',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveImage',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveImage',
        ];
    }

    /**
     * @return string The full path and filename that should be assigned to an image corresponding to the owner.
     * Does not check whether the file actually exists.
     */
    private function getImagePath()
    {
        return Yii::getAlias('@webroot')."/$this->folder/thumb-".$this->owner->{$this->idAttr}.".png";
    }

    /**
     * @return string The url to the image associated to the owner. Includes cache busting variable. Does not check
     * whether the image actually exists in disk.
     */
    private function getImageUrl()
    {
        $url = Yii::getAlias('@web')."/$this->folder/thumb-".$this->owner->{$this->idAttr}.".png";
        // Add timestamp to bust browser cache when image is modified
        $timestamp = @filemtime($this->getImagePath());
        if ($timestamp) {
            $url .= "?v=$timestamp";
        }
        return $url;
    }

    /**
     * Loads the uploaded image so that it can later be saved when calling save() on the owner.
     * @return bool Whether an image was actually loaded.
     */
    public function loadImage() {
        $this->_image = UploadedFile::getInstance($this->owner, $this->imageAttr);
        return ($this->_image !== null);
    }

    /**
     * Saves the image loaded by loadImage() as a thumbnail with dimensions $width x $height.
     * @param AfterSaveEvent $event
     * @return bool Whether the save operation was successful.
     */
    public function saveImage(AfterSaveEvent $event) {
        if ($this->_image && !$this->owner->hasErrors()) {
            try {
                $originalFile = Yii::getAlias('@webroot')."/$this->folder/".$this->owner->id.'.'.$this->_image->extension;
                $this->_image->saveAs($originalFile);
                Image::thumbnail($originalFile, $this->width, $this->height)->save($this->getImagePath());
            } catch (Exception $e) {
                $this->owner->addError($this->imageAttr, "Could not save image: ".$e->getMessage().".");
                return false;
            }
        }
        return true;
    }

    /**
     * Sets the owner's imageAttr to the URL of its image, if it exists on disk.
     * @param Event $event
     */
    public function populateImageAttr(Event $event) {
        if (file_exists($this->getImagePath())) {
            $this->owner->{$this->imageAttr} = $this->getImageUrl();
        }
    }

    /**
     * Performs a validation on the image loaded by loadImage. If it fails, adds the corresponding error to the owner's
     * imageAttr.
     */
    public function validateImage() {
        if ($this->_image) {
            $validator = new ImageValidator($this->imageRules);
            $error = '';
            if (!$validator->validate($this->_image, $error)) {
                $this->owner->addError($this->imageAttr, $error);
            }
        }
    }
}
