<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_kategori_evaluasi".
 *
 * @property integer $id
 * @property string $range
 * @property string $name
 */
class RefKategoriEvaluasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_kategori_evaluasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'range', 'name'], 'required'],
            [['id'], 'integer'],
            [['range'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'range' => 'Range',
            'name' => 'Name',
        ];
    }
}
