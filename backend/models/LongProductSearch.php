<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LongtailKeywords;

/**
 * LongtailKeywordsSearch represents the model behind the search form of `backend\models\LongtailKeywords`.
 */
class LongProductSearch extends LongtailKeywords
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['website_id', 'product_id'], 'integer'],
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
        $query = LongtailKeywords::find();

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
            'website_id' => $this->website_id,
            'product_id' => $this->product_id,
        ]);

        $query->select(['website_id', 'product_id'])->groupBy(['website_id', 'product_id']);

        return $dataProvider;
    }
}
