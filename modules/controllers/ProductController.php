<?php

namespace app\modules\controllers;

use Yii;
use app\models\search\ProductSearch;
use app\modules\models\Product;
use app\models\form\ProductForm;
use app\modules\models\HttpStatus;
use app\controllers\Controller;
use app\modules\models\pagination\Pagination;
use yii\filters\auth\HttpBearerAuth;

class ProductController extends Controller
{
    public $modelClass = 'app\modules\models\Product';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    public function actionIndex()
    {
        $products = Product::find();
        if (!$products) {
            return $this->json(false, [], 'Product not found', HttpStatus::NOT_FOUND);
        }

        $dataProvider = Pagination::getPagination($products, 10, SORT_DESC);
        
        return $this->json(true, ["products" => $dataProvider], "Success", HttpStatus::OK);
    }

    public function actionView($id)
    {
        $product = Product::find()->where(["id" => $id])->one();

        $product->load(Yii::$app->request->post());
        return $this->json(true, ["products" => $product]);
    }


    public function actionSearch()
    {
        $modelSearch = new ProductSearch();
        $dataProvider = $modelSearch->search(Yii::$app->request->getQueryParams());

        if ($dataProvider->getCount() == 0) {
            return $this->json(false, [], "Not found", HttpStatus::NOT_FOUND);
        }
        return $this->json(true, ["products" => $dataProvider->getModels()], "Find successfully", HttpStatus::OK);
    }

    public function actionCreate()
    {
        $productForm = new ProductForm();
        $productForm->load(Yii::$app->request->post(), '');

        if (!$productForm->validate() || !$productForm->save()) {
            return $this->json(false, ["errors" => $productForm->getErrors()], "Can't create new product", HttpStatus::BAD_REQUEST);
        }

        return $this->json(true, ["product" => $productForm], "Create product successfully", HttpStatus::OK);
    }

    public function actionUpdate($id)
    {
        $product = Product::findOne($id);
        $product->load(Yii::$app->request->post(), '');

        if (!$product->validate() || !$product->save()) {
            return $this->json(false, ['errors' => $product->getErrors()], "Can't update product", HttpStatus::BAD_REQUEST);
        }

        return $this->json(true, ['product' => $product], 'Update product successfully', HttpStatus::OK);
    }

    public function actionDelete($id)
    {
        $product = Product::find()->where(["id" => $id])->one();

        if (empty($product)) {
            return $this->json(false, [], "Product not found", HttpStatus::NOT_FOUND);
        }

        $product->load(Yii::$app->request->post());

        if (!$product->delete()) {
            return $this->json(false, ['errors' => $product->getErrors()], "Can't delete product", HttpStatus::BAD_REQUEST);
        }

        return $this->json(true, [], 'Delete product successfully', HttpStatus::OK);

    }
}