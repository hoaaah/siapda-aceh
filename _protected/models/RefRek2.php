<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_rek_2".
 *
 * @property int $kd_rek_1
 * @property int $kd_rek_2
 * @property string|null $nm_rek_2
 *
 * @property RefRek1 $kdRek1
 * @property RefRek3[] $refRek3s
 */
class RefRek2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_rek_2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_rek_1', 'kd_rek_2'], 'required'],
            [['kd_rek_1', 'kd_rek_2'], 'integer'],
            [['nm_rek_2'], 'string', 'max' => 100],
            [['kd_rek_1', 'kd_rek_2'], 'unique', 'targetAttribute' => ['kd_rek_1', 'kd_rek_2']],
            [['kd_rek_1'], 'exist', 'skipOnError' => true, 'targetClass' => RefRek1::className(), 'targetAttribute' => ['kd_rek_1' => 'kd_rek_1']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kd_rek_1' => 'Kd Rek 1',
            'kd_rek_2' => 'Kd Rek 2',
            'nm_rek_2' => 'Nm Rek 2',
        ];
    }

    /**
     * Gets query for [[KdRek1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKdRek1()
    {
        return $this->hasOne(RefRek1::className(), ['kd_rek_1' => 'kd_rek_1']);
    }

    /**
     * Gets query for [[RefRek3s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefRek3s()
    {
        return $this->hasMany(RefRek3::className(), ['kd_rek_1' => 'kd_rek_1', 'kd_rek_2' => 'kd_rek_2']);
    }
}
