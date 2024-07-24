<?php

namespace app\modules\shops\controllers;

use app\controllers\Controller;
use app\modules\core\Pagination;
use app\modules\enums\HttpStatus;
use app\modules\shops\form\OrderForm;
use app\modules\shops\models\Order;
use app\modules\shops\search\OrderSearch;
use app\modules\users\models\User;
use Yii;
use yii\web\UnauthorizedHttpException;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{

     public function beforeAction($action)
     {
          if (!parent::beforeAction($action)) {
               return false;
          }

          // Log the action being executed
          Yii::info("Executing action: " . $action->id, __METHOD__);

          // Token validation
          $headers = Yii::$app->request->headers;
          $authHeader = $headers->get('Authorization');
          if ($authHeader && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
               $token = $matches[1];
               // Validate the token
               if (!$this->validateToken($token)) {
                    throw new UnauthorizedHttpException('Invalid token.');
               }
          } else {
               throw new UnauthorizedHttpException('No token provided.');
          }

          // Additional initialization or checks can be added here

          return true; // Return true to proceed with the action
     }

     // Example method to validate the token
     protected function validateToken($token)
     {
          // Find the user by access token
          $user = User::findOne(['access_token' => $token]);
          if ($user) {
               // Optionally, you can add additional checks here (e.g., token expiration)
               return true;
          }
          return false;
     }

     public function actionIndex()
     {
          $orders = Order::find();
          if (!$orders) {
               return $this->json(false, [], 'Order not found', HttpStatus::NOT_FOUND);
          }

          $dataProvider = Pagination::getPagination($orders, 10, SORT_DESC);

          return $this->json(true, ["products" => $dataProvider], "Success", HttpStatus::OK);
     }

     public function actionSearch()
     {
          $modelSearch = new OrderSearch();
          $dataProvider = $modelSearch->search(Yii::$app->request->getQueryParams());

          if ($dataProvider->getCount() == 0) {
               return $this->json(false, [], "Not found", HttpStatus::NOT_FOUND);
          }
          return $this->json(true, ["products" => $dataProvider->getModels()], "Find successfully", HttpStatus::OK);
     }

     public function actionCreate()
     {
          $orderForm = new OrderForm();
          $orderForm->load(Yii::$app->request->post(), '');

          if (!$orderForm->validate() || !$orderForm->save()) {
               return $this->json(false, ["errors" => $orderForm->getErrors()], "Can't create new product", HttpStatus::BAD_REQUEST);
          }

          if ($orderForm->save()) {
              if ($orderForm->sendEmailToVendor())
              {
                   Yii::error("Email to the vendor is not sent");
              }
              else if ($orderForm->sendEmailToCustomer())
              {
                    Yii::error("Email to the customer is not sent");
              }
          }
          return $this->json(true, ["product" => $orderForm], "Create product and send email has successfully", HttpStatus::OK);
     }

     public function actionUpdate($id)
     {
          $order = OrderForm::findOne($id);
          if ($order == null) {
               return $this->json(false, [], "Order not found", HttpStatus::NOT_FOUND);
          }

          $order->load(Yii::$app->request->post(), '');
          if (!$order->validate() || !$order->save()) {
               return $this->json(false, ['errors' => $order->getErrors()], "Can't update product", HttpStatus::BAD_REQUEST);
          }

          return $this->json(true, ['order' => $order], 'Update product successfully', HttpStatus::OK);
     }

     protected function sendEmailToVendor()
     {
          return Yii::$app->mailer->compose(
               ['html' => 'order_completed_vendor-html', 'text' => 'order_completed_vendor-text'],
               ['order' => $this]
          )
               ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . 'robot'])
               ->setTo(Yii::$app->params['vendorEmail'])
               ->setSubject('New order has been made at: ' . Yii::$app->name)
               ->send();
     }

     protected function sendEmailToCustomer()
     {
          return Yii::$app->mailer->compose(
               ['html' => 'order_completed_customer-html', 'text' => 'order_completed_customer-text'],
               ['order' => $this]
          )
               ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. 'robot'])
               ->setTo($this->email)
               ->setSubject('Your order has been completed at: '. Yii::$app->name)
               ->send();
     }

     public function actionDelete($id)
     {
          $order = Order::find()->where(['id' => $id])->one();
          if ($order == null) {
               return $this->json(false, [], "Order not found", HttpStatus::NOT_FOUND);
          }
          $order->load(Yii::$app->request->post(), '');
          if (!$order->delete()) {
               return $this->json(false, ['errors' => $order->getErrors()], "Can't delete order", HttpStatus::BAD_REQUEST);
          }
          return $this->json(true, [], 'Delete order successfully', HttpStatus::OK);
     }
}
