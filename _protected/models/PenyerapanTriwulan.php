<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penyerapan_triwulan".
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
 * @property float|null $anggaran_tw1
 * @property float|null $anggaran_tw2
 * @property float|null $anggaran_tw3
 * @property float|null $anggaran_tw4
 * @property float|null $realisasi_tw1
 * @property float|null $realisasi_tw2
 * @property float|null $realisasi_tw3
 * @property float|null $realisasi_tw4
 */
class PenyerapanTriwulan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penyerapan_triwulan';
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
            [['anggaran_tw1', 'anggaran_tw2', 'anggaran_tw3', 'anggaran_tw4', 'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3', 'realisasi_tw4'], 'number'],
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
            'anggaran_tw1' => 'Anggaran Tw1',
            'anggaran_tw2' => 'Anggaran Tw2',
            'anggaran_tw3' => 'Anggaran Tw3',
            'anggaran_tw4' => 'Anggaran Tw4',
            'realisasi_tw1' => 'Realisasi Tw1',
            'realisasi_tw2' => 'Realisasi Tw2',
            'realisasi_tw3' => 'Realisasi Tw3',
            'realisasi_tw4' => 'Realisasi Tw4',
        ];
    }
}
