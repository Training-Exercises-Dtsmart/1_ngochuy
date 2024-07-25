<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-25 15:55:55
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-25 17:09:09
 * @FilePath: modules/shops/controllers/PaymentTypeController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\shops\controllers;

use app\modules\enums\HttpStatus;
use app\modules\shops\forms\PaymentTypeForm;
use app\modules\shops\models\PaymentType;
use app\modules\shops\search\PaymentTypeSearch;
use Yii;
use app\controllers\Controller;


class PaymentTypeController extends Controller
{
     public $modelClass = 'modules\models\Product';

     public $serializer = [
          'class' => 'yii\rest\Serializer',
          'collectionEnvelope' => 'items',
     ];

     public function actionIndex()
     {
          $key = "payment_type-list";
          $payment_types = Yii::$app->cache->get($key);
          if (!$payment_types) {
               $searchModel = new PaymentTypeSearch();
               $payment_types = $searchModel->search(Yii::$app->request->queryParams);
               Yii::$app->cache->set($key, $payment_types, 600);
          }
          return $this->json(true, $payment_types);
     }

     public function actionCreate()
     {
          $paymentTypeForm = new PaymentTypeForm();
          $paymentTypeForm->load(Yii::$app->request->post());

          if (!$paymentTypeForm->validate() || !$paymentTypeForm->save()) {
               return $this->json(false, ["errors" => $paymentTypeForm->getErrors()], "Can't create new payment type", HttpStatus::BAD_REQUEST);
          }

          return $this->json(true, ["payment_type" => $paymentTypeForm], "Create  a payment type successfully");
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

          if (!Yii::$app->user->can('updatePaymentType', ['paymentType' => $paymentType])) {
               return $this->json(false, [], 'Permission denied', HttpStatus::FORBIDDEN);
          }

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
          $paymentType = PaymentType::find()->where(["id" => $id])->one();
          if (!Yii::$app->user->can('deletePaymentType', ['payment_type' => $paymentType])) {
               return $this->json(false, [], 'Permission denied', HttpStatus::FORBIDDEN);
          }

          if (empty($post)) {
               return $this->json(false, [], "Payment type not found", HttpStatus::NOT_FOUND);
          }
          $post->save();
          
          return $this->json(true, [], 'Delete  successfully', HttpStatus::OK);
     }

     public function actionSearch()
     {
          $searchModel = new PaymentTypeSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

          return $this->json(true, ["payment_types" => $dataProvider->getModels()], "Find successfully");
     }

     protected function findModel($id)
     {
          if (($model = PaymentType::findOne($id) !== null)) {
               return $model;
          } else {
               return $this->json(false, ['error' => $model->getErrors()], 'Failed to find payment type', HttpStatus::class);
          }
     }
}