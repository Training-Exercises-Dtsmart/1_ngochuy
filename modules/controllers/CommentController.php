<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:13:35
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 11:51:17
 * @FilePath: modules/controllers/CommentController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\controllers;

use Yii;
use app\modules\models\Comment;
use app\modules\models\form\CommentForm;
use app\modules\models\HttpStatus;
use app\modules\models\pagination\Pagination;
use app\controllers\Controller;


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
}