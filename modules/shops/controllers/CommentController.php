<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:13:35
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 10:19:56
 * @FilePath: modules/shops/controllers/CommentController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\shops\controllers;

use Yii;
use app\components\CustomSerializer;
use app\controllers\Controller;
use app\modules\enums\HttpStatus;
use app\modules\shops\forms\CommentForm;
use app\modules\shops\models\Comment;
use app\services\CommentService;
use app\repositories\CommentRepository;
use app\components\RateLimitBehavior;


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

     public $modelClass = 'app\models\Comment';

     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];

     private $commentService;

     private $commentRepository;

     public function __construct($id, $module, CommentService $commentService, CommentRepository $commentRepository, $config = [])
     {
          $this->commentService = $commentService;
          $this->commentRepository = $commentRepository;
          parent::__construct($id, $module, $config);
     }

     public function behaviors()
     {
          $behaviors = parent::behaviors();

          if (in_array($this->action->id, ['index', 'create', 'update', 'delete'])) {
               $behaviors['rateLimiter'] = [
                    'class' => RateLimitBehavior::class,
                    'enableRateLimitHeaders' => true,
               ];
          }
          return $behaviors;
     }


     /**
      * Lists all Comment models.
      *
      * @return array
      */
     public function actionIndex()
     {
          try {
               $comments = $this->commentService->getAllComments(Yii::$app->request->queryParams);
               return $this->json(true, ["comments" => $comments], "Success", HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionIndex: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }


     public function actionCreate()
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $commentForm = new CommentForm();
               $commentForm->load(Yii::$app->request->post(), '');
               
               if (!$commentForm->validate() || !$commentForm->save()) {
                    return $this->json(false, ['errors' => $commentForm->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
               }
               $this->commentService->clearCommentCache();
               $transaction->commit();
               return $this->json(true, ["comment" => $commentForm], "Create comment successfully", HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionReply($commentId)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $comment = $this->commentRepository->findOne($commentId);
               if (!$comment) {
                    return $this->json(false, [], 'Comment not found', HttpStatus::NOT_FOUND);
               }

               $replyContent = Yii::$app->request->post('replyContent');
               if (!$this->commentRepository->reply($comment, $replyContent)) {
                    return $this->json(false, [], 'Can not reply comment', HttpStatus::BAD_REQUEST);
               }

               $transaction->commit();
               return $this->json(true, [], 'Reply comment successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionReply: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionUpdate($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $commentForm = CommentForm::find()->where(['id' => $id])->one();
               if (!$commentForm) {
                    return $this->json(false, [], 'Comment not found', HttpStatus::NOT_FOUND);
               }
               $commentForm->load(Yii::$app->request->post(), '');
               if ($commentForm->validate() && $commentForm->save()) {
                    $this->commentService->clearCommentCache();
                    return $this->json(true, ['comment' => $commentForm], 'Update comment successfully');
               }

               $transaction->commit();
               return $this->json(false, ['errors' => $commentForm->getErrors()], "Can't update comment", HttpStatus::BAD_REQUEST);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionUpdate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }


     public function actionDelete($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $comment = $this->commentRepository->findOne($id);
               if (empty($comment)) {
                    return $this->json(false, [], "Comment not found", HttpStatus::NOT_FOUND);
               }
               if (!$this->commentRepository->delete($comment)) {
                    return $this->json(false, ['errors' => $comment->getErrors()], "Can't delete comment", HttpStatus::BAD_REQUEST);
               }
               $this->commentService->clearCommentCache();
               $transaction->commit();
               return $this->json(true, [], 'Delete comment successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }


     protected function findModel($id)
     {
          try {
               if (($model = $this->commentRepository->findOne($id)) !== null) {
                    return $this->json(true, ['data' => $model], 'Success', HttpStatus::OK);
               }
               return $this->json(false, [], 'The requested page does not exist.', HttpStatus::NOT_FOUND);
          } catch (\Exception $e) {
               Yii::error('Error in findModel: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
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