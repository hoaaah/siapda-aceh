<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penyerapan_rekening".
 *
 * @property int $id
 * @property string $bulan
 * @property int $perwakilan_id
 * @property int $province_id
 * @property string $pemda_id
 * @property string|null $tanggal_pelaporan
 * @property int|null $kd_rek_1
 * @property int|null $kd_rek_2
 * @property int|null $kd_rek_3
 * @property int|null $kd_rek_4
 * @property int|null $kd_rek_5
 * @property int|null $kd_rek_6
 * @property float|null $anggaran
 * @property float|null $realisasi
 */
class PenyerapanRekening extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penyerapan_rekening';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id'], 'required'],
            [['perwakilan_id', 'province_id', 'kd_rek_1', 'kd_rek_2', 'kd_rek_3', 'kd_rek_4', 'kd_rek_5', 'kd_rek_6'], 'integer'],
            [['tanggal_pelaporan'], 'safe'],
            [['anggaran', 'realisasi'], 'number'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bulan' => 'Bulan',
            'perwakilan_id' => 'Perwakilan ID',
            'province_id' => 'Province ID',
            'pemda_id' => 'Pemda ID',
            'tanggal_pelaporan' => 'Tanggal Pelaporan',
            'kd_rek_1' => 'Kd Rek 1',
            'kd_rek_2' => 'Kd Rek 2',
            'kd_rek_3' => 'Kd Rek 3',
            'kd_rek_4' => 'Kd Rek 4',
            'kd_rek_5' => 'Kd Rek 5',
            'kd_rek_6' => 'Kd Rek 6',
            'anggaran' => 'Anggaran',
            'realisasi' => 'Realisasi',
        ];
    }
}
