<?php

namespace app\modules\dataentry\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lsimdas;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * LsimdasSearch represents the model behind the search form about `app\models\Lsimdas`.
 */
class LsimdasSearch extends Lsimdas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'use_keu', 'use_keu_penganggaran', 'use_keu_penatausahaan', 'use_keu_pelaporan', 'use_bmd', 'use_gaji', 'use_pendapatan', 'use_perencanaan'], 'integer'],
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'ket', 'user_id', 'created', 'updated', 'ver_keu', 'ver_bmd', 'ver_gaji', 'ver_pendapatan', 'ver_perencanaan'], 'safe'],
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
        $query = Lsimdas::find();

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
            'use_keu' => $this->use_keu,
            'use_keu_penganggaran' => $this->use_keu_penganggaran,
            'use_keu_penatausahaan' => $this->use_keu_penatausahaan,
            'use_keu_pelaporan' => $this->use_keu_pelaporan,
            'use_bmd' => $this->use_bmd,
            'use_gaji' => $this->use_gaji,
            'use_pendapatan' => $this->use_pendapatan,
            'use_perencanaan' => $this->use_perencanaan,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'perwakilan_id', $this->perwakilan_id])
            ->andFilterWhere(['like', 'province_id', $this->province_id])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'ver_keu', $this->ver_keu])
            ->andFilterWhere(['like', 'ver_bmd', $this->ver_bmd])
            ->andFilterWhere(['like', 'ver_gaji', $this->ver_gaji])
            ->andFilterWhere(['like', 'ver_pendapatan', $this->ver_pendapatan])
            ->andFilterWhere(['like', 'ver_perencanaan', $this->ver_perencanaan]);

        return $dataProvider;
    }
}
