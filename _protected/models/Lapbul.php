<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lapbul".
 *
 * @property string $id
 * @property string $bulan
 * @property integer $perwakilan_id
 * @property string $no_lap
 * @property string $tgl_lap
 * @property string $nm_ttd
 * @property string $jbt_ttd
 * @property string $nip_ttd
 * @property integer $locked
 * @property integer $user_id
 * @property string $created
 * @property string $updated
 *
 * @property RefPerwakilan $perwakilan
 */
class Lapbul extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lapbul';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'no_lap', 'tgl_lap', 'nm_ttd', 'jbt_ttd', 'nip_ttd', 'locked', 'user_id', 'created', 'updated'], 'required'],
            [['perwakilan_id', 'locked', 'user_id'], 'integer'],
            [['tgl_lap', 'created', 'updated'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['no_lap'], 'string', 'max' => 20],
            [['nm_ttd', 'jbt_ttd', 'nip_ttd'], 'string', 'max' => 45],
            [['perwakilan_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefPerwakilan::className(), 'targetAttribute' => ['perwakilan_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bulan' => 'Bulan',
            'perwakilan_id' => 'Perwakilan ID',
            'no_lap' => 'No Lap',
            'tgl_lap' => 'Tgl Lap',
            'nm_ttd' => 'Nm Ttd',
            'jbt_ttd' => 'Jbt Ttd',
            'nip_ttd' => 'Nip Ttd',
            'locked' => 'Locked',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerwakilan()
    {
        return $this->hasOne(RefPerwakilan::className(), ['id' => 'perwakilan_id']);
    }
}
