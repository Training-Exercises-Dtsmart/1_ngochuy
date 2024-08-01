<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 09:36:26
 * @FilePath: repositories/CommentRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use Yii;
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
          return $comment->save(false); // // Bypass validation here, as it's already done.
     }

     /**
      * Deletes a brand.
      * @param Comment $comment
      * @return bool
      */
     public function delete(Comment $comment): bool
     {
          return $comment->delete() != false;
     }

     /**
      * Creates a reply to a comment.
      * @param Comment $comment
      * @param string $replyContent
      * @return bool
      */
     public function reply(Comment $comment, string $replyContent): bool
     {
        $reply = new Comment();
        $reply->content = $replyContent;
        $reply->parent_id = $comment->id;
        $reply->user_id = Yii::$app->user->id;
        
        return $this->save($reply);
     }
}