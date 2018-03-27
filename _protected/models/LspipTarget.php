<?php

namespace app\models;

use Yii;

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
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'tahun'], 'required'],
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
}
