<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_pendapatan_desa".
 *
 * @property integer $id
 * @property string $name
 * @property integer $dana_desa
 */
class RefPendapatanDesa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_pendapatan_desa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dana_desa'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'dana_desa' => 'Dana Desa',
        ];
    }
}
