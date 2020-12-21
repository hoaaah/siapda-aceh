<?php

namespace app\modules\dataentry\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lkegiatans;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * LkegiatansSearch represents the model behind the search form about `app\models\Lkegiatans`.
 */
class LkegiatansSearch extends Lkegiatans
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'perpanjangan'], 'integer'],
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'kategori_id', 'kelompok_id', 'kegiatan_id', 'nama_kegiatan', 'no_st', 'tanggal_st', 'no_laporan', 'ket', 'user_id', 'created', 'updated', 'tanggal_lap'], 'safe'],
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
        $query = Lkegiatans::find();

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
            'tanggal_st' => $this->tanggal_st,
            'created' => $this->created,
            'updated' => $this->updated,
            'tanggal_lap' => $this->tanggal_lap,
            'perpanjangan' => $this->perpanjangan,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'perwakilan_id', $this->perwakilan_id])
            ->andFilterWhere(['like', 'province_id', $this->province_id])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id])
            ->andFilterWhere(['like', 'kategori_id', $this->kategori_id])
            ->andFilterWhere(['like', 'kelompok_id', $this->kelompok_id])
            ->andFilterWhere(['like', 'kegiatan_id', $this->kegiatan_id])
            ->andFilterWhere(['like', 'nama_kegiatan', $this->nama_kegiatan])
            ->andFilterWhere(['like', 'no_st', $this->no_st])
            ->andFilterWhere(['like', 'no_laporan', $this->no_laporan])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
