<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_rek_1".
 *
 * @property int $kd_rek_1
 * @property string|null $nm_rek_1
 *
 * @property RefRek2[] $refRek2s
 */
class RefRek1 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_rek_1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_rek_1'], 'required'],
            [['kd_rek_1'], 'integer'],
            [['nm_rek_1'], 'string', 'max' => 255],
            [['kd_rek_1'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kd_rek_1' => 'Kd Rek 1',
            'nm_rek_1' => 'Nm Rek 1',
        ];
    }

    /**
     * Gets query for [[RefRek2s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefRek2s()
    {
        return $this->hasMany(RefRek2::className(), ['kd_rek_1' => 'kd_rek_1']);
    }
}
