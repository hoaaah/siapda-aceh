<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lspip_evaluasi".
 *
 * @property integer $id
 * @property string $bulan
 * @property integer $perwakilan_id
 * @property integer $province_id
 * @property string $pemda_id
 * @property string $tahun
 * @property string $no_laporan
 * @property string $tgl_laporan
 * @property string $nilai_spip
 * @property integer $kat_spip
 * @property integer $f1
 * @property integer $f2
 * @property integer $f3
 * @property integer $f4
 * @property integer $f5
 * @property integer $f6
 * @property integer $f7
 * @property integer $f8
 * @property integer $f9
 * @property integer $f10
 * @property integer $f11
 * @property integer $f12
 * @property integer $f13
 * @property integer $f14
 * @property integer $f15
 * @property integer $f16
 * @property integer $f17
 * @property integer $f18
 * @property integer $f19
 * @property integer $f20
 * @property integer $f21
 * @property integer $f22
 * @property integer $f23
 * @property integer $f24
 * @property integer $f25
 * @property string $ket
 * @property string $user_id
 * @property string $created
 * @property string $updated
 */
class LspipEvaluasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lspip_evaluasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'tahun', 'no_laporan'], 'required'],
            [['perwakilan_id', 'province_id', 'kat_spip', 'f1', 'f2', 'f3', 'f4', 'f5', 'f6', 'f7', 'f8', 'f9', 'f10', 'f11', 'f12', 'f13', 'f14', 'f15', 'f16', 'f17', 'f18', 'f19', 'f20', 'f21', 'f22', 'f23', 'f24', 'f25'], 'integer'],
            [['tgl_laporan', 'created', 'updated'], 'safe'],
            [['nilai_spip'], 'number'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
            [['tahun'], 'string', 'max' => 4],
            [['no_laporan', 'user_id'], 'string', 'max' => 50],
            [['ket'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bulan' => 'Bulan',
            'perwakilan_id' => 'Perwakilan ID',
            'province_id' => 'Province ID',
            'pemda_id' => 'Pemda ID',
            'tahun' => 'Tahun',
            'no_laporan' => 'No Laporan',
            'tgl_laporan' => 'Tgl Laporan',
            'nilai_spip' => 'Nilai Spip',
            'kat_spip' => 'Kat Spip',
            'f1' => 'F1',
            'f2' => 'F2',
            'f3' => 'F3',
            'f4' => 'F4',
            'f5' => 'F5',
            'f6' => 'F6',
            'f7' => 'F7',
            'f8' => 'F8',
            'f9' => 'F9',
            'f10' => 'F10',
            'f11' => 'F11',
            'f12' => 'F12',
            'f13' => 'F13',
            'f14' => 'F14',
            'f15' => 'F15',
            'f16' => 'F16',
            'f17' => 'F17',
            'f18' => 'F18',
            'f19' => 'F19',
            'f20' => 'F20',
            'f21' => 'F21',
            'f22' => 'F22',
            'f23' => 'F23',
            'f24' => 'F24',
            'f25' => 'F25',
            'ket' => 'Ket',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
