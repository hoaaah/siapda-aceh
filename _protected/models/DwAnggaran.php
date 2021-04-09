<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dw_anggaran".
 *
 * @property int $id
 * @property string $bulan
 * @property int $perwakilan_id
 * @property int $province_id
 * @property string $pemda_id
 * @property string|null $tanggal_pelaporan
 * @property string|null $coa
 * @property string|null $aplikasi
 * @property int|null $kd_riwayat
 * @property int|null $kd_urusan
 * @property int|null $kd_bidang
 * @property string|null $kd_skpd
 * @property string|null $kd_program
 * @property string|null $kd_kegiatan
 * @property string|null $kd_sub_kegiatan
 * @property int|null $kd_fungsi
 * @property int|null $kd_sub_fungsi
 * @property int|null $kd_rek_1
 * @property int|null $kd_rek_2
 * @property int|null $kd_rek_3
 * @property int|null $kd_rek_4
 * @property int|null $kd_rek_5
 * @property int|null $kd_rek_6
 * @property string|null $output
 * @property float|null $anggaran
 */
class DwAnggaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dw_anggaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id'], 'required'],
            [['perwakilan_id', 'province_id', 'kd_riwayat', 'kd_urusan', 'kd_bidang', 'kd_fungsi', 'kd_sub_fungsi', 'kd_rek_1', 'kd_rek_2', 'kd_rek_3', 'kd_rek_4', 'kd_rek_5', 'kd_rek_6'], 'integer'],
            [['tanggal_pelaporan', 'output'], 'safe'],
            [['anggaran'], 'number'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id', 'kd_program', 'kd_kegiatan', 'kd_sub_kegiatan'], 'string', 'max' => 5],
            [['coa'], 'string', 'max' => 50],
            [['aplikasi', 'kd_skpd'], 'string', 'max' => 100],
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
            'coa' => 'Coa',
            'aplikasi' => 'Aplikasi',
            'kd_riwayat' => 'Kd Riwayat',
            'kd_urusan' => 'Kd Urusan',
            'kd_bidang' => 'Kd Bidang',
            'kd_skpd' => 'Kd Skpd',
            'kd_program' => 'Kd Program',
            'kd_kegiatan' => 'Kd Kegiatan',
            'kd_sub_kegiatan' => 'Kd Sub Kegiatan',
            'kd_fungsi' => 'Kd Fungsi',
            'kd_sub_fungsi' => 'Kd Sub Fungsi',
            'kd_rek_1' => 'Kd Rek 1',
            'kd_rek_2' => 'Kd Rek 2',
            'kd_rek_3' => 'Kd Rek 3',
            'kd_rek_4' => 'Kd Rek 4',
            'kd_rek_5' => 'Kd Rek 5',
            'kd_rek_6' => 'Kd Rek 6',
            'output' => 'Output',
            'anggaran' => 'Anggaran',
        ];
    }
}
