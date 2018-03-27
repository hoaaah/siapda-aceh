<?php

namespace app\modules\dataentry\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LspipEvaluasi;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * LspipEvaluasiSearch represents the model behind the search form about `app\models\LspipEvaluasi`.
 */
class LspipEvaluasiSearch extends LspipEvaluasi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'perwakilan_id', 'province_id', 'kat_spip', 'f1', 'f2', 'f3', 'f4', 'f5', 'f6', 'f7', 'f8', 'f9', 'f10', 'f11', 'f12', 'f13', 'f14', 'f15', 'f16', 'f17', 'f18', 'f19', 'f20', 'f21', 'f22', 'f23', 'f24', 'f25'], 'integer'],
            [['bulan', 'pemda_id', 'tahun', 'no_laporan', 'tgl_laporan', 'ket', 'user_id', 'created', 'updated'], 'safe'],
            [['nilai_spip'], 'number'],
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
        $query = LspipEvaluasi::find();

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
            'tgl_laporan' => $this->tgl_laporan,
            'nilai_spip' => $this->nilai_spip,
            'kat_spip' => $this->kat_spip,
            'f1' => $this->f1,
            'f2' => $this->f2,
            'f3' => $this->f3,
            'f4' => $this->f4,
            'f5' => $this->f5,
            'f6' => $this->f6,
            'f7' => $this->f7,
            'f8' => $this->f8,
            'f9' => $this->f9,
            'f10' => $this->f10,
            'f11' => $this->f11,
            'f12' => $this->f12,
            'f13' => $this->f13,
            'f14' => $this->f14,
            'f15' => $this->f15,
            'f16' => $this->f16,
            'f17' => $this->f17,
            'f18' => $this->f18,
            'f19' => $this->f19,
            'f20' => $this->f20,
            'f21' => $this->f21,
            'f22' => $this->f22,
            'f23' => $this->f23,
            'f24' => $this->f24,
            'f25' => $this->f25,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id])
            ->andFilterWhere(['like', 'tahun', $this->tahun])
            ->andFilterWhere(['like', 'no_laporan', $this->no_laporan])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
