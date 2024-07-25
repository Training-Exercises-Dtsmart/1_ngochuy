<?php

namespace app\modules\shops\controllers;


use app\controllers\Controller;
use app\modules\core\Pagination;
use app\modules\enums\HttpStatus;
use app\modules\shops\form\PostForm;
use app\modules\shops\models\AddToPost;
use app\modules\shops\models\Post;
use app\modules\shops\search\PostSearch;
use Yii;


class PostController extends Controller
{
     public $modelClass = 'modules\models\Post';
     public $serializer = [
          'class' => 'yii\rest\Serializer',
          'collectionEnvelope' => 'items',
     ];

     public function actionIndex()
     {
          $posts = Post::find();

          $dataProvider = Pagination::getPagination($posts, 10, SORT_DESC);
          return $this->json(true, ["posts" => $dataProvider], "Success", HttpStatus::OK);
     }

     public function actionCreate()
     {
          $postForm = new PostForm();

          $data = $postForm->load(Yii::$app->request->post(), '');
          if ($postForm->uploadMultipleImage($data) && $postForm->validate()) {
               if ($postForm->save()) {
                    return $this->json(true, ["product" => $postForm], "Create product successfully", HttpStatus::OK);
               }
          }
          return $this->json(false, ['errors' => $postForm->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
     }

     public function actionUpdate($id)
     {
          $post = Post::find()->where(['id' => $id])->one();

          $post->load(Yii::$app->request->post());
          if (!$post->validate() || !$post->save()) {
               return $this->json(false, ["errors" => $post->getErrors()], "Can't update post", HttpStatus::BAD_REQUEST);
          }
          return $this->json(true, ["post" => $post], "Update post successfully", HttpStatus::OK);
     }

     public function actionDelete($id)
     {
          $post = Post::find()->where(["id" => $id])->one();
          if (empty($post)) {
               return $this->json(false, [], "Post not found", HttpStatus::NOT_FOUND);
          }
          $post->load(Yii::$app->request->post());
          if (!$post->delete()) {
               return $this->json(false, ['errors' => $post->getErrors()], "Can't delete post", HttpStatus::BAD_REQUEST);
          }
          return $this->json(true, [], 'Delete post successfully', HttpStatus::OK);
     }

     public function actionSearch()
     {
          $searchModel = new PostSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

          if ($dataProvider->getCount() == 0) {
               return $this->json(false, ['errors' => $searchModel->getErrors()], "Not found", HttpStatus::NOT_FOUND);
          }

          return $this->json(true, ["posts" => $dataProvider->getModels()], "Find successfully");
     }


     public function actionCreateFakeData()
     {
          if (Yii::$app->queue->delay(2)->push(new AddToPost())) {
               return $this->json(true, [], "Enqueued job to create fake data", HttpStatus::OK);
          } else {
               return $this->json(false, [], "Failed to enqueue job", HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

}