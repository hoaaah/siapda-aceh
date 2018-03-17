<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

class Llkpd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'llkpd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id'], 'required'],
            [['perwakilan_id', 'province_id', 'stat_lk'], 'integer'],
            [['tanggal', 'created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
            [['pihak_bantu_susun', 'pihak_bantu_reviu'], 'string', 'max' => 20],
            [['opini_id'], 'string', 'max' => 2],
            [['ket'], 'string', 'max' => 255],
            [['user_id'], 'string', 'max' => 3],
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
            'stat_lk' => 'Stat Lk',
            'tanggal' => 'Tanggal',
            'pihak_bantu_susun' => 'Pihak Bantu Susun',
            'pihak_bantu_reviu' => 'Pihak Bantu Reviu',
            'opini_id' => 'Opini ID',
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
    
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }

    public function getBantuSusun()
    {
        return $this->hasOne(RefBantuan::className(), ['id' => 'pihak_bantu_susun']);
    }

    public function getBantuReviu()
    {
        return $this->hasOne(RefBantuan::className(), ['id' => 'pihak_bantu_reviu']);
    }

    public function getOpini()
    {
        return $this->hasOne(RefOpini::className(), ['id' => 'opini_id']);
    }
}
