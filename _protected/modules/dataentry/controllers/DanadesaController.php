<?php

namespace app\modules\dataentry\controllers;

use Yii;
use app\models\LdanadesaAlokasi;
use app\modules\dataentry\models\LdanadesaAlokasiSearch;
use app\models\RefPemda;
use app\models\RefPendapatanDesa;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
class DanadesaController extends Controller
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
     * Lists all LdanadesaAlokasi models.
     * @return mixed
     */
    public function actionIndex()
    {   
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;
        
        $searchModel = new LdanadesaAlokasiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['tahun' => $tahun])->andWhere("bulan <= $tahunBulan");
        if($perwakilan_id) $dataProvider->query->andWhere(['perwakilan_id' => $perwakilan_id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tahun' => $tahun,
        ]);
    }

    /**
     * Displays a single LdanadesaAlokasi model.
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
     * Creates a new LdanadesaAlokasi model.
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

        $model = new LdanadesaAlokasi();
        $model->tahun = $tahun;
        $model->bulan = $tahunBulan;
        
        // Pemda Array
        $pemda = RefPemda::find()->select(["id", "CONCAT(id, ' ', name) AS name"]);
        if($perwakilan_id) $pemda->where(['perwakilan_id' => $perwakilan_id]);
        $pemda = $pemda->orderBy('id ASC')->all();

        // Dana Desa Array
        $pendapatanDesa = RefPendapatanDesa::findAll(['dana_desa' => 1]);

        if ($model->load(Yii::$app->request->post())) {
            $model->nilai = str_replace(',', '.', $model->nilai);
            // return var_dump($model->getErrors());
            IF($model->save()){
                return 1;
            }ELSE{
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'pemda' => $pemda,
                'pendapatanDesa' => $pendapatanDesa,
            ]);
        }
    }

    /**
     * Updates an existing LdanadesaAlokasi model.
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

        // Dana Desa Array
        $pendapatanDesa = RefPendapatanDesa::findAll(['dana_desa' => 1]);

        if ($model->load(Yii::$app->request->post())) {
            $model->nilai = str_replace(',', '.', $model->nilai);
            IF($model->save()){
                return 1;
            }ELSE{
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'pemda' => $pemda,
                'pendapatanDesa' => $pendapatanDesa,
            ]);
        }
    }

    /**
     * Deletes an existing LdanadesaAlokasi model.
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
     * Finds the LdanadesaAlokasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LdanadesaAlokasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LdanadesaAlokasi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function cekakses(){

        IF(Yii::$app->user->identity){
            $akses = \app\models\RefUserMenu::find()->where(['kd_user' => Yii::$app->user->identity->kd_user, 'menu' => 305])->one();
            IF($akses){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }  

}
