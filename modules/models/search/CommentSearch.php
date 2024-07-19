<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 10:30:41
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 10:35:01
 * @FilePath: modules/models/search/CommentSearch.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\models\search;

use app\modules\models\Comment;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class CommentSearch extends Comment
{
     /**
      * {@inheritdoc}
      */
     public function rules()
     {
          return [
               [['id', 'product_id', 'parent_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
               [['comment'], 'safe'],
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
          $query = Comment::find();

          // add conditions that should always apply here

          $dataProvider = new ActiveDataProvider([
               'query' => $query,
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
               'product_id' => $this->product_id,
               'parent_id' => $this->parent_id,
               'created_at' => $this->created_at,
               'updated_at' => $this->updated_at,
               'created_by' => $this->created_by,
          ]);

          $query->andFilterWhere(['like', 'comment', $this->comment]);

          return $dataProvider;
     }
}