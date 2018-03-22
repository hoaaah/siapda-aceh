<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;


/**
 * This is the model class for table "ldanadesa_alokasi".
 *
 * @property integer $id
 * @property string $tahun
 * @property integer $bulan
 * @property integer $perwakilan_id
 * @property integer $province_id
 * @property string $pemda_id
 * @property integer $pendapatan_desa_id
 * @property integer $jumlah_desa
 * @property string $nilai
 * @property integer $user_id
 * @property string $created
 * @property string $updated
 */
class LdanadesaAlokasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ldanadesa_alokasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun', 'bulan', /*'perwakilan_id', 'province_id',*/ 'pemda_id', 'pendapatan_desa_id'], 'required'],
            [['tahun', 'created', 'updated'], 'safe'],
            [['bulan', 'perwakilan_id', 'province_id', 'pendapatan_desa_id', 'jumlah_desa', 'user_id'], 'integer'],
            [['nilai'], 'number'],
            [['pemda_id'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'perwakilan_id' => 'Perwakilan ID',
            'province_id' => 'Province ID',
            'pemda_id' => 'Pemda ID',
            'pendapatan_desa_id' => 'Pendapatan Desa ID',
            'jumlah_desa' => 'Jumlah Desa',
            'nilai' => 'Nilai',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
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

    public function getPendapatanDesa()
    {
        return $this->hasOne(RefPendapatanDesa::className(), ['id' => 'pendapatan_desa_id']);
    }
    
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }
}
