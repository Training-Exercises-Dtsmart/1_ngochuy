<?php

namespace app\controllers;

use app\models\form\ProductForm;
use app\models\Product;
use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductController extends Controller
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $products = Product::find()->all();
        return [
            'status' => 'success',
            'data' => $products
        ];
    }

    public function actionCreate()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $productForm = new ProductForm();

        if ($productForm->load(Yii::$app->request->post(), '') && $productForm->validate()) {
            if ($productForm->save()) {
                return [
                    'status' => 'success',
                    'data' => $productForm
                ];
            } else {
                Yii::$app->response->statusCode = 500;
                return [
                    'status' => 'error',
                    'message' => 'Failed to save the product.'
                ];
            }
        } else {
            Yii::$app->response->statusCode = 422;
            return [
                'status' => 'error',
                'errors' => $productForm->getErrors()
            ];
        }

    }

    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = $this->findModel($id);
        $productForm = new ProductForm();

        if ($productForm->load(Yii::$app->request->post(), '')) {
            $product->attributes = $productForm->attributes;
            if ($product->save()) {
                return [
                    'status' => 'success',
                    'data' => $product
                ];
            } else {
                Yii::$app->response->statusCode = 500;
                return [
                    'status' => 'error',
                    'message' => 'Failed to update the product.'
                ];
            }
        } else {
            Yii::$app->response->statusCode = 422;
            return [
                'status' => 'error',
                'errors' => $productForm->getErrors()
            ];
        }
    }

    public function actionDelete($id)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = $this->findModel($id);

        if ($product->delete()) {
            return [
                'status' => 'success',
                'message' => 'Product deleted successfully.'
            ];
        } else {
            Yii::$app->response->statusCode = 500;
            return [
                'status' => 'error',
                'message' => 'Failed to delete the product.'
            ];
        }
    }

    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested product does not exist.');
    }
}