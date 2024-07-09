<?php

namespace app\modules\models\search;
use app\modules\models\Post;
use yii\data\ActiveDataProvider;

class PostSearch extends Post{
    public $keyword;
    public $name;
    public function rules(){
        return [
            [['id', 'category_post_id', 'user_id'], 'integer'],
            [['title', 'content', 'keyword'], 'safe']
        ];
    }

    public function search($params){
        $query = Post::find()->joinWith('category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 2,
            ],
        ]);

        $this->load($params);
        if(!$this->validate()){
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);


        $query->andFilterWhere(["or", ["LIKE", "title", $this->keyword], 
            ["LIKE", "name", $this->name]]);

        return $dataProvider;

    }   
}
