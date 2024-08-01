<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-25 15:55:55
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 10:27:56
 * @FilePath: modules/shops/controllers/PaymentTypeController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\shops\controllers;

use Yii;
use app\components\CustomSerializer;
use app\components\RateLimitBehavior;
use app\modules\enums\HttpStatus;
use app\modules\shops\forms\PaymentTypeForm;
use app\modules\shops\models\PaymentType;
use app\controllers\Controller;
use app\services\PaymentTypeService;
use app\repositories\PaymentTypeRepository;


class PaymentTypeController extends Controller
{
     public $modelClass = 'app\models\PaymentType';

     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];

     private $paymentTypeService;

     private $paymentTypeRepository;

     public function __construct($id, $module, PaymentTypeService $paymentTypeService, PaymentTypeRepository $paymentTypeRepository, $config = [])
     {
          $this->paymentTypeService = $paymentTypeService;
          $this->paymentTypeRepository = $paymentTypeRepository;
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
               $paymentTypes = $this->paymentTypeService->getAllPaymentType(Yii::$app->request->queryParams);
               return $this->json(true, ["paymentTypes" => $paymentTypes], "Success", HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionIndex: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionCreate()
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $paymentType = new PaymentTypeForm();
               $paymentType->load(Yii::$app->request->post(), '');

               if (!$paymentType->validate() || !$paymentType->save()) {
                    return $this->json(false, ['errors' => $paymentType->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
               }
               $this->paymentTypeService->clearPaymentTypeCache();
               $transaction->commit();
               return $this->json(true, ["Payment Type" => $paymentType], "Create comment successfully", HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionView($id)
     {
          $payment_type = PaymentType::find()->where(["id" => $id])->one();
          if (empty($payment_type)) {
               return $this->json(false, [], 'Payment type not found', HttpStatus::NOT_FOUND);
          }
          return $this->json(true, ["payment_types" => $payment_type], 'Find payment type successfully');
     }

     public function actionUpdate($id)
     {
          $paymentType = PaymentType::find()->where(['id' => $id])->one();

//          if (!Yii::$app->user->can('updatePaymentType', ['paymentType' => $paymentType])) {
//               return $this->json(false, [], 'Permission denied', HttpStatus::FORBIDDEN);
//          }

          if (empty($paymentType)) {
               return $this->json(false, [], 'Payment type not found', HttpStatus::NOT_FOUND);
          }

          $paymentType->load(Yii::$app->request->post(), '');
          if (!$paymentType->validate() || !$paymentType->save()) {
               return $this->json(false, ["errors" => $paymentType->getErrors()], "Can't update payment type", HttpStatus::BAD_REQUEST);
          }
          return $this->json(true, ["payment_types" => $paymentType], "Update payment type successfully", HttpStatus::OK);
     }

     public function actionDelete($id)
     {
//          if (!Yii::$app->user->can('deletePaymentType', ['payment_type' => $paymentType])) {
//               return $this->json(false, [], 'Permission denied', HttpStatus::FORBIDDEN);
//          }
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $paymentType = $this->paymentTypeRepository->findOne($id);
               if (empty($paymentType)) {
                    return $this->json(false, [], "Payment not found", HttpStatus::NOT_FOUND);
               }
               if (!$this->paymentTypeRepository->delete($paymentType)) {
                    return $this->json(false, ['errors' => $paymentType->getErrors()], "Can't delete paymentType", HttpStatus::BAD_REQUEST);
               }
               $this->paymentTypeService->clearPaymentTypeCache();
               $transaction->commit();
               return $this->json(true, [], 'Delete paymentType successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }

     }

     public function actionSearch()
     {
          try {
               $paymentTypes = $this->paymentTypeService->searchPaymentTypes(Yii::$app->request->queryParams);
               return $this->json(true, ['payment_types' => $paymentTypes], 'Find payment types successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionSearch: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     protected function findModel($id)
     {
          try {
               if (($model = $this->paymentTypeRepository->findOne($id)) !== null) {
                    return $this->json(true, ['data' => $model], 'Success', HttpStatus::OK);
               }
               return $this->json(false, [], 'The requested page does not exist.', HttpStatus::NOT_FOUND);
          } catch (\Exception $e) {
               Yii::error('Error in findModel: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }
}