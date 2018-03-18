<?php

namespace app\modules\dataentry\controllers;

use Yii;
use app\models\Lsimdas;
use app\modules\dataentry\models\LsimdasSearch;
use app\models\RefPemda;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
class SimdaController extends Controller
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
     * Lists all Lsimdas models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new LsimdasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Tahun' => $tahun,
        ]);
    }

    /**
     * Displays a single Lsimdas model.
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
     * Creates a new Lsimdas model.
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

        $model = new Lsimdas();
        $model->bulan = $this->tahun.$this->bulan;
        $model->perwakilan_id = $pemda->perwakilan_id;
        $model->province_id = $pemda->province_id;
        $model->pemda_id = $pemda->id;

        if ($model->load(Yii::$app->request->post())) {
            // return var_dump($model->validate();
            IF($model->save()){
                return 1;
            }ELSE{
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lsimdas model.
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
            IF($model->save()){
                return 1;
            }ELSE{
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lsimdas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {


        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Lsimdas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lsimdas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lsimdas::findOne($id)) !== null) {
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
