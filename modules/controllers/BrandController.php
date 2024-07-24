<?php

namespace app\modules\controllers;


use Yii;
use app\models\Brand;
use app\modules\models\Brand as ModelsBrand;
use app\modules\models\form\BrandForm;
use app\modules\models\HttpStatus;
use app\controllers\Controller;
use yii\web\NotFoundHttpException;
use app\modules\models\pagination\Pagination;


/**
 * BrandController implements the CRUD actions for Brand model.
 */
class BrandController extends Controller
{
     public function actionIndex()
     {
          $brands = ModelsBrand::find();
          if (!$brands) {
               return $this->json(false, ['errors' => $brands->getErrors()], 'Brand not found', HttpStatus::NOT_FOUND);
          }

          $dataProvider = Pagination::getPagination($brands, 10, SORT_DESC);

          return $this->json(true, ["products" => $dataProvider], "Success", HttpStatus::OK);
     }

     public function actionCreate()
     {
          $brandForm = new BrandForm();
          $brandForm->load(Yii::$app->request->post(), '');
          if (!$brandForm->validate() || !$brandForm->saveBrand()) {
               return $this->json(false, ['errors' => $brandForm->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
          }
          return $this->json(true, ["product" => $brandForm], "Create brand successfully", HttpStatus::OK);

     }

      public function actionUpdate($id)
      {
           $brandForm = BrandForm::find()->where(['id' => $id])->one();
           if (!$brandForm) {
                return $this->json(false, [], 'Brand not found', HttpStatus::NOT_FOUND);
           }
           $brandForm->load(Yii::$app->request->post());
           if ($brandForm->validate() && $brandForm->save()) {
                return $this->json(true, ['brand' => $brandForm], 'Update brand successfully');
           }
           return $this->json(false, ['errors' => $brandForm->getErrors()], "Can't update brand", HttpStatus::BAD_REQUEST);
      }


     public function actionDelete($id)
     {
          $brand = BrandForm::find()->where(['id' => $id])->one();

          if (empty($brand)) {
               return $this->json(false, [], "Brand not found", HttpStatus::NOT_FOUND);
          }

          $brand->load(Yii::$app->request->post(), '');

          if (!$brand->delete()) {
               return $this->json(false, ['errors' => $brand->getErrors()], "Can't delete product", HttpStatus::BAD_REQUEST);
          }

          return $this->json(true, [], 'Delete brand successfully', HttpStatus::OK);

     }


     protected function findModel($id)
     {
          if (($model = \app\modules\models\Brand::findOne(['id' => $id])) !== null) {
               return $this->json(true, ['data' => $model], 'Success', HttpStatus::OK);
          }

          return $this->json(false, [], 'The requested page does not exist.', HttpStatus::NOT_FOUND);
     }
}
