<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_kegiatan".
 *
 * @property integer $id
 * @property integer $kelompok_id
 * @property string $no_kegiatan
 * @property string $name
 *
 * @property RefKelompok $kelompok
 */
class RefKegiatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_kegiatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kelompok_id', 'no_kegiatan', 'name'], 'required'],
            [['kelompok_id'], 'integer'],
            [['no_kegiatan'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 30],
            [['kelompok_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefKelompok::className(), 'targetAttribute' => ['kelompok_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kelompok_id' => 'Kelompok ID',
            'no_kegiatan' => 'No Kegiatan',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelompok()
    {
        return $this->hasOne(RefKelompok::className(), ['id' => 'kelompok_id']);
    }
}
