<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

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
            [['bulan', /*'perwakilan_id', 'province_id',*/ 'pemda_id', 'tahun', 'no_laporan'], 'required'],
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
            'f1' => 'F1 Penegakan Integritas dan Nilai Etika',
            'f2' => 'F2 Komitmen Terhadap Kompetensi',
            'f3' => 'F3 Kepemimpinan yang Kondusif',
            'f4' => 'F4 Struktur Organisasi Sesuai Kebutuhan',
            'f5' => 'F5 Pendelegasian Wewenang dan Tanggung Jawab yang Tepat',
            'f6' => 'F6 Penyusunan dan Penerapan Kebijakan yang Sehat tentang Pembinaan SDM',
            'f7' => 'F7 Perwujudan Peran APIP yang Efektif',
            'f8' => 'F8 Hubungan Kerja yang Baik dengan Instansi Pemerintah Terkait',
            'f9' => 'F9 Identifikasi Risiko',
            'f10' => 'F10 Analisis Risiko',
            'f11' => 'F11 Reviu Kinerja',
            'f12' => 'F12 Pembinaan SDM',
            'f13' => 'F13 Pengendalian atas Pengelolaan Sistem Informasi',
            'f14' => 'F14 Pengendalian Fisik atas Aset',
            'f15' => 'F15 Penetapan dan Reviu Indikator',
            'f16' => 'F16 Pemisahan Fungsi',
            'f17' => 'F17 Otorisasi Transaksi dan Kejadian Penting',
            'f18' => 'F18 Pencatatan yang Akurat dan Tepat Waktu',
            'f19' => 'F19 Pembatasan Akses atas Sumber Daya',
            'f20' => 'F20 Akuntabilitas Pencatatan dan Sumber Daya',
            'f21' => 'F21 Dokumentasi yang Baik atas SPI serta Transaksi dan Kejadian Penting',
            'f22' => 'F22 Informasi',
            'f23' => 'F23 Penyelenggaraan Komunikasi yang Efektif',
            'f24' => 'F24 Pemantauan Berkelanjutan',
            'f25' => 'F25 Evaluasi Terpisah',
            'ket' => 'Ket',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ],
        ];
    }

    public function beforeSave($insert){		
		if (parent::beforeSave($insert) && $this->pemda_id) {
            $pemda = RefPemda::findOne($this->pemda_id);
            $this->perwakilan_id = $pemda->perwakilan_id;
            $this->province_id = $pemda->province_id;
            
            // perhitungan skor
            $skor = 0;
            $skor += $this->f1 * 0.0375;
            $skor += $this->f2 * 0.0375;
            $skor += $this->f3 * 0.0375;
            $skor += $this->f4 * 0.0375;
            $skor += $this->f5 * 0.0375;
            $skor += $this->f6 * 0.0375;
            $skor += $this->f7 * 0.0375;
            $skor += $this->f8 * 0.0375;
            $skor += $this->f9 * 0.1;
            $skor += $this->f10 * 0.1;
            $skor += $this->f11 * 0.0227;
            $skor += $this->f12 * 0.0227;
            $skor += $this->f13 * 0.0227;
            $skor += $this->f14 * 0.0227;
            $skor += $this->f15 * 0.0227;
            $skor += $this->f16 * 0.0227;
            $skor += $this->f17 * 0.0227;
            $skor += $this->f18 * 0.0227;
            $skor += $this->f19 * 0.0227;
            $skor += $this->f20 * 0.0227;
            $skor += $this->f21 * 0.0227;
            $skor += $this->f22 * 0.05;
            $skor += $this->f23 * 0.05;
            $skor += $this->f24 * 0.075;
            $skor += $this->f25 * 0.075;
            $this->nilai_spip = $skor;

            // we mapped spip value if exist
            if($this->nilai_spip){
                switch ($this->nilai_spip) {
                    case ($this->nilai_spip < 1):
                        $this->kat_spip = 1;
                        break;
                    case ($this->nilai_spip < 2):
                        $this->kat_spip = 2;
                        break;
                    case ($this->nilai_spip < 3):
                        $this->kat_spip = 3;
                        break;
                    case ($this->nilai_spip < 4):
                        $this->kat_spip = 4;
                        break;
                    case ($this->nilai_spip < 4.5):
                        $this->kat_spip = 5;
                        break;
                    case ($this->nilai_spip < 5):
                        $this->kat_spip = 6;
                        break;
                    
                    default:
                        # code...
                        break;
                } 
            }   
            return true;
        }
        return false;
    }

    public function getSpip()
    {
        switch ($this->nilai_spip) {
            case ($this->nilai_spip < 1):
                return "Belum Ada";
                break;
            case ($this->nilai_spip < 2):
                return "Rintisan";
                break;
            case ($this->nilai_spip < 3):
                return "Intuitif";
                break;
            case ($this->nilai_spip < 4):
                return "Terdefinisi";
                break;
            case ($this->nilai_spip < 4.5):
                return "Terkelola dan Terukur";
                break;
            case ($this->nilai_spip < 5):
                return "Optimum";
                break;
            
            default:
                # code...
                break;
        } 
    }

}
