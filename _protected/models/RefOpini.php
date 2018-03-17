<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_opini".
 *
 * @property integer $id_opini
 * @property string $id
 * @property string $name
 * @property string $created
 * @property string $updated
 */
class RefOpini extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_opini';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'created', 'updated'], 'required'],
            [['created', 'updated'], 'safe'],
            [['id'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_opini' => 'Id Opini',
            'id' => 'ID',
            'name' => 'Name',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
