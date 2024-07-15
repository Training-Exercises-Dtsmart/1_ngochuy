<?php

namespace app\models\query;

use app\models\Product as ModelsProduct;
use Product;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see \app\modules\models\v1\\app\modules\models\\app\modules\v1\models\Product
 * @method Product[] all($db = null)
 * @method Product one($db = null)
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    public function active(){

       return $this->andWhere(['product_status' => ModelsProduct::STATUS_ACTIVE]);
    }
}
