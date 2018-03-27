<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "lspip_target".
 *
 * @property integer $id
 * @property string $bulan
 * @property integer $perwakilan_id
 * @property integer $province_id
 * @property string $pemda_id
 * @property string $tahun
 * @property string $kat_spip
 * @property string $ket
 * @property string $user_id
 * @property string $created
 * @property string $updated
 */
class LspipTarget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lspip_target';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan',/*'perwakilan_id', 'province_id',*/ 'pemda_id', 'tahun'], 'required'],
            [['perwakilan_id', 'province_id'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
            [['tahun'], 'string', 'max' => 4],
            [['kat_spip'], 'string', 'max' => 2],
            [['ket'], 'string', 'max' => 255],
            [['user_id'], 'string', 'max' => 50],
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
            'kat_spip' => 'Kat Spip',
            'ket' => 'Ket',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    public function categorySpip(){
        return [
            1 => "0 Belum Ada",
            2 => "1 Rintisan",
            3 => '2 Intuitif',
            4 => "3 Terdefinisi",
            5 => "4 Terkelola dan Terukur",
            6 => "5 Optimum",
        ];
    }

    public function getSpip()
    {
        switch ($this->kat_spip) {
            case  1:
                return "0 Belum Ada";
                break;
            case  2:
                return "1 Rintisan";
                break;
            case  3:
                return "2 Intuitif";
                break;
            case  4:
                return "3 Terdefinisi";
                break;
            case  5:
                return "4 Terkelola dan Terukur";
                break;
            case  6:
                return "5 Optimum";
                break;
            
            default:
                # code...
                break;
        } 
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
            return true;
        }
        return false;
    }
}
