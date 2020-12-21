<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "lkegiatans".
 *
 * @property int $id
 * @property string $bulan
 * @property string $perwakilan_id
 * @property string $province_id
 * @property string $pemda_id
 * @property string $kategori_id
 * @property string $kelompok_id
 * @property string $kegiatan_id
 * @property string $nama_kegiatan
 * @property string $no_st
 * @property string $tanggal_st
 * @property string $no_laporan
 * @property string $ket
 * @property string $user_id
 * @property string $created
 * @property string $updated
 * @property string $tanggal_lap
 * @property int $perpanjangan
 *
 * @property RefPemda $pemda
 */
class Lkegiatans extends \yii\db\ActiveRecord
{

    public $kode_pemda_gabung, $kode_kegiatan_gabung;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lkegiatans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'kategori_id', 'kelompok_id', 'kegiatan_id', 'nama_kegiatan', 'no_st', 'tanggal_st', 'no_laporan', 'ket' /*, 'user_id', 'created', 'updated' */, 'tanggal_lap'], 'required'],
            [['tanggal_st', 'created', 'updated', 'tanggal_lap'], 'safe'],
            [['perpanjangan'], 'integer'],
            [['bulan'], 'string', 'max' => 6],
            [['perwakilan_id', 'province_id', 'kategori_id'], 'string', 'max' => 2],
            [['pemda_id'], 'string', 'max' => 5],
            [['kelompok_id'], 'string', 'max' => 4],
            [['kegiatan_id'], 'string', 'max' => 7],
            [['nama_kegiatan', 'ket', 'kode_pemda_gabung', 'kode_kegiatan_gabung'], 'string', 'max' => 255],
            [['no_st', 'no_laporan'], 'string', 'max' => 20],
            [['user_id'], 'string', 'max' => 50],
            [['pemda_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefPemda::className(), 'targetAttribute' => ['pemda_id' => 'id']],
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
            'kategori_id' => 'Kategori ID',
            'kelompok_id' => 'Kelompok ID',
            'kegiatan_id' => 'Kegiatan ID',
            'nama_kegiatan' => 'Nama Kegiatan',
            'no_st' => 'No St',
            'tanggal_st' => 'Tanggal St',
            'no_laporan' => 'No Laporan',
            'ket' => 'Ket',
            'user_id' => 'User ID',
            'created' => 'Created',
            'updated' => 'Updated',
            'tanggal_lap' => 'Tanggal Lap',
            'perpanjangan' => 'Perpanjangan',
            'kode_pemda_gabung' => 'Pemda',
            'kode_kegiatan_gabung' => 'Kegiatan'
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ],
        ];
    }

    public function beforeValidate()
    {
        if ($this->kode_kegiatan_gabung) $this->setKegiatan();
        if ($this->kode_pemda_gabung) $this->setPemda();

        if (parent::beforeValidate()) {

            return true;
        }
    }

    private function setKegiatan()
    {
        if ($this->kode_kegiatan_gabung) {
            list($this->kategori_id, $this->kelompok_id, $this->kegiatan_id) = explode(".", $this->kode_kegiatan_gabung);
        }
    }

    private function setPemda()
    {
        if ($this->kode_pemda_gabung) {
            list($this->province_id, $this->pemda_id) = explode("~", $this->kode_pemda_gabung);
        }
    }

    public function pemdaArrayList($perwakilan_id = null)
    {
        $query = Yii::$app->db->createCommand("
            SELECT
            a.province_id, a.id AS pemda_id, a.name AS pemda, b.name AS province,
            CONCAT(a.province_id, '~', a.id) AS kode,
            (a.name) AS name
            FROM ref_pemda a
            LEFT JOIN ref_province b ON a.province_id = b.id
            WHERE a.perwakilan_id LIKE :perwakilan_id       
            ORDER BY a.id ASC
        ", [':perwakilan_id' => $perwakilan_id ?? '%'])->queryAll();
        return ArrayHelper::map($query, 'kode', 'name');
    }

    public function kegiatanArrayList($perwakilan_id = null)
    {
        $query = Yii::$app->db->createCommand("
            SELECT 
            CONCAT (c.id, '.', b.id, '.', a.id) AS kode,
            CONCAT (c.name, ' - ', b.name, ' - ', a.name) AS name
            FROM ref_kegiatan a
            LEFT JOIN ref_kelompok b ON a.kelompok_id = b.id 
            LEFT JOIN ref_kategori_penugasan c ON b.kategori_id = c.id
            ORDER BY c.id, b.id, a.id ASC
        ")->queryAll();
        return ArrayHelper::map($query, 'kode', 'name');
    }

    /**
     * Gets query for [[Pemda]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }

    public function getKegiatan()
    {
        return $this->hasOne(RefKegiatan::class, ['id' => 'kegiatan_id']);
    }
}
