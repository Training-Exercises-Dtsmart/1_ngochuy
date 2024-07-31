<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use \app\models\UserQuery;
use yii\filters\RateLimitInterface;

/**
 * This is the base-model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $access_token
 * @property string $verification_token
 * @property integer $age
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \app\models\AuthAssignment[] $authAssignments
 * @property \app\models\Cart[] $carts
 * @property \app\models\CategoryPost[] $categoryPosts
 * @property \app\models\Comment[] $comments
 * @property \app\models\OrderItem[] $orderItems
 * @property \app\models\Order[] $orders
 * @property \app\models\Post[] $posts
 * @property \app\models\Post[] $posts0
 * @property \app\models\Product[] $products
 * @property \app\models\Product[] $products0
 * @property \app\models\UserAddress[] $userAddresses
 * @property \app\models\UserDeliveryAddress[] $userDeliveryAddresses
 */
abstract class User extends \yii\db\ActiveRecord implements  \yii\web\IdentityInterface, RateLimitInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'value' => (new \DateTime())->format('Y-m-d H:i:s'),
                        ];
        
    return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $parentRules = parent::rules();
        return ArrayHelper::merge($parentRules, [
            [['access_token'], 'required'],
            [['age'], 'integer'],
            [['deleted_at'], 'safe'],
            [['name', 'address', 'email', 'phone', 'password', 'access_token', 'verification_token'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'access_token' => 'Access Token',
            'verification_token' => 'Verification Token',
            'age' => 'Age',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ]);
    }

     /**
      * Returns the maximum number of allowed requests and the window size.
      *
      * @param \yii\web\Request $request
      * @param \yii\base\Action $action
      * @return array an array of two elements. The first element is the maximum number of allowed requests,
      * and the second element is the window size in seconds.
      */
     public function getRateLimit($request, $action)
     {
          return [100, 600]; // 100 requests per 10 minutes
     }

     /**
      * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
      *
      * @param \yii\web\Request $request
      * @param \yii\base\Action $action
      * @return array an array of two elements. The first element is the number of allowed requests,
      * and the second element is the corresponding UNIX timestamp.
      */
     public function loadAllowance($request, $action)
     {
          return [$this->allowance, $this->allowance_updated_at];
     }

     /**
      * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
      *
      * @param \yii\web\Request $request
      * @param \yii\base\Action $action
      * @param int $allowance the number of allowed requests remaining.
      * @param int $timestamp the current UNIX timestamp.
      */
     public function saveAllowance($request, $action, $allowance, $timestamp)
     {
          $this->allowance = $allowance;
          $this->allowance_updated_at = $timestamp;
          $this->save(false);
     }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(\app\models\AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(\app\models\Cart::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryPosts()
    {
        return $this->hasMany(\app\models\CategoryPost::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(\app\models\Comment::class, ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(\app\models\OrderItem::class, ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(\app\models\Order::class, ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(\app\models\Post::class, ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts0()
    {
        return $this->hasMany(\app\models\Post::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(\app\models\Product::class, ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts0()
    {
        return $this->hasMany(\app\models\Product::class, ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses()
    {
        return $this->hasMany(\app\models\UserAddress::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserDeliveryAddresses()
    {
        return $this->hasMany(\app\models\UserDeliveryAddress::class, ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(static::class);
    }
     public static function findIdentity($id)
     {
          return self::findOne($id);
     }

     /**
      * {@inheritdoc}
      */
     public static function findIdentityByAccessToken($token, $type = null)
     {
          return self::find()->andWhere(['access_token' => $token])->one();
     }

     public static function findByName($name)
     {
          return self::find()->andWhere(['name' => $name])->one();
     }

     public function getId()
     {
          return $this->id;
     }

     /**
      * {@inheritdoc}
      */
     public function getAuthKey()
     {
          return $this->id;
     }

     /**
      * {@inheritdoc}
      */
     public function validateAuthKey($authKey)
     {
          return false;
     }


     /**
      * Validates password
      *
      * @param string $password password to validate
      * @return bool if password provided is valid for current user
      */
     public function validatePassword($password)
     {
          return Yii::$app->security->validatePassword($password, $this->password);
     }

     public function findByVerificationToken()
     {
          return self::findOne(['verification_token' => $this->verification_token]);
     }
}
