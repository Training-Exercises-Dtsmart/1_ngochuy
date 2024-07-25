<?php

namespace app\modules\shops\search;

use app\models\PaymentType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PaymentTypeSearch represents the model behind the search form of `app\models\PaymentType`.
 */
class PaymentTypeSearch extends PaymentType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'commission', 'active', 'payment_available', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['name', 'class', 'params', 'logo'], 'safe'],
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
        $query = PaymentType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'pagination' => [
                  'pageSize' => 20,
             ],
             'sort' => [
                  'defaultOrder' => ['id' => SORT_ASC],
             ],
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
            'commission' => $this->commission,
            'active' => $this->active,
            'payment_available' => $this->payment_available,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}
