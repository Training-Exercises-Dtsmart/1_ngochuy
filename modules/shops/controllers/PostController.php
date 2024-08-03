<?php

namespace app\modules\shops\controllers;

use Yii;
use app\components\RateLimitBehavior;
use app\components\CustomSerializer;
use app\controllers\Controller;
use app\models\form\PostForm;
use app\modules\enums\HttpStatus;
use app\modules\shops\models\Post;
use app\services\PostService;
use app\repositories\PostRepository;

class PostController extends Controller
{
     public $modelClass = 'app\models\Post';

     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];

     private $postService;

     private $postRepository;

     public function __construct($id, $module, PostService $postService, PostRepository $postRepository, $config = [])
     {
          $this->postService = $postService;
          $this->postRepository = $postRepository;
          parent::__construct($id, $module, $config);
     }

     public function behaviors()
     {
          $behaviors = parent::behaviors();

          // Add rate limiter for specific actions
          if (in_array($this->action->id, ['index', 'create', 'update', 'delete'])) {
               $behaviors['rateLimiter'] = [
                    'class' => RateLimitBehavior::class,
                    'enableRateLimitHeaders' => true,
               ];
          }

          // Add access control behavior
          $behaviors['access'] = [
               'class' => AccessControl::class,
               'rules' => [
                    [
                         'allow' => true,
                         'actions' => ['index'],
                         'roles' => ['managePost'],
                    ],
                    [
                         'allow' => true,
                         'actions' => ['view'],
                         'roles' => ['viewPost'],
                    ],
                    [
                         'allow' => true,
                         'actions' => ['create'],
                         'roles' => ['createPost'],
                    ],
                    [
                         'allow' => true,
                         'actions' => ['update'],
                         'roles' => ['updatePost'],
                    ],
                    [
                         'allow' => true,
                         'actions' => ['delete'],
                         'roles' => ['deletePost'],
                    ],
               ],
          ];

          return $behaviors;
     }
     public function actionIndex()
     {
          try {
               $posts = $this->postService->getAllPosts(Yii::$app->request->queryParams);
               return $this->json(true, ["posts" => $posts], "Success", HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionIndex: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionCreate()
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $post = new PostForm();
               $post->load(Yii::$app->request->post(), '');

               if (!$post->validate() || !$post->save()) {
                    return $this->json(false, ['errors' => $post->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
               }
               $this->postService->clearPostCache();
               $transaction->commit();
               return $this->json(true, ["post" => $post], "Create post successfully", HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionView($id)
     {
          $post = Post::find()->where(["id" => $id])->one();
          if (empty($post)) {
               return $this->json(false, [], 'Post not found', HttpStatus::NOT_FOUND);
          }
          return $this->json(true, ["post" => $post], 'Find payment type successfully');
     }

     public function actionUpdate($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $postForm = \app\modules\shops\forms\PostForm::find()->where(['id' => $id])->one();
               if (!$postForm) {
                    return $this->json(false, [], 'Post not found', HttpStatus::NOT_FOUND);
               }
               $postForm->load(Yii::$app->request->post(), '');
               if ($postForm->validate() && $postForm->save()) {
                    $this->postService->clearPostCache();
                    return $this->json(true, ['post' => $postForm], 'Update post successfully');
               }
               $transaction->commit();
               return $this->json(false, ['errors' => $postForm->getErrors()], "Can't update brand", HttpStatus::BAD_REQUEST);
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
               $post = $this->postRepository->findOne($id);
               if (empty($post)) {
                    return $this->json(false, [], "Post not found", HttpStatus::NOT_FOUND);
               }
               if (!$this->postRepository->delete($post)) {
                    return $this->json(false, ['errors' => $post->getErrors()], "Can't delete post", HttpStatus::BAD_REQUEST);
               }
               $this->postService->clearPostCache();
               $transaction->commit();
               return $this->json(true, [], 'Delete post successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }

     }

     public function actionSearch()
     {
          try {
               $posts = $this->postService->searchPosts(Yii::$app->request->queryParams);
               return $this->json(true, ['posts' => $posts], 'Find payment types successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionSearch: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     protected function findModel($id)
     {
          try {
               if (($model = $this->postRepository->findOne($id)) !== null) {
                    return $this->json(true, ['data' => $model], 'Success', HttpStatus::OK);
               }
               return $this->json(false, [], 'The requested page does not exist.', HttpStatus::NOT_FOUND);
          } catch (\Exception $e) {
               Yii::error('Error in findModel: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionComponent()
     {
          $data = Yii::$app->common->getData();

          if ($data === null) {
               return $this->json(false, [], "No data found", HttpStatus::NOT_FOUND);
          }

          return $this->json(true, ["posts" => $data], "Data retrieved successfully", HttpStatus::OK);
     }

}