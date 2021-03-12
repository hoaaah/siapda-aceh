<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penyerapan_urusan".
 *
 * @property int $id
 * @property string $bulan
 * @property int $perwakilan_id
 * @property int $province_id
 * @property string $pemda_id
 * @property string|null $tanggal_pelaporan
 * @property int|null $kd_urusan
 * @property int|null $kd_bidang
 * @property float|null $anggaran
 * @property float|null $realisasi
 */
class PenyerapanUrusan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penyerapan_urusan';
    }

    public $kd_urbid;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id'], 'required'],
            [['perwakilan_id', 'province_id', 'kd_urusan', 'kd_bidang'], 'integer'],
            [['tanggal_pelaporan'], 'safe'],
            [['anggaran', 'realisasi'], 'number'],
            [['bulan'], 'string', 'max' => 6],
            [['kd_urbid'], 'string'],
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
            'tanggal_pelaporan' => 'Tanggal Pelaporan',
            'kd_urusan' => 'Kd Urusan',
            'kd_bidang' => 'Kd Bidang',
            'anggaran' => 'Anggaran',
            'realisasi' => 'Realisasi',
            'kd_urbid' => 'Urusan Pemerintahan',
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
        $this->setUrbidFromKdUrbid();
        return true;
    }

    public function setUrbidFromKdUrbid()
    {
        if ($this->kd_urbid) {
            list($this->kd_urusan, $this->kd_bidang) = explode(".", $this->kd_urbid);
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

    public function getRefBidang()
    {
        return $this->hasOne(RefBidang::class, ['kd_urusan' => 'kd_urusan', 'kd_bidang' => 'kd_bidang']);
    }

    public function getRefUrusan()
    {
        return $this->hasOne(RefUrusan::class, ['kd_urusan' => 'kd_urusan']);
    }
}
