<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;


/**
 * This is the model class for table "lsimdas".
 *
 * @property integer $id
 * @property string $bulan
 * @property string $perwakilan_id
 * @property string $province_id
 * @property string $pemda_id
 * @property integer $use_keu
 * @property integer $use_keu_penganggaran
 * @property integer $use_keu_penatausahaan
 * @property integer $use_keu_pelaporan
 * @property integer $use_bmd
 * @property integer $use_gaji
 * @property integer $use_pendapatan
 * @property integer $use_perencanaan
 * @property string $ket
 * @property string $user_id
 * @property string $created
 * @property string $updated
 * @property string $ver_keu
 * @property string $ver_bmd
 * @property string $ver_gaji
 * @property string $ver_pendapatan
 * @property string $ver_perencanaan
 *
 * @property RefPemda $pemda
 */
class Lsimdas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lsimdas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id'], 'required'],
            [['use_keu', 'use_keu_penganggaran', 'use_keu_penatausahaan', 'use_keu_pelaporan', 'use_bmd', 'use_gaji', 'use_pendapatan', 'use_perencanaan', 'use_cms'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['perwakilan_id', 'province_id'], 'integer', 'max' => 2],
            [['pemda_id'], 'string', 'max' => 5],
            [['ket'], 'string', 'max' => 255],
            [['user_id'], 'string', 'max' => 4],
            [['ver_keu', 'ver_bmd', 'ver_gaji', 'ver_pendapatan', 'ver_perencanaan'], 'string', 'max' => 15],
            [['perwakilan_id', 'province_id', 'pemda_id', 'bulan'], 'unique', 'targetAttribute' => ['perwakilan_id', 'province_id', 'pemda_id', 'bulan'], 'message' => 'The combination of Bulan, Perwakilan ID, Province ID and Pemda ID has already been taken.'],
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
            'use_keu' => 'Simda Keu',
            'use_keu_penganggaran' => 'Simda Keu pada Penganggaran',
            'use_keu_penatausahaan' => 'Simda Keu pada Penatausahaan',
            'use_keu_pelaporan' => 'Simda Keu pada Pelaporan',
            'use_bmd' => 'Simda BMD',
            'use_gaji' => 'Simda Gaji',
            'use_pendapatan' => 'Simda Pendapatan',
            'use_perencanaan' => 'Simda Perencanaan',
            'use_cms' => 'CMS Online',
            'ket' => 'Ket',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
            'ver_keu' => 'Ver Keu',
            'ver_bmd' => 'Ver Bmd',
            'ver_gaji' => 'Ver Gaji',
            'ver_pendapatan' => 'Ver Pendapatan',
            'ver_perencanaan' => 'Ver Perencanaan',
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
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }
}
