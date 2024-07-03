<?php

namespace app\models;

use app\models\Product as ModelsProduct;
use Product;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 * @method Product[] all($db = null)
 * @method Product one($db = null)
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    public function active(){

       return $this->andWhere(['product_status' => ModelsProduct::STATUS_ACTIVE]);
    }
}
