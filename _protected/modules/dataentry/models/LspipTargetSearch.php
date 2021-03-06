<?php

namespace app\modules\dataentry\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LspipTarget;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
/**
 * LspipTargetSearch represents the model behind the search form about `app\models\LspipTarget`.
 */
class LspipTargetSearch extends LspipTarget
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'perwakilan_id', 'province_id'], 'integer'],
            [['bulan', 'pemda_id', 'tahun', 'kat_spip', 'ket', 'user_id', 'created', 'updated'], 'safe'],
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
        $query = LspipTarget::find();

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
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'pemda_id', $this->pemda_id])
            ->andFilterWhere(['like', 'tahun', $this->tahun])
            ->andFilterWhere(['like', 'kat_spip', $this->kat_spip])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
