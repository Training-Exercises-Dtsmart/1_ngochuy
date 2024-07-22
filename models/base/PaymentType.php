<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;
use yii\helpers\ArrayHelper;
use \app\models\PaymentTypeQuery;

/**
 * This is the base-model class for table "payment_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $logo
 * @property integer $status
 */
abstract class PaymentType extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $parentRules = parent::rules();
        return ArrayHelper::merge($parentRules, [
            [['status'], 'integer'],
            [['name', 'description', 'logo'], 'string', 'max' => 255]
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
            'description' => 'Description',
            'logo' => 'Logo',
            'status' => 'Status',
        ]);
    }

    /**
     * @inheritdoc
     * @return PaymentTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentTypeQuery(static::class);
    }
}
