<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "lmou".
 *
 * @property integer $id
 * @property string $bulan
 * @property integer $perwakilan_id
 * @property integer $province_id
 * @property string $pemda_id
 * @property string $no_mou
 * @property string $tanggal_mou
 * @property string $no_mou_pemda
 * @property string $judul
 * @property string $ruang_lingkup
 * @property string $tanggal_berlaku
 * @property string $ket
 * @property string $user_id
 * @property integer $approved
 * @property string $created
 * @property string $updated
 *
 * @property RefPemda $pemda
 */
class Lmou extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lmou';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'no_mou', 'no_mou_pemda', 'judul', 'ruang_lingkup'], 'required'],
            [['perwakilan_id', 'province_id', 'approved'], 'integer'],
            [['tanggal_mou', 'tanggal_berlaku', 'created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id'], 'string', 'max' => 5],
            [['no_mou', 'no_mou_pemda'], 'string', 'max' => 30],
            [['judul', 'ruang_lingkup', 'ket'], 'string', 'max' => 2000],
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
            'no_mou' => 'No Mou',
            'tanggal_mou' => 'Tanggal Mou',
            'no_mou_pemda' => 'No Mou Pemda',
            'judul' => 'Judul',
            'ruang_lingkup' => 'Ruang Lingkup',
            'tanggal_berlaku' => 'Tanggal Berlaku',
            'ket' => 'Ket',
            'user_id' => 'User ID',
            'approved' => 'Approved',
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
}
