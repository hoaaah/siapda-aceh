<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_urusan".
 *
 * @property int $kd_urusan
 * @property string $nm_urusan
 */
class RefUrusan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_urusan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_urusan', 'nm_urusan'], 'required'],
            [['kd_urusan'], 'integer'],
            [['nm_urusan'], 'string', 'max' => 100],
            [['kd_urusan'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kd_urusan' => 'Kd Urusan',
            'nm_urusan' => 'Nm Urusan',
        ];
    }
}
