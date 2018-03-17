<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_kategori_penugasan".
 *
 * @property integer $id
 * @property string $name
 *
 * @property RefKelompok[] $refKelompoks
 */
class RefKategoriPenugasan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_kategori_penugasan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKelompoks()
    {
        return $this->hasMany(RefKelompok::className(), ['kategori_id' => 'id']);
    }
}
