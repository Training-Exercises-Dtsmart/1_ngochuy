<?php

namespace app\controllers;

use Yii;
use app\models\form\ProductForm;
use app\models\Product;
use app\models\search\ProductSearch;
use yii\web\Response;
use yii\db\Exception;

class ProductController extends Controller
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->json(true, [
            "products" => $dataProvider->getModels()
        ], "Success", 200);
    }

    /**
     * Creates a new Product model.
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $product = new ProductForm();

        if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {
            if ($product->save()) {
                return $this->json(true, ["product" => $product], "Create product successfully", 200);
            }
        }

        if (!$product->save()) {
            return $this->json(false, ["error" => $product->getErrors()], "Can't create product", 400);
        }

    }

    /**
     * Updates an existing Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $product = Product::find()->where(["id"=>$id])->one();
        $productForm = new ProductForm();

        if ($productForm->load(Yii::$app->request->post(), '')) {
            if (!$productForm->validate()) {
               return $this->josn(false, ["error" => $productForm->getErrors()], "Can't update product", 400);
            }
           
            $product->save();
        }
        return $this->json(true, ["product" => $product], "Update product successfully", 200);
    }

    /**
     * Deletes an existing Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $product = Product::find()->where(["id"=>$id])->one();
        if(!$product){
            return $this->json(false, [], "Product not found");
        }
        if(!$product->delete()){
            return $this->json(false, [], "Can't delete product");
        }
        return $this->json(true, [], 'Delete product successfully',200);
    }

   
    public function actionSearch(){
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->json(true, ["products" => $dataProvider->getModels()], "Find successfully", 200); 
    }
}