<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penyerapan_urusan".
 *
 * @property int $id
 * @property string $bulan
 * @property int $perwakilan_id
 * @property int $province_id
 * @property string $pemda_id
 * @property string|null $tanggal_pelaporan
 * @property int|null $kd_urusan
 * @property int|null $kd_bidang
 * @property float|null $anggaran
 * @property float|null $realisasi
 */
class PenyerapanUrusan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penyerapan_urusan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id'], 'required'],
            [['perwakilan_id', 'province_id', 'kd_urusan', 'kd_bidang'], 'integer'],
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
            'kd_urusan' => 'Kd Urusan',
            'kd_bidang' => 'Kd Bidang',
            'anggaran' => 'Anggaran',
            'realisasi' => 'Realisasi',
        ];
    }
}
