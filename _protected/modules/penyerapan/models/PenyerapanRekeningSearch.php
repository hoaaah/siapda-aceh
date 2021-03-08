<?php

namespace app\modules\penyerapan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PenyerapanRekening;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * PenyerapanRekeningSearch represents the model behind the search form about `app\models\PenyerapanRekening`.
 */
class PenyerapanRekeningSearch extends PenyerapanRekening
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'perwakilan_id', 'province_id', 'kd_rek_1', 'kd_rek_2', 'kd_rek_3', 'kd_rek_4', 'kd_rek_5', 'kd_rek_6'], 'integer'],
            [['bulan', 'pemda_id', 'tanggal_pelaporan'], 'safe'],
            [['anggaran', 'realisasi'], 'number'],
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
        $query = PenyerapanRekening::find();

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
            'tanggal_pelaporan' => $this->tanggal_pelaporan,
            'kd_rek_1' => $this->kd_rek_1,
            'kd_rek_2' => $this->kd_rek_2,
            'kd_rek_3' => $this->kd_rek_3,
            'kd_rek_4' => $this->kd_rek_4,
            'kd_rek_5' => $this->kd_rek_5,
            'kd_rek_6' => $this->kd_rek_6,
            'anggaran' => $this->anggaran,
            'realisasi' => $this->realisasi,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id]);

        return $dataProvider;
    }
}
