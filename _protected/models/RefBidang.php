<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_bidang".
 *
 * @property int $kd_urusan
 * @property int $kd_bidang
 * @property string $nm_bidang
 */
class RefBidang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_bidang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_urusan', 'kd_bidang', 'nm_bidang'], 'required'],
            [['kd_urusan', 'kd_bidang'], 'integer'],
            [['nm_bidang'], 'string', 'max' => 100],
            [['kd_urusan', 'kd_bidang'], 'unique', 'targetAttribute' => ['kd_urusan', 'kd_bidang']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kd_urusan' => 'Kd Urusan',
            'kd_bidang' => 'Kd Bidang',
            'nm_bidang' => 'Nm Bidang',
        ];
    }
}
