<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use \app\models\AuthAssignmentQuery;

/**
 * This is the base-model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property integer $user_id
 * @property integer $created_at
 *
 * @property \app\models\AuthItem $itemName
 * @property \app\models\User $user
 */
abstract class AuthAssignment extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_assignment';
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
            [['item_name', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
            [['item_name'], 'unique'],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\AuthItem::class, 'targetAttribute' => ['item_name' => 'name']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::class, 'targetAttribute' => ['user_id' => 'id']]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'item_name' => 'Item Name',
            'created_at' => 'Created At',
            'user_id' => 'User ID',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(\app\models\AuthItem::class, ['name' => 'item_name']);
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
     * @return AuthAssignmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthAssignmentQuery(static::class);
    }
}
