<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 16:09:52
 * @FilePath: repositories/CommentRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use app\modules\shops\models\Comment;
use yii\db\ActiveRecord;
class CommentRepository
{
     /**
      * Retrieves all brands.
      * @return array|ActiveRecord[]
      */
     public function findAll(): array
     {
          return Comment::find()->all();
     }

     /**
      * Finds a brand by its ID.
      * @param int $id
      * @return Comment|null
      */
     public function findOne(int $id): ?Comment
     {
          return Comment::findOne($id);
     }

     /**
      * Saves a brand.
      * @param Comment $comment
      * @return bool
      */
     public function save(Comment $comment): bool
     {
          return $comment->save();
     }

     /**
      * Deletes a brand.
      * @param Comment $comment
      * @return bool
      */
     public function delete(Comment $comment): bool
     {
          return $comment->delete();
     }
}