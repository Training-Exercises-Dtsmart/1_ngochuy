<?php

namespace app\modules\shops\controllers;

use app\controllers\Controller;
use app\models\form\ProductForm;
use app\models\search\ProductSearch;
use app\modules\enums\HttpStatus;
use app\modules\shops\models\Product;
use Yii;

class ProductController extends Controller
{
     public $modelClass = 'modules\models\Product';

     public $serializer = [
          'class' => 'yii\rest\Serializer',
          'collectionEnvelope' => 'items',
     ];

     public function actionIndex()
     {
          $cacheKey = 'product_index_' . md5(json_encode(Yii::$app->request->queryParams));
          $cachedData = Yii::$app->cache->get($cacheKey);

          if ($cachedData === false) {
               $searchModel = new ProductSearch();
               $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
               $cachedData = $dataProvider->getModels();
               Yii::$app->cache->set($cacheKey, $cachedData, 3600); // Cache for 1 hour
          }

          return $this->json(true, [
               "products" => $cachedData
          ], "Success", 200);

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

     public function actionComponent()
     {
          $data = Yii::$app->common->getData();

          if ($data === null) {
               return $this->json(false, [], "No data found", HttpStatus::NOT_FOUND);
          }

          return $this->json(true, ["products" => $data], "Data retrieved successfully", HttpStatus::OK);
     }

}