<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_permasalahan_penyerapan".
 *
 * @property int $id
 * @property string $nm_permasalahan
 */
class RefPermasalahanPenyerapan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_permasalahan_penyerapan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nm_permasalahan'], 'required'],
            [['nm_permasalahan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nm_permasalahan' => 'Nm Permasalahan',
        ];
    }
}
