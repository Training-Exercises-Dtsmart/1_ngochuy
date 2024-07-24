<?php

namespace app\modules\controllers;

use app\models\Brand;
use app\modules\models\Brand as ModelsBrand;
use app\modules\models\HttpStatus;
use app\controllers\Controller;
use yii\web\NotFoundHttpException;
use app\modules\models\pagination\Pagination;


/**
 * BrandController implements the CRUD actions for Brand model.
 */
class BrandController extends Controller
{
   public $modelClass = 'modules\models\Brand';
   public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
   ];

    public function acitonIndex()
     {
        $orders = Brand::find();
        if (!$orders) {
             return $this->json(false, ['errors' => $orders->getErrors()], 'Brand not found', HttpStatus::NOT_FOUND);
        }

        $dataProvider = Pagination::getPagination($orders, 5, SORT_DESC);

        return $this->json(true, ["products" => $dataProvider], "Success", HttpStatus::OK);
   }



    /**
     * Creates a new Brand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Brand();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

   
    }

    /**
     * Updates an existing Brand model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

        
    // }

    /**
     * Deletes an existing Brand model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Brand::findOne(['id' => $id])) !== null) {
            return $this->json(true, ['data' => $model], 'Success', HttpStatus::OK);
        }

        return $this->json(false, [], 'The requested page does not exist.', HttpStatus::NOT_FOUND);
    }
}
