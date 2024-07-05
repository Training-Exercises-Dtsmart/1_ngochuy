<?php

namespace app\modules\controllers;

use app\controllers\Controller;
use app\modules\models\Product;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['index', 'view', 'create', 'update', 'delete', 'test-many-query'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return Product::find()->all();
    }

    public function actionTestManyQuery()
    {
        $products = Product::find()->with("categoryProduct")->all();

        $countCategoryProduct = 0;

        foreach ($products as $product) {
            if ($product->category->name === "Laptop") {
                $countCategoryProduct++;
            }
        }
        return $countCategoryProduct > 4;
    }

    public function actionView($id)
    {
        return $this->findModel($id);
    }

    public function actionCreate()
    {
        $model = new Product();
        $response = Yii::$app->response;

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            $response->statusCode = 201; 
            return $model;
        }

        $response->statusCode = 400;
        return ['errors' => $model->errors];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $response = Yii::$app->response;

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return $model;
        }

        $response->statusCode = 400; 
        return ['errors' => $model->errors];
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->response->statusCode = 204; 
        return ['message' => 'Product deleted'];
    }

    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested product does not exist.');
    }

}