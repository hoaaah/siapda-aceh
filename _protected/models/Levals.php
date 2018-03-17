<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "levals".
 *
 * @property integer $id
 * @property string $bulan
 * @property integer $perwakilan_id
 * @property integer $province_id
 * @property string $pemda_id
 * @property string $tahun
 * @property string $nilai_spip
 * @property string $kat_spip
 * @property string $nilai_sakip
 * @property string $kat_sakip
 * @property string $nilai_lppd
 * @property string $kat_lppd
 * @property string $ket
 * @property string $user_id
 * @property string $created
 * @property string $updated
 *
 * @property RefPemda $pemda
 */
class Levals extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'levals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'tahun'], 'required'],
            [['perwakilan_id', 'province_id'], 'integer'],
            [['nilai_spip', 'nilai_sakip', 'nilai_lppd'], 'number'],
            [['created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
            [['tahun'], 'string', 'max' => 4],
            [['kat_spip', 'kat_sakip', 'kat_lppd'], 'string', 'max' => 2],
            [['ket'], 'string', 'max' => 255],
            [['user_id'], 'string', 'max' => 50],
            [['pemda_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefPemda::className(), 'targetAttribute' => ['pemda_id' => 'id']],
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
            'nilai_spip' => 'Nilai Spip',
            'kat_spip' => 'Kat Spip',
            'nilai_sakip' => 'Nilai Sakip',
            'kat_sakip' => 'Kat Sakip',
            'nilai_lppd' => 'Nilai Lppd',
            'kat_lppd' => 'Kat Lppd',
            'ket' => 'Ket',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
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

    public function getSakip()
    {
        return $this->hasOne(RefKategoriLakip::className(), ['id' => 'kat_sakip']);
    }

    public function getLppd()
    {
        return $this->hasOne(RefKategoriEvaluasi::className(), ['id' => 'kat_lppd']);
    }

    public function beforeSave($insert){		
		if (parent::beforeSave($insert)) {

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

            // we mapped sakip value if exist
            if($this->nilai_sakip){
                switch ($this->nilai_sakip) {
                    case ($this->nilai_sakip <= 30):
                        $this->kat_sakip = 1;
                        break;
                    case ($this->nilai_sakip <= 50):
                        $this->kat_sakip = 2;
                        break;
                    case ($this->nilai_sakip <= 65):
                        $this->kat_sakip = 3;
                        break;
                    case ($this->nilai_sakip <= 75):
                        $this->kat_sakip = 4;
                        break;
                    case ($this->nilai_sakip <= 85):
                        $this->kat_sakip = 5;
                        break;
                    case ($this->nilai_sakip <= 100):
                        $this->kat_sakip = 6;
                        break;
                    
                    default:
                        # code...
                        break;
                } 
            }            

            // we mapped lppd value if exist
            if($this->nilai_lppd){
                switch ($this->nilai_lppd) {
                    case ($this->nilai_lppd <= 1):
                        $this->kat_lppd = 1;
                        break;
                    case ($this->nilai_lppd <= 2):
                        $this->kat_lppd = 2;
                        break;
                    case ($this->nilai_lppd <= 3):
                        $this->kat_lppd = 3;
                        break;
                    case ($this->nilai_lppd <= 4):
                        $this->kat_lppd = 4;
                        break;
                    
                    default:
                        # code...
                        break;
                } 
            }
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }
}
