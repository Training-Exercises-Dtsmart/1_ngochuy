<?php

namespace app\modules\shops\controllers;

use Yii;
use app\components\CustomSerializer;
use app\components\RateLimitBehavior;
use app\controllers\Controller;
use app\modules\enums\HttpStatus;
use app\modules\shops\forms\BrandForm;
use app\repositories\BrandRepository;
use app\services\BrandService;


/**
 * BrandController implements the CRUD actions for Brand model.
 */
class BrandController extends Controller
{
     public $modelClass = 'app\models\Brand';

     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];
     private $brandService;

     private $brandRepository;

     public function __construct($id, $module, BrandService $brandService, BrandRepository $brandRepository, $config = [])
     {
          $this->brandService = $brandService;
          $this->brandRepository = $brandRepository;
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
               $brands = $this->brandService->getAllBrands(Yii::$app->request->queryParams);
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
               $brandForm = new BrandForm();
               $brandForm->load(Yii::$app->request->post(), '');
               if (!$brandForm->validate() || !$brandForm->saveBrand()) {
                    return $this->json(false, ['errors' => $brandForm->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
               }
               $this->brandService->clearBrandCache();
               $transaction->commit();
               return $this->json(true, ["brand" => $brandForm], "Create brand successfully", HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          } catch (\Throwable $e) {
               $transaction->rollBack();
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionUpdate($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $brandForm = BrandForm::find()->where(['id' => $id])->one();
               if (!$brandForm) {
                    return $this->json(false, [], 'Brand not found', HttpStatus::NOT_FOUND);
               }
               $brandForm->load(Yii::$app->request->post(), '');
               if ($brandForm->validate() && $brandForm->save()) {
                    $this->brandService->clearBrandCache();
                    return $this->json(true, ['brand' => $brandForm], 'Update brand successfully');
               }
               $transaction->commit();
               return $this->json(false, ['errors' => $brandForm->getErrors()], "Can't update brand", HttpStatus::BAD_REQUEST);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionUpdate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          } catch (\Throwable $e) {
               $transaction->rollBack();
               Yii::error('Error in actionUpdate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionDelete($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $brand = $this->brandRepository->findOne($id);
               if (empty($brand)) {
                    return $this->json(false, [], "Brand not found", HttpStatus::NOT_FOUND);
               }
               if (!$this->brandRepository->delete($brand)) {
                    return $this->json(false, ['errors' => $brand->getErrors()], "Can't delete product", HttpStatus::BAD_REQUEST);
               }
               $this->brandService->clearBrandCache();
               $transaction->commit();
               return $this->json(true, [], 'Delete brand successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          } catch (\Throwable $e) {
               $transaction->rollBack();
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     protected function findModel($id)
     {
          try {
               if (($model = $this->brandRepository->findOne($id)) !== null) {
                    return $this->json(true, ['data' => $model], 'Success', HttpStatus::OK);
               }
               return $this->json(false, [], 'The requested page does not exist.', HttpStatus::NOT_FOUND);
          } catch (\Exception $e) {
               Yii::error('Error in findModel: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          } catch (\Throwable $e) {
               Yii::error('Error in findModel: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }

     }

     public function actionMultiple()
     {
          $data = Yii::$app->request->post();
          $data = implode(',', $data);
          $data = explode(',', $data);

          $numberA = $data[0];
          $numberB = $data[1];

          if (is_numeric($numberA) && is_numeric($numberB)) {
               if (strlen($numberA) <= 255 && strlen($numberB) <= 255) {
                    $a_len = strlen($numberA);
                    $b_len = strlen($numberB);

                    $result = 0;
                    for ($i = 0; $i < $a_len; $i++) {
                         for ($j = 0; $j < $b_len; $j++) {
                              $digit_a = (int)substr($numberA, $a_len - 1 - $i, 1);
                              $digit_b = (int)substr($numberB, $b_len - 1 - $j, 1);
                              $partial_product = $digit_a * $digit_b * pow(10, $i + $j);
                              $result += $partial_product;
                         }
                    }
                    $result = number_format($result, 2, '.', '');
                    return $this->json(true, $result, 'success', HttpStatus::OK);
               }
          }
          return $this->json(true, [], 'Dữ liệu không hợp lệ', HttpStatus::BAD_REQUEST);

     }


}
