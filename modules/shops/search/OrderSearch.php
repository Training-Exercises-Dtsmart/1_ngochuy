<?php

namespace app\modules\shops\search;


use app\modules\shops\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
     /**
      * {@inheritdoc}
      */
     public function rules()
     {
          return [
               [['id', 'quantity', 'status', 'customer_id'], 'integer'],
               [['total_amount'], 'number'],
               [['order_date', 'payment_method', 'shipping_address', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
          ];
     }

     /**
      * {@inheritdoc}
      */
     public function scenarios()
     {
          // bypass scenarios() implementation in the parent class
          return Model::scenarios();
     }

     /**
      * Creates data provider instance with search query applied
      *
      * @param array $params
      *
      * @return ActiveDataProvider
      */
     public function search($params)
     {
          $query = Order::find();

          // add conditions that should always apply here

          $dataProvider = new ActiveDataProvider([
               'query' => $query,
               'pagination' => [
                    'pageSize' => 2,
               ],
               'sort' => [
                    'defaultOrder' => ['created_at' => SORT_DESC],
                    'attributes' => [
                         'created_at' => [
                              'desc' => ['created_at' => SORT_DESC],
                         ],
                         'id' => ['id' => SORT_DESC],
                    ],
                    'enableMultiSort' => true,
               ]
          ]);

          $this->load($params);

          if (!$this->validate()) {
               // uncomment the following line if you do not want to return any records when validation fails
               // $query->where('0=1');
               return $dataProvider;
          }

          // grid filtering conditions
          $query->andFilterWhere([
               'id' => $this->id,
               'quantity' => $this->quantity,
               'total_amount' => $this->total_amount,
               'order_date' => $this->order_date,
               'status' => $this->status,
               'customer_id' => $this->customer_id,
               'created_at' => $this->created_at,
               'updated_at' => $this->updated_at,
               'deleted_at' => $this->deleted_at,
          ]);

          $query->andFilterWhere(['like', 'payment_method', $this->payment_method])
               ->andFilterWhere(['like', 'shipping_address', $this->shipping_address]);

          return $dataProvider;
     }
}
