<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lspips".
 *
 * @property integer $id
 * @property string $bulan
 * @property string $perwakilan_id
 * @property string $province_id
 * @property string $pemda_id
 * @property string $no_perkada
 * @property string $tanggal_perkada
 * @property string $pihak_bantu
 * @property string $ket
 * @property string $no_sk_satgas
 * @property string $tanggal_sk
 * @property string $user_id
 * @property string $created
 * @property string $updated
 *
 * @property RefPemda $pemda
 */
class Lspips extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lspips';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'no_perkada', 'tanggal_perkada', 'pihak_bantu'], 'required'],
            [['tanggal_perkada', 'tanggal_sk', 'created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['perwakilan_id', 'province_id'], 'string', 'max' => 2],
            [['pemda_id'], 'string', 'max' => 5],
            [['no_perkada', 'pihak_bantu'], 'string', 'max' => 20],
            [['ket'], 'string', 'max' => 255],
            [['no_sk_satgas', 'user_id'], 'string', 'max' => 50],
            [['no_perkada', 'pemda_id', 'tanggal_perkada', 'bulan'], 'unique', 'targetAttribute' => ['no_perkada', 'pemda_id', 'tanggal_perkada', 'bulan'], 'message' => 'The combination of Bulan, Pemda ID, No Perkada and Tanggal Perkada has already been taken.'],
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
            'no_perkada' => 'No Perkada',
            'tanggal_perkada' => 'Tanggal Perkada',
            'pihak_bantu' => 'Pihak Bantu',
            'ket' => 'Ket',
            'no_sk_satgas' => 'No Sk Satgas',
            'tanggal_sk' => 'Tanggal Sk',
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
}
