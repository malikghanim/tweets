<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tweet;

/**
 * TweetSearch represents the model behind the search form of `common\models\Tweet`.
 */
class TweetSearch extends Tweet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'keyword_id', 'user_id', 'country_id', 'created_at', 'updated_at'], 'integer'],
            [['country_name', 'city_name', 'location', 'coordinates', 'altitude', 'longtitude', 'description', 'tweet_owner', 'profile_image'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Tweet::find();

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
            'keyword_id' => $this->keyword_id,
            'user_id' => $this->user_id,
            'country_id' => $this->country_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'country_name', $this->country_name])
            ->andFilterWhere(['like', 'city_name', $this->city_name])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates])
            ->andFilterWhere(['like', 'altitude', $this->altitude])
            ->andFilterWhere(['like', 'longtitude', $this->longtitude])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tweet_owner', $this->tweet_owner])
            ->andFilterWhere(['like', 'profile_image', $this->profile_image]);

        return $dataProvider;
    }
}
