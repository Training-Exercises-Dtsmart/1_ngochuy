<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use app\models\PlaceQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "places".
 *
 * @property integer $id
 * @property string $name
 *
 * @property \app\models\UserDeliveryAddress[] $userDeliveryAddresses
 */
abstract class Place extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $parentRules = parent::rules();
        return ArrayHelper::merge($parentRules, [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
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
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserDeliveryAddresses()
    {
        return $this->hasMany(\app\models\UserDeliveryAddress::class, ['place_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PlaceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlaceQuery(static::class);
    }
}
