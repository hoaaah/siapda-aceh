<?php

namespace app\modules\dataentry\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Llkpd;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * LlkpdSearch represents the model behind the search form about `app\models\Llkpd`.
 */
class LlkpdSearch extends Llkpd
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'perwakilan_id', 'province_id', 'stat_lk'], 'integer'],
            [['bulan', 'pemda_id', 'tanggal', 'pihak_bantu_susun', 'pihak_bantu_reviu', 'opini_id', 'ket', 'user_id', 'created', 'updated'], 'safe'],
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
        $query = Llkpd::find();

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
            'perwakilan_id' => $this->perwakilan_id,
            'province_id' => $this->province_id,
            'stat_lk' => $this->stat_lk,
            'tanggal' => $this->tanggal,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id])
            ->andFilterWhere(['like', 'pihak_bantu_susun', $this->pihak_bantu_susun])
            ->andFilterWhere(['like', 'pihak_bantu_reviu', $this->pihak_bantu_reviu])
            ->andFilterWhere(['like', 'opini_id', $this->opini_id])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
