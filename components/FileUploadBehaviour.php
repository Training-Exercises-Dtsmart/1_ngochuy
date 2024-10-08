<?php 
/**
 * @Author: JustAbusiness huysanti123456@gmail.com
 * @Date: 2024-07-22 11:31:21
 * @LastEditors: JustAbusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-22 11:46:14
 * @FilePath: components/FileUploadBehaviour.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\components;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class FileUploadBehaviour extends Behavior
{
     /** @var string Name of attribute which holds the attachment */
     public $attribute = 'img';
     
     /** @var string Path template to use in storing files.5 */
     public $filePath = 'uploads/';

     /** @var \yii\web\UploadedFile */  
     public $file;

     /** @var $file_name string Feature name of file */
     public $file_name;
     
     public function events()
     {
          return [
               ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
               ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
               ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
               ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
               ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
          ];
     }
     
     public function beforeValidate()
     {
          if ($this->owner->{$this->attribute} instanceof  UploadedFile) {
               $this->file = $this->owner->{$this->attribute};
               return;
          }
          $this->file = UploadedFile::getInstance($this->owner, $this->attribute);
          if (empty($this->file)) {
               $this->file = UploadedFile::getInstanceByName($this->attribute);
          }
          
          if ($this->file instanceof UploadedFile) {
               $this->owner->{$this->attribute} = $this->file;
          }
          
     }
     
     public function beforeSave()
     {
          if ($this->file) {
               $this->file_name = time() . '.'. $this->file->extension;
               $this->owner->{$this->attribute} = $this->file_name;
          } else {
               $this->owner->{$this->attribute} = ArrayHelper::getValue($this->owner->oldAttributes, $this->attribute, null);
          }
     }
     
     public function afterSave()
     {
          if ($this->file)
          {
               $this->file->saveAs($this->filePath . $this->file->name);
          }
     }
}