<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "lappds".
 *
 * @property integer $id
 * @property string $bulan
 * @property string $perwakilan_id
 * @property string $province_id
 * @property string $pemda_id
 * @property integer $katc_id
 * @property string $tanggal
 * @property string $pihak_bantu
 * @property string $stat_id
 * @property string $ket
 * @property string $user_id
 * @property string $created
 * @property string $updated
 *
 * @property RefPemda $pemda
 */
class Lappds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lappds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'katc_id'], 'required'],
            [['katc_id'], 'integer'],
            [['tanggal', 'created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['perwakilan_id', 'province_id', 'stat_id'], 'string', 'max' => 2],
            [['pemda_id'], 'string', 'max' => 5],
            [['pihak_bantu'], 'string', 'max' => 20],
            [['ket'], 'string', 'max' => 255],
            [['user_id'], 'string', 'max' => 4],
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
            'katc_id' => 'Katc ID',
            'tanggal' => 'Tanggal',
            'pihak_bantu' => 'Pihak Bantu',
            'stat_id' => 'Stat ID',
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

    public function getKatc()
    {
        return $this->hasOne(Katcs::className(), ['id' => 'katc_id']);
    }

    public function getBantuSusun()
    {
        return $this->hasOne(RefBantuan::className(), ['id' => 'pihak_bantu']);
    }
}
