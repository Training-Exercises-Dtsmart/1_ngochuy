<?php

namespace app\modules\shops\controllers;

use Yii;
use app\components\CustomSerializer;
use app\components\RateLimitBehavior;
use app\controllers\Controller;
use app\modules\enums\HttpStatus;
use app\modules\shops\forms\OrderForm;
use app\modules\shops\search\OrderSearch;
use app\modules\users\models\User;
use yii\web\UnauthorizedHttpException;
use app\services\OrderService;
use app\repositories\OrderRepository;


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

     public $modelClass = 'app\models\Order';

     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];

     private $orderService;

     private $orderRepository;

     public function __construct($id, $module, OrderService $orderService, OrderRepository $orderRepository, $config = [])
     {
          $this->orderService = $orderService;
          $this->orderRepository = $orderRepository;
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
               $orders = $this->orderService->getAllOrders(Yii::$app->request->queryParams);
               return $this->json(true, ["orders" => $orders], "Success", HttpStatus::OK);
          } catch (\Exception $e) {
               Yii::error('Error in actionIndex: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionSearch()
     {
          $modelSearch = new OrderSearch();
          $dataProvider = $modelSearch->search(Yii::$app->request->getQueryParams());

          if ($dataProvider->getCount() == 0) {
               return $this->json(false, [], "Order not found", HttpStatus::NOT_FOUND);
          }
          return $this->json(true, ["orders" => $dataProvider->getModels()], "Find successfully", HttpStatus::OK);
     }

     public function actionCreate()
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $orderForm = new OrderForm();
               $orderForm->load(Yii::$app->request->post(), '');

               if (!$orderForm->validate() || !$orderForm->save()) {
                    return $this->json(false, ["errors" => $orderForm->getErrors()], "Can't create new order", HttpStatus::BAD_REQUEST);
               }

               if ($orderForm->save()) {
                    if ($orderForm->sendEmailToVendor()) {
                         Yii::error("Email to the vendor is not sent");
                    } else if ($orderForm->sendEmailToCustomer()) {
                         Yii::error("Email to the customer is not sent");
                    }
               }
               $this->orderService->clearOrderCache();
               $transaction->commit();
               return $this->json(true, ["order" => $orderForm], "Create order successfully", HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionCreate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionUpdate($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $orderForm = OrderForm::find()->where(['id' => $id])->one();
               if (!$orderForm) {
                    return $this->json(false, [], 'Order not found', HttpStatus::NOT_FOUND);
               }
               $orderForm->load(Yii::$app->request->post(), '');
               if ($orderForm->validate() && $orderForm->save()) {
                    $this->orderService->clearOrderCache();
                    return $this->json(true, ['order' => $orderForm], 'Update order successfully');
               }
               $transaction->commit();
               return $this->json(false, ['errors' => $orderForm->getErrors()], "Can't update order", HttpStatus::BAD_REQUEST);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionUpdate: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
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
               ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . 'robot'])
               ->setTo($this->email)
               ->setSubject('Your order has been completed at: ' . Yii::$app->name)
               ->send();
     }

     public function actionDelete($id)
     {
          $transaction = Yii::$app->db->beginTransaction();
          try {
               $order = $this->orderRepository->findOne($id);
               if (empty($order)) {
                    return $this->json(false, [], "Order not found", HttpStatus::NOT_FOUND);
               }
               if (!$this->orderRepository->delete($order)) {
                    return $this->json(false, ['errors' => $order->getErrors()], "Can't delete order", HttpStatus::BAD_REQUEST);
               }
               $this->orderService->clearOrderCache();
               $transaction->commit();
               return $this->json(true, [], 'Delete order successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::error('Error in actionDelete: ' . $e->getMessage(), __METHOD__);
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }
}
