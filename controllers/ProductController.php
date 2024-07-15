<?php
namespace app\controllers;

use Yii;
use app\models\form\ProductForm;
use app\models\Product;
use app\models\search\ProductSearch;
use yii\web\Response;
use yii\db\Exception;

class ProductController extends Controller
{
     /**
      * Lists all Product models.
      * @return mixed
      */
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

     /**
      * Creates a new Product model.
      * @return mixed
      * @throws Exception
      */
     public function actionCreate()
     {
          $product = new ProductForm();

          if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {
               if ($product->save()) {
                    Yii::$app->cache->flush(); // Clear cache on create
                    return $this->json(true, ["product" => $product], "Create product successfully", 200);
               }
          }

          if (!$product->save()) {
               return $this->json(false, ["error" => $product->getErrors()], "Can't create product", 400);
          }

     }

     /**
      * Updates an existing Product model.
      * @param integer $id
      * @return mixed
      */
     public function actionUpdate($id)
     {
          $product = Product::find()->where(["id" => $id])->one();
          $productForm = new ProductForm();

          if ($productForm->load(Yii::$app->request->post(), '')) {
               if (!$productForm->validate()) {
                    return $this->json(false, ["error" => $productForm->getErrors()], "Can't update product", 400);
               }

               $product->save();
               Yii::$app->cache->flush(); // Clear cache on update
          }
          return $this->json(true, ["product" => $product], "Update product successfully", 200);
     }

     /**
      * Deletes an existing Product model.
      * @param integer $id
      * @return mixed
      */
     public function actionDelete($id)
     {
          $product = Product::find()->where(["id" => $id])->one();
          if (!$product) {
               return $this->json(false, [], "Product not found");
          }
          if (!$product->delete()) {
               return $this->json(false, [], "Can't delete product");
          }
          Yii::$app->cache->flush(); // Clear cache on delete
          return $this->json(true, [], 'Delete product successfully', 200);
     }

     /**
      * Searches for Product models.
      * @return mixed
      */
     public function actionSearch()
     {
          $cacheKey = 'product_search_' . md5(json_encode(Yii::$app->request->queryParams));
          $cachedData = Yii::$app->cache->get($cacheKey);

          if ($cachedData === false) {
               $searchModel = new ProductSearch();
               $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
               $cachedData = $dataProvider->getModels();
               Yii::$app->cache->set($cacheKey, $cachedData, 3600); // Cache for 1 hour
          }

          return $this->json(true, ["products" => $cachedData], "Find successfully", 200);
     }
}
