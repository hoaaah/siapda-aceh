<?php

namespace app\modules\dataentry\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lappds;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * LappdsSearch represents the model behind the search form about `app\models\Lappds`.
 */
class LappdsSearch extends Lappds
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'katc_id'], 'integer'],
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'tanggal', 'pihak_bantu', 'stat_id', 'ket', 'user_id', 'created', 'updated'], 'safe'],
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
        $query = Lappds::find();

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
            'katc_id' => $this->katc_id,
            'tanggal' => $this->tanggal,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'perwakilan_id', $this->perwakilan_id])
            ->andFilterWhere(['like', 'province_id', $this->province_id])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id])
            ->andFilterWhere(['like', 'pihak_bantu', $this->pihak_bantu])
            ->andFilterWhere(['like', 'stat_id', $this->stat_id])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
