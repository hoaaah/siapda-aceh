<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "lprofil_pemda".
 *
 * @property integer $id
 * @property string $bulan
 * @property integer $perwakilan_id
 * @property integer $province_id
 * @property string $pemda_id
 * @property string $created
 * @property string $updated
 * @property string $luas_wilayah
 * @property string $jumlah_penduduk
 * @property integer $tahun_politik
 */
class LprofilPemda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lprofil_pemda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id'], 'required'],
            [['perwakilan_id', 'province_id', 'tahun_politik'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['luas_wilayah', 'jumlah_penduduk'], 'number'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
            [['perwakilan_id', 'province_id', 'pemda_id', 'bulan'], 'unique', 'targetAttribute' => ['perwakilan_id', 'province_id', 'pemda_id', 'bulan'], 'message' => 'The combination of Bulan, Perwakilan ID, Province ID and Pemda ID has already been taken.'],
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
            'created' => 'Created',
            'updated' => 'Updated',
            'luas_wilayah' => 'Luas Wilayah (dalam km2)',
            'jumlah_penduduk' => 'Jumlah Penduduk (dalam ribu)',
            'tahun_politik' => 'Tahun Politik',
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
        ];
    }
    
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }
}
