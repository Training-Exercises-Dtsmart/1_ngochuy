<?php

namespace app\controllers;

use app\models\form\ProductForm;
use app\models\Product;
use app\models\ProductSearch;
use Yii;

use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\db\Exception;
use yii\rest\Serializer;

class ProductController extends Controller
{
   /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return [
            'status' => 'success',
            'data' => $dataProvider->getModels(),
            'pagination' => [
                'totalCount' => $dataProvider->getTotalCount(),
                'pageCount' => $dataProvider->pagination->getPageCount(),
                'currentPage' => $dataProvider->pagination->getPage() + 1,
                'perPage' => $dataProvider->pagination->getPageSize(),
            ],
        ];
    }

    /**
     * Lists Product models per page.
     * @return mixed
     */
    public function actionGetPerPage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dataProvider = new ActiveDataProvider([
            'query' => Product::find(),
            'pagination' => [
                'pageSize' => 3, 
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ],
        ]);

        $serializer = new Serializer();
        $data = $serializer->serialize($dataProvider);

        return [
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'totalCount' => $dataProvider->getTotalCount(),
                'pageCount' => $dataProvider->pagination->getPageCount(),
                'currentPage' => $dataProvider->pagination->getPage() + 1,
                'perPage' => $dataProvider->pagination->getPageSize(),
            ],
        ];
    }

    /**
     * Creates a new Product model.
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = new Product();

        if ($product->load(Yii::$app->request->post()) && $product->validate() && $product->save()) {
            return [
                'status' => 'success',
                'data' => $product,
                'message' => 'Create successfully!',
            ];
        } else {
            Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'errors' => $product->getErrors(),
                'message' => "Can't create product",
            ];
        }
    }

    /**
     * Updates an existing Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = $this->findModel($id);
        $productForm = new ProductForm();

        if ($productForm->load(Yii::$app->request->post(), '') && $product->load($productForm->attributes, '') && $product->save()) {
            return [
                'status' => 'success',
                'data' => $product,
            ];
        } else {
            Yii::$app->response->statusCode = 422;
            return [
                'status' => 'error',
                'errors' => $productForm->getErrors(),
                'message' => 'Failed to update the product.',
            ];
        }
    }

    /**
     * Deletes an existing Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = $this->findModel($id);

        if ($product->delete()) {
            return [
                'status' => 'success',
                'message' => 'Product deleted successfully.',
            ];
        } else {
            Yii::$app->response->statusCode = 500;
            return [
                'status' => 'error',
                'message' => 'Failed to delete the product.',
            ];
        }
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionDetail($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = $this->findModel($id);

        return [
            'status' => 'success',
            'data' => $product,
            'message' => 'Get detail product successfully!',
        ];
    }

    /**
     * Finds the Product model based on its primary key value.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested product does not exist.');
    }
}