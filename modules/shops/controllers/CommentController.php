<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:13:35
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-24 16:11:19
 * @FilePath: modules/shops/controllers/CommentController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\shops\controllers;

use app\controllers\Controller;
use app\modules\core\Pagination;
use app\modules\enums\HttpStatus;
use app\modules\shops\form\CommentForm;
use app\modules\shops\models\Comment;
use Yii;


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
          $comments = Comment::find();
          if (!$comments) {
               return $this->json(false, [], 'Order not found', HttpStatus::NOT_FOUND);
          }

          $dataProvider = Pagination::getPagination($comments, 10, SORT_DESC);

          return $this->json(true, ['comment' => $dataProvider], 'Success', HttpStatus::OK);
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
          $comment = CommentForm::findOne($id);
          if($comment == null)
          {
               return $this->json(false, [], "Order not found", HttpStatus::NOT_FOUND);
          }

          $comment->load(Yii::$app->request->post(), '');
          if (!$comment->validate() || !$comment->save()) {
               return $this->json(false, ['errors' => $comment->getErrors()], "Can't update comment", HttpStatus::BAD_REQUEST);
          }

          return $this->json(true, ['order' => $comment], 'Update comment successfully', HttpStatus::OK);
     }


     public function actionDelete($id)
     {
          $comment  = Comment::find()->where(['id' => $id])->one();
          if ($comment == null) {
               return $this->json(false, [], 'Comment not found', HttpStatus::NOT_FOUND);
          }
          
          $comment->load(Yii::$app->request->post(), '');
          if (!$comment->delete()) {
               return $this->json(false, ['errors' => $comment->getErrors()], "Can't delete order", HttpStatus::BAD_REQUEST);
          }
          return $this->json(true, 'Success', HttpStatus::OK);
     }


     protected function findModel($id)
     {
          if (($model = Comment::findOne(['id' => $id])) !== null) {
               return $model;
          }

          return $this->json(false, [], 'Model not found', HttpStatus::NOT_FOUND);
     }
     
     protected function findParentId($parentId)
     {
          if (($model = Comment::findOne(['parent_id' => $parentId]))!== null) {
               return $model->parent_id;
          }
     
          return $this->json(false, [], 'Model not found', HttpStatus::NOT_FOUND);
     }
}