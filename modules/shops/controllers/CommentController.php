<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:13:35
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-29 15:48:07
 * @FilePath: modules/shops/controllers/CommentController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\shops\controllers;

use Yii;
use app\controllers\Controller;
use app\modules\core\Pagination;
use app\modules\enums\HttpStatus;
use app\modules\shops\forms\CommentForm;
use app\modules\shops\models\Comment;


class CommentController extends Controller
{
     /**
      * @inheritDoc
      */
//     public function behaviors()
//     {
//          return [
//               'access' => AccessControl::class,
//               'only' => ['create', 'update', 'delete'],
//               'rules' => [
//                    [
//                         'allow' => true,
//                         'roles' => ['@']
//                    ]
//               ],
//          ];
//     }

     /**
      * Lists all Comment models.
      *
      * @return array
      */
     public function actionIndex()
     {
          try {
               $comments = Comment::find();
               if (!$comments) {
                    return $this->json(false, [], 'Comment not found', HttpStatus::NOT_FOUND);
               }

               $dataProvider = Pagination::getPagination($comments, 10, SORT_DESC);
               return $this->json(true, ['comment' => $dataProvider], 'Success', HttpStatus::OK);
          } catch (\Exception $e) {
               return $this->json(false, [], 'Can not find comment', HttpStatus::BAD_REQUEST);
          }
     }


     public function actionCreate()
     {
          $comment = new CommentForm();
          $comment->load(Yii::$app->request->post(), '');

          if (!$comment->validate() || !$comment->save()) {
               return $this->json(false, ["errors" => $comment->getErrors()], "Can't create comment", HttpStatus::BAD_REQUEST);
          }

          return $this->json(true, ["comment" => $comment], "Create comment successfully", HttpStatus::OK);
     }

     public function actionReply()
     {
          $parentId = Yii::$app->request->post('parent_id');
          $parentComment = $this->findModel($parentId);

          if (!$parentComment) {
               return $this->json(false, [], 'Parent comment not found', HttpStatus::NOT_FOUND);
          }

          $comment = new CommentForm();
          $comment->load(Yii::$app->request->post(), '');
          $comment->parent_id = $parentId;

          if (!$comment->validate() || !$comment->save()) {
               return $this->json(false, ['errors' => $comment->getErrors()], "Can't reply to comment", HttpStatus::BAD_REQUEST);
          }

          return $this->json(true, ['comment' => $comment], 'Reply to comment successfully', HttpStatus::OK);
     }

     public function actionUpdate($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $comment = CommentForm::findOne($id);
               if ($comment === null) {
                    return $this->json(false, [], 'Comment not found', HttpStatus::NOT_FOUND);
               }

               $comment->load(Yii::$app->request->post(), '');
               if ($comment->validate() || $comment->save()) {
                    return $this->json(false, ['errors' => $comment->getErrors()], "Can't update comment", HttpStatus::BAD_REQUEST);
               }

               return $this->jsons(true, ['comment' => $comment], 'Update comment successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               return $this->json(false, [], 'Can not update comment', HttpStatus::BAD_REQUEST);
          }
     }


     public function actionDelete($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $comment = Comment::findOne($id);
               if ($comment == null) {
                    return $this->json(false, [], 'Comment not found', HttpStatus::NOT_FOUND);
               }

               if (!$comment->delete()) {
                    $transaction->rollBack();
                    return $this->json(false, ['errors' => $comment->getErrors()], "Can't delete comment", HttpStatus::BAD_REQUEST);
               }

               $transaction->commit();
               return $this->json(true, [], 'Delete comment successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               return $this->json(false, [], 'Can not delete comment', HttpStatus::BAD_REQUEST);
          }
     }


     protected function findModel($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               if (($model = Comment::findOne($id)) !== null) {
                    return $model;
               }
          } catch (\Exception $e) {
               $transaction->rollBack();
               return $this->json(false, [], 'Can not find comment', HttpStatus::BAD_REQUEST);
          }
     }

     protected function findParentId($parentId)
     {
          if (($model = Comment::findOne(['parent_id' => $parentId])) !== null) {
               return $model->parent_id;
          }

          return $this->json(false, [], 'Model not found', HttpStatus::NOT_FOUND);
     }
}