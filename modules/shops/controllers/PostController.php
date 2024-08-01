<?php

namespace app\modules\shops\controllers;

use app\components\RateLimitBehavior;
use app\modules\shops\models\AddToPost;
use Yii;
use app\controllers\Controller;
use app\services\PostService;
use app\components\CustomSerializer;
use app\modules\enums\HttpStatus;

class PostController extends Controller
{
     public $modelClass = 'app\models\Post';
     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];

     private $postService;

     public function __construct($id, $module, PostService $postService, $config = [])
     {
          $this->postService = $postService;
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
     public function actionIndex()
     {
          try {
               $dataProvider = $this->postService->getAllPosts(Yii::$app->request->queryParams);
               return $this->json(true, ["posts" => $dataProvider], "Success", HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionIndex: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => 'Internal Server Error'], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionCreate()
     {
          try {
               $postData = Yii::$app->request->post();
               if ($this->postService->createPost($postData)) {
                    return $this->json(true, ["post" => $postData], "Create post successfully", HttpStatus::OK);
               }
               return $this->json(false, ['errors' => 'Validation Failed'], "Bad request", HttpStatus::BAD_REQUEST);
          } catch (\Exception $e) {
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => 'Internal Server Error'], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionUpdate($id)
     {
          try {
               $postData = Yii::$app->request->post();
               if ($this->postService->updatePost($id, $postData)) {
                    return $this->json(true, ["post" => $postData], "Update post successfully", HttpStatus::OK);
               }
               return $this->json(false, ['errors' => 'Validation Failed'], "Bad request", HttpStatus::BAD_REQUEST);
          } catch (\Exception $e) {
               Yii::error('Error in actionUpdate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => 'Internal Server Error'], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionDelete($id)
     {
          try {
               if ($this->postService->deletePost($id)) {
                    return $this->json(true, [], 'Delete post successfully', HttpStatus::OK);
               }
               return $this->json(false, ['errors' => 'Post not found'], "Not found", HttpStatus::NOT_FOUND);
          } catch (\Exception $e) {
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => 'Internal Server Error'], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionSearch()
     {
          try {
               $queryParams = Yii::$app->request->queryParams;
               $posts = $this->postService->searchPosts($queryParams);
               if (empty($posts)) {
                    return $this->json(false, ['errors' => 'Not found'], "Not found", HttpStatus::NOT_FOUND);
               }
               return $this->json(true, ["posts" => $posts], "Find successfully", HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionSearch: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => 'Internal Server Error'], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionCreateFakeData()
     {
          try {
               if (Yii::$app->queue->delay(2)->push(new AddToPost())) {
                    return $this->json(true, [], "Enqueued job to create fake data", HttpStatus::OK);
               } else {
                    return $this->json(false, [], "Failed to enqueue job", HttpStatus::INTERNAL_SERVER_ERROR);
               }
          } catch (\Exception $e) {
               Yii::error('Error in actionCreateFakeData: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => 'Internal Server Error'], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionCreateFakeData2()
     {
          try {
               if (Yii::$app->queue->delay(2)->push(new AddToPost())) {
                    return $this->json(true, [], "Enqueued job to create fake data", HttpStatus::OK);
               } else {
                    return $this->json(false, [], "Failed to enqueue job", HttpStatus::INTERNAL_SERVER_ERROR);
               }
          } catch (\Exception $e) {
               Yii::error('Error in actionCreateFakeData: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => 'Internal Server Error'], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

}
