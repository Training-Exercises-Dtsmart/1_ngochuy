<?php

namespace app\modules\shops\controllers;

use app\components\RateLimitBehavior;
use Yii;
use app\components\CustomSerializer;
use app\controllers\Controller;
use app\models\form\ProductForm;
use app\models\search\ProductSearch;
use app\modules\enums\HttpStatus;
use app\modules\shops\models\Product;
use app\services\ProductService;
use app\repositories\ProductRepository;

class ProductController extends Controller
{
     public $modelClass = 'app\models\Product';

     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];

     private $productService;

     private $productRepository;

     public function __construct($id, $module, ProductService $productService, ProductRepository $productRepository, $config = [])
     {
          $this->productService = $productService;
          $this->productRepository = $productRepository;
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
               $brands = $this->productService->getAllProducts(Yii::$app->request->queryParams);
               return $this->json(true, ["brands" => $brands], "Success", HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionIndex: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionCreate()
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $product = new ProductForm();
               $product->load(Yii::$app->request->post(), '');

               if (!$product->validate() || !$product->save()) {
                    return $this->json(false, ['errors' => $product->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
               }
               $this->productService->clearProductCache();
               $transaction->commit();
               return $this->json(true, ["product" => $product], "Create product successfully", HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionView($id)
     {
          $payment_type = Product::find()->where(["id" => $id])->one();
          if (empty($payment_type)) {
               return $this->json(false, [], 'Payment type not found', HttpStatus::NOT_FOUND);
          }
          return $this->json(true, ["payment_types" => $payment_type], 'Find payment type successfully');
     }

     public function actionUpdate($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $productForm = \app\modules\shops\forms\ProductForm::find()->where(['id' => $id])->one();
               if (!$productForm) {
                    return $this->json(false, [], 'Product not found', HttpStatus::NOT_FOUND);
               }
               $productForm->load(Yii::$app->request->post(), '');
               if ($productForm->validate() && $productForm->save()) {
                    $this->productService->clearProductCache();
                    return $this->json(true, ['brand' => $productForm], 'Update product successfully');
               }
               $transaction->commit();
               return $this->json(false, ['errors' => $productForm->getErrors()], "Can't update brand", HttpStatus::BAD_REQUEST);
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
               $product = $this->productRepository->findOne($id);
               if (empty($product)) {
                    return $this->json(false, [], "Payment not found", HttpStatus::NOT_FOUND);
               }
               if (!$this->productRepository->delete($product)) {
                    return $this->json(false, ['errors' => $product->getErrors()], "Can't delete product", HttpStatus::BAD_REQUEST);
               }
               $this->productService->clearProductCache();
               $transaction->commit();
               return $this->json(true, [], 'Delete product successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }

     }

     public function actionSearch()
     {
          try {
               $products = $this->productService->searchProducts(Yii::$app->request->queryParams);
               return $this->json(true, ['payment_types' => $products], 'Find payment types successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionSearch: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     protected function findModel($id)
     {
          try {
               if (($model = $this->productRepository->findOne($id)) !== null) {
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

          return $this->json(true, ["products" => $data], "Data retrieved successfully", HttpStatus::OK);
     }

}