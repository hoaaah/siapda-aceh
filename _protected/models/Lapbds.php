<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

class Lapbds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lapbds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'no_apbd', 'stat_id', 'pihak_bantu'], 'required'],
            [['perwakilan_id', 'province_id'], 'integer'],
            [['tanggal', 'created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
            [['no_apbd', 'pihak_bantu'], 'string', 'max' => 20],
            [['stat_id'], 'string', 'max' => 2],
            [['ket'], 'string', 'max' => 255],
            [['user_id'], 'string', 'max' => 3],
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
            'no_apbd' => 'No Apbd',
            'tanggal' => 'Tanggal',
            'stat_id' => 'Stat ID',
            'pihak_bantu' => 'Pihak Bantu',
            'ket' => 'Ket',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }

    public function getBantuSusun()
    {
        return $this->hasOne(RefBantuan::className(), ['id' => 'pihak_bantu']);
    }

    public function getStatus()
    {
        if($this->stat_id == 1) return "TW";
        return "TTW";
    }
}
