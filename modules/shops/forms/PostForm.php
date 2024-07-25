<?php

namespace app\modules\shops\form;

use app\models\Post;
use Yii;
use yii\web\UploadedFile;

class PostForm extends Post
{
     public function rules()
     {
          return array_merge(
               parent::rules(),
               [
                    [['title', 'content', 'short_description', 'slug', 'image'], 'required'],
                    [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
               ]
          );
     }

     public function uploadFile($postData)
     {
          // Get the uploaded file instance
          $avatarFile = UploadedFile::getInstanceByName('image');

          if ($avatarFile) {
               $uploadPath = Yii::getAlias('@app/web/assets/upload/');
               $filename = uniqid() . '.' . $avatarFile->extension;
               $filePath = $uploadPath . $filename;

               if ($avatarFile->saveAs($filePath)) {
                    $this->image = $filename;
                    return $filename;
               } else {
                    return false;
               }
          }
          return true;
     }

     public function uploadMultipleImage($postData)
     {
          // Get the uploaded file instances
          $postFiles = UploadedFile::getInstancesByName('image');
          $uploadedFiles = [];

          if ($postFiles) {
               $uploadPath = Yii::getAlias('@app/web/assets/upload/');

               foreach ($postFiles as $file) {
                    $filename = uniqid() . '.' . $file->extension;
                    $filePath = $uploadPath . $filename;

                    if ($file->saveAs($filePath)) {
                         $uploadedFiles[] = $filename;
                    } else {
                         return false;
                    }
               }

               // If all files are uploaded successfully, store their names (as a comma-separated string)
               $this->image = implode(',', $uploadedFiles);
               return true;
          }
          return false;
     }
}