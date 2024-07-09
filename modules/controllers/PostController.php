<?php

namespace app\modules\controllers;

use Yii;
use app\modules\models\form\PostForm;
use app\modules\models\HttpStatus;
use app\modules\models\Post;
use app\modules\models\search\PostSearch;
use app\controllers\Controller;
use app\modules\models\pagination\Pagination;

class PostController extends Controller
{
    public $modelClass = 'app\modules\models\Post';
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
        $postForm->load(Yii::$app->request->post());

        if (!$postForm->validate() || !$postForm->save()) {
            return $this->json(false, ["errors" => $postForm->getErrors()], "Can't create new product", HttpStatus::BAD_REQUEST);
        }

        return $this->json(true, ["product" => $postForm], "Create product successfully", HttpStatus::OK);
    }

    public function actionView($id)
    {
        $post = Post::find()->where(["id" => $id])->one();

        $post->load(Yii::$app->request->post());
        return $this->json(true, ["post" => $post]);
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

}