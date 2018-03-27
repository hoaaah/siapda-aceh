<?php

namespace app\modules\dataentry\controllers;

use Yii;
use app\models\LspipEvaluasi;
use app\modules\dataentry\models\LspipEvaluasiSearch;
use app\models\RefPemda;
use app\models\RefBantuan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
class SpipevalController extends Controller
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
     * Lists all LspipEvaluasi models.
     * @return mixed
     */
    public function actionIndex()
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;

        $searchModel = new LspipEvaluasiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['tahun' => $tahun])->andWhere("bulan <= $tahunBulan");
        if($perwakilan_id) $dataProvider->query->andWhere(['perwakilan_id' => $perwakilan_id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Tahun' => $tahun,
        ]);
    }

    /**
     * Displays a single LspipEvaluasi model.
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
     * Creates a new LspipEvaluasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;

        $model = new LspipEvaluasi();
        $model->tahun = $tahun;
        $model->bulan = $this->tahun.$this->bulan;
        // Pemda Array
        $pemda = RefPemda::find()->select(["id", "CONCAT(id, ' ', name) AS name"]);
        if($perwakilan_id) $pemda->where(['perwakilan_id' => $perwakilan_id]);
        $pemda = $pemda->orderBy('id ASC')->all();

        if ($model->load(Yii::$app->request->post())) {
            IF($model->save()){
                return 1;
            }ELSE{
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'pemda' => $pemda,
            ]);
        }
    }

    /**
     * Updates an existing LspipEvaluasi model.
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
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;

        $model = $this->findModel($id);
        // Pemda Array
        $pemda = RefPemda::find()->select(["id", "CONCAT(id, ' ', name) AS name"]);
        if($perwakilan_id) $pemda->where(['perwakilan_id' => $perwakilan_id]);
        $pemda = $pemda->orderBy('id ASC')->all();

        if ($model->load(Yii::$app->request->post())) {
            IF($model->save()){
                return 1;
            }ELSE{
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'pemda' => $pemda,
            ]);
        }
    }

    /**
     * Deletes an existing LspipEvaluasi model.
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
     * Finds the LspipEvaluasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LspipEvaluasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LspipEvaluasi::findOne($id)) !== null) {
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
