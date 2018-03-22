<?php

namespace app\modules\dataentry\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LdanadesaAlokasi;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * LdanadesaAlokasiSearch represents the model behind the search form about `app\models\LdanadesaAlokasi`.
 */
class LdanadesaAlokasiSearch extends LdanadesaAlokasi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bulan', 'perwakilan_id', 'province_id', 'pendapatan_desa_id', 'jumlah_desa', 'user_id'], 'integer'],
            [['tahun', 'pemda_id', 'created', 'updated'], 'safe'],
            [['nilai'], 'number'],
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
        $query = LdanadesaAlokasi::find();

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
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'perwakilan_id' => $this->perwakilan_id,
            'province_id' => $this->province_id,
            'pendapatan_desa_id' => $this->pendapatan_desa_id,
            'jumlah_desa' => $this->jumlah_desa,
            'nilai' => $this->nilai,
            'user_id' => $this->user_id,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'pemda_id', $this->pemda_id]);

        return $dataProvider;
    }
}
