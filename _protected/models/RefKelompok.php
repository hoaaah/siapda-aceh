<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_kelompok".
 *
 * @property integer $id
 * @property integer $kategori_id
 * @property string $name
 *
 * @property RefKegiatan[] $refKegiatans
 * @property RefKategoriPenugasan $kategori
 */
class RefKelompok extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_kelompok';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kategori_id', 'name'], 'required'],
            [['kategori_id'], 'integer'],
            [['name'], 'string', 'max' => 25],
            [['kategori_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefKategoriPenugasan::className(), 'targetAttribute' => ['kategori_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kategori_id' => 'Kategori ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKegiatans()
    {
        return $this->hasMany(RefKegiatan::className(), ['kelompok_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(RefKategoriPenugasan::className(), ['id' => 'kategori_id']);
    }
}
