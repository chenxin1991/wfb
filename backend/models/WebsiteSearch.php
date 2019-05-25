<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Website;

/**
 * WebsiteSearch represents the model behind the search form of `backend\models\Website`.
 */
class WebsiteSearch extends Website
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'platform_id', 'industry_id', 'site_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'api_key'], 'safe'],
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
        $query = Website::find();

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
            'platform_id' => $this->platform_id,
            'industry_id' => $this->industry_id,
            'site_id' => $this->site_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'api_key', $this->api_key]);

        $query->orderBy('created_at desc');
        
        return $dataProvider;
    }
}
