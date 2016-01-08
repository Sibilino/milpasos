<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pass;

/**
 * PassSearch represents the model behind the search form about `app\models\Pass`.
 */
class PassSearch extends Pass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'event_id'], 'integer'],
            [['price'], 'number'],
            [['full'], 'boolean'],
            [['description', 'available_from', 'available_to', 'full'], 'safe'],
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
        $query = Pass::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'available_from' => $this->available_from,
            'available_to' => $this->available_to,
            'full' => $this->full,
            'event_id' => $this->event_id,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
