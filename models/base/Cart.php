<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use \app\models\CartQuery;

/**
 * This is the base-model class for table "cart".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $qty
 * @property integer $delivery_types_id
 * @property integer $status
 * @property integer $created_at
 *
 * @property \app\models\DeliveryType $deliveryTypes
 * @property \app\models\Product $product
 * @property \app\models\User $user
 */
abstract class Cart extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
                                    'updatedAtAttribute' => false,
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
            [['user_id', 'product_id', 'qty', 'delivery_types_id', 'status'], 'integer'],
            [['delivery_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\DeliveryType::class, 'targetAttribute' => ['delivery_types_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::class, 'targetAttribute' => ['user_id' => 'id']]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'qty' => 'Qty',
            'delivery_types_id' => 'Delivery Types ID',
            'created_at' => 'Created At',
            'status' => 'Status',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryTypes()
    {
        return $this->hasOne(\app\models\DeliveryType::class, ['id' => 'delivery_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\app\models\Product::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return CartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CartQuery(static::class);
    }
}
