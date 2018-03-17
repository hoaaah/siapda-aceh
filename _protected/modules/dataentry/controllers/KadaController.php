<?php

namespace app\modules\dataentry\controllers;

use Yii;
use app\models\Lkada;
use app\modules\dataentry\models\LkadaSearch;
use app\models\RefPemda;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
class KadaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    // Set Tahun
    protected function getTahun(){
        if(Yii::$app->session->get('tahun'))
        {
            $tahun = Yii::$app->session->get('tahun');
        }ELSE{
            $tahun = DATE('Y');
        }
        return $tahun;
    }

    // Set Bulan
    protected function getBulan(){
        if(Yii::$app->session->get('bulan'))
        {
            $tahun = Yii::$app->session->get('bulan');
        }ELSE{
            $tahun = DATE('m');
        }
        return substr("0".$tahun, -2);
    }    

    /**
     * Lists all Lkada models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new LkadaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Tahun' => $tahun,
        ]);
    }

    /**
     * Displays a single Lkada model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lkada model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $pemda = RefPemda::findOne($id);
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;

        $model = new Lkada();
        $model->bulan = $this->tahun.$this->bulan;
        $model->perwakilan_id = $pemda->perwakilan_id;
        $model->province_id = $pemda->province_id;
        $model->pemda_id = $pemda->id;

        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $image = UploadedFile::getInstance($model, 'image');
                // return var_dump($image);
                if($image){
                    // store the source file name
                    $model->image_name = $image->name;
                    $imageName = (explode(".", $image->name)); 
                    $ext = end($imageName);
    
                    // generate a unique file name
                    $model->saved_image = Yii::$app->security->generateRandomString().".{$ext}";
    
                    if($model->save()){
                        // generate path
                        $path = $model->getImage();
                        $image->saveAs($path);
                        // return 1;
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                    return 0;
                }
            }
            IF($model->save()){
                // return 1;
                return $this->redirect(Yii::$app->request->referrer);
            }ELSE{
                var_dump($model->getErrors());
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lkada model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $image = UploadedFile::getInstance($model, 'image');
                // return var_dump($image);
                if($image){
                    // store the source file name
                    $model->image_name = $image->name;
                    $imageName = (explode(".", $image->name)); 
                    $ext = end($imageName);
    
                    // generate a unique file name
                    $model->saved_image = Yii::$app->security->generateRandomString().".{$ext}";
    
                    if($model->save()){
                        // generate path
                        $path = $model->getImage();
                        $image->saveAs($path);
                        // return 1;
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                    return 0;
                }
            }
            IF($model->save()){
                // return 1;
                return $this->redirect(Yii::$app->request->referrer);
            }ELSE{
                var_dump($model->getErrors());
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lkada model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleteImage();
        $model->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteImage($id)
    {
        $model = $this->findModel($id);
        if(!$model->deleteImage()){
            return false;
        }
        return true;
    }

    /**
     * Finds the Lkada model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lkada the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lkada::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function cekakses(){

        IF(Yii::$app->user->identity){
            $akses = \app\models\RefUserMenu::find()->where(['kd_user' => Yii::$app->user->identity->kd_user, 'menu' => 401])->one();
            IF($akses){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }  

}
