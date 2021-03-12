<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penyerapan_rekening".
 *
 * @property int $id
 * @property string $bulan
 * @property int $perwakilan_id
 * @property int $province_id
 * @property string $pemda_id
 * @property string|null $tanggal_pelaporan
 * @property int|null $kd_rek_1
 * @property int|null $kd_rek_2
 * @property int|null $kd_rek_3
 * @property int|null $kd_rek_4
 * @property int|null $kd_rek_5
 * @property int|null $kd_rek_6
 * @property float|null $anggaran
 * @property float|null $realisasi
 */
class PenyerapanRekening extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penyerapan_rekening';
    }

    public $rek3_gabung;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'tanggal_pelaporan'], 'required'],
            [['perwakilan_id', 'province_id', 'kd_rek_1', 'kd_rek_2', 'kd_rek_3', 'kd_rek_4', 'kd_rek_5', 'kd_rek_6'], 'integer'],
            // [['tanggal_pelaporan'], 'safe'],
            [['anggaran', 'realisasi'], 'number'],
            [['bulan'], 'string', 'max' => 6],
            [['rek3_gabung'], 'string'],
            [['pemda_id'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bulan' => 'Bulan',
            'perwakilan_id' => 'Perwakilan ID',
            'province_id' => 'Province ID',
            'pemda_id' => 'Pemda ID',
            'tanggal_pelaporan' => 'Tanggal CutOff',
            'kd_rek_1' => 'Kd Rek 1',
            'kd_rek_2' => 'Kd Rek 2',
            'kd_rek_3' => 'Kd Rek 3',
            'kd_rek_4' => 'Kd Rek 4',
            'kd_rek_5' => 'Kd Rek 5',
            'kd_rek_6' => 'Kd Rek 6',
            'rek3_gabung' => 'Rekening Jenis',
            'anggaran' => 'Anggaran',
            'realisasi' => 'Realisasi',
        ];
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        $this->setFromStringToDecimal();

        return true;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // ...custom code here...
        $this->setFromStringToDecimal();
        $this->setRekToNolIfNotExist();
        $this->setRek3FromRek3Gabung();
        return true;
    }

    public function setRekToNolIfNotExist()
    {
        if (!$this->kd_rek_1) $this->kd_rek_1 = 0;
        if (!$this->kd_rek_2) $this->kd_rek_2 = 0;
        if (!$this->kd_rek_3) $this->kd_rek_3 = 0;
        if (!$this->kd_rek_4) $this->kd_rek_4 = 0;
        if (!$this->kd_rek_5) $this->kd_rek_5 = 0;
        if (!$this->kd_rek_6) $this->kd_rek_6 = 0;
    }

    public function setRek3FromRek3Gabung()
    {
        if ($this->rek3_gabung) {
            list($this->kd_rek_1, $this->kd_rek_2, $this->kd_rek_3) = explode(".", $this->rek3_gabung);
        }
    }

    public function setFromStringToDecimal()
    {
        if ($this->anggaran) $this->comaToDecimal('anggaran');
        if ($this->realisasi) $this->comaToDecimal('realisasi');
    }

    private function comaToDecimal($attribute)
    {
        $this->{$attribute} = str_replace(',', '.', $this->{$attribute});
    }

    public function getRefRek3()
    {
        return $this->hasOne(RefRek3::class, ['kd_rek_1' => 'kd_rek_1', 'kd_rek_2' => 'kd_rek_2', 'kd_rek_3' => 'kd_rek_3']);
    }

    public function getRefRek2()
    {
        return $this->hasOne(RefRek2::class, ['kd_rek_1' => 'kd_rek_1', 'kd_rek_2' => 'kd_rek_2']);
    }

    public function getRefRek1()
    {
        return $this->hasOne(RefRek1::class, ['kd_rek_1' => 'kd_rek_1']);
    }
}
