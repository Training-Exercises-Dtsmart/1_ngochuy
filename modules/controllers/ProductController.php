<?php

namespace app\modules\controllers;

use app\controllers\Controller;
use app\models\search\ProductSearch;
use app\modules\models\Product;
use app\models\form\ProductForm;
use app\modules\models\HttpStatus;
use Yii;

class ProductController extends Controller
{
    public function actionIndex()
    {
        $products = Product::find()->all();
        return $this->json(true, ["products" => $products]);
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

        if (!$product) {
            return $this->json(false, [], "Product not found", HttpStatus::NOT_FOUND);
        }

        $product->load(Yii::$app->request->post());

        if (!$product->delete()) {
            return $this->json(false, ['errors' => $product->getErrors()], "Can't delete product", HttpStatus::BAD_REQUEST);
        }

        return $this->json(true, [], 'Delete product successfully', HttpStatus::OK);

    }
}