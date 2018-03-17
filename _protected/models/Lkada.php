<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "lkada".
 *
 * @property integer $id
 * @property string $bulan
 * @property integer $perwakilan_id
 * @property integer $province_id
 * @property string $pemda_id
 * @property string $created
 * @property string $updated
 * @property integer $status_kada
 * @property string $nama
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $partai
 * @property string $masa_jabatan
 * @property string $image_name
 * @property string $saved_image
 */
class Lkada extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lkada';
    }

    public $image;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'perwakilan_id', 'province_id', 'pemda_id', 'nama', 'masa_jabatan', 'jabatan'], 'required'],
            [['perwakilan_id', 'province_id', 'status_kada'], 'integer'],
            [['created', 'updated', 'tanggal_lahir'], 'safe'],
            [['bulan'], 'string', 'max' => 6],
            [['pemda_id', 'tempat_lahir'], 'string', 'max' => 5],
            [['nama', 'partai'], 'string', 'max' => 100],
            [['masa_jabatan'], 'string', 'max' => 10],
            [['image'], 'file', 'maxSize' => 2104000, /*'maxFiles' => 10,*/ 'extensions'=>'jpg, gif, png'],
            [['image_name', 'saved_image', 'jabatan'], 'string', 'max' => 50],
            [['perwakilan_id', 'province_id', 'pemda_id', 'bulan', 'status_kada'], 'unique', 'targetAttribute' => ['perwakilan_id', 'province_id', 'pemda_id', 'bulan', 'status_kada'], 'message' => 'The combination of Bulan, Perwakilan ID, Province ID, Pemda ID and Status Kada has already been taken.'],
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
            'province_id' => 'Province ID',
            'pemda_id' => 'Pemda ID',
            'created' => 'Created',
            'updated' => 'Updated',
            'status_kada' => 'Status Kada',
            'nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'partai' => 'Partai',
            'masa_jabatan' => 'Masa Jabatan',
            'image_name' => 'Image Name',
            'saved_image' => 'Saved Image',
            'image' => 'Photo',
            'jabatan' => 'Jabatan',
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
        ];
    }

    public function getStatus()
    {
        switch ($this->status_kada) {
            case 1:
                return "Kepala Daerah";
                break;
            case 2:
                return "Wakil Kepala Daerah";
                break;
            
            default:
                return "The hell?";
                break;
        }
    }

    public function getPemda()
    {
        return $this->hasOne(RefPemda::className(), ['id' => 'pemda_id']);
    }

    private function checkFolder($directoryName){
        if(!is_dir($directoryName)){
            //Directory does not exist, so lets create it.
            mkdir($directoryName, 0755, true);
        }
    }

    private function checkFile($filePath){
        return file_exists($filePath);
    }

    public function getImage(){
        // // check folder first
        // $this->checkFolder('@webroot/uploads');
        // $this->checkFolder('@webroot/uploads/'.$this->pemda_id.'/kada');
        // // getImage
        // if(isset($this->image_name)){
        //     $filePath = '@webroot/uploads/'.$this->pemda_id.'/kada/'.$this->saved_image;
        //     // check file exist first
        //     // if($this->checkFile($filePath)) 
        //     return $filePath;
        // }
        // return null;

        // check folder first
        $this->checkFolder(Url::to('@webroot/uploads'));
        $this->checkFolder(Url::to('@webroot/uploads/'.$this->pemda_id.'/kada'));
        // getImage
        if(isset($this->image_name)){
            $filePath = Url::to('@webroot/uploads/'.$this->pemda_id.'/kada/'.$this->saved_image);
            // check file exist first
            // if($this->checkFile($filePath)) 
            return $filePath;
        }
        return null;        
    }

    public function getImageUrl(){
        return Url::to('@web/uploads/'.$this->pemda->id.'/kada/'.$this['saved_image']);
    }

    public function uploadImage(){
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'image');

        // if no image was uploaded abort the upload
        if (empty($image)) {
            return false;
        }

        // store the source file name
        $this->image_name = $image->name;
        $imageName = (explode(".", $image->name));
        $ext = end($imageName);

        // generate a unique file name
        $this->saved_image = Yii::$app->security->generateRandomString().".{$ext}";

        // the uploaded image instance
        return $image;
    }

    public function deleteImage() {
        $file = $this->getImage();

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }

        // if deletion successful, reset your file attributes
        $this->image_name = null;
        $this->saved_image = null;

        $this->save();

        return true;
    }        

}
