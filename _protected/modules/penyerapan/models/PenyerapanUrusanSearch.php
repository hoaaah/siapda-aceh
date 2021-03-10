<?php

namespace app\modules\penyerapan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PenyerapanUrusan;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * PenyerapanUrusanSearch represents the model behind the search form about `app\models\PenyerapanUrusan`.
 */
class PenyerapanUrusanSearch extends PenyerapanUrusan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'perwakilan_id', 'province_id', 'kd_urusan', 'kd_bidang'], 'integer'],
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
        $query = PenyerapanUrusan::find();

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
            'kd_urusan' => $this->kd_urusan,
            'kd_bidang' => $this->kd_bidang,
            'anggaran' => $this->anggaran,
            'realisasi' => $this->realisasi,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id]);

        return $dataProvider;
    }
}
