<?php

namespace app\modules\dataentry\controllers;

use Yii;
use app\models\LdanadesaPenyaluranRkudesa;
use app\modules\dataentry\models\LdanadesaPenyaluranRkudesaSearch;
use app\models\RefPemda;
use app\models\RefPendapatanDesa;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/
class DanadesarkudesaController extends Controller
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
     * Lists all LdanadesaPenyaluranRkudesa models.
     * @return mixed
     */
    public function actionIndex($pemda_id, $pendapatan_desa_id)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax) return $this->redirect('danadesa');
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;

        $pemda = RefPemda::findOne($pemda_id);

        // insert
        $model = new LdanadesaPenyaluranRkudesa();
        $model->tahun = $tahun;
        $model->bulan = $this->tahun.$this->bulan;
        $model->perwakilan_id = $pemda->perwakilan_id;
        $model->province_id = $pemda->province_id;
        $model->pemda_id = $pemda->id;
        $model->pendapatan_desa_id = $pendapatan_desa_id;

        $searchModel = new LdanadesaPenyaluranRkudesaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['tahun' => $tahun, 'pemda_id' => $pemda_id]);

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tahun' => $tahun,
            'model' => $model,
            'pemda_id' => $pemda_id,
            'pendapatan_desa_id' => $pendapatan_desa_id,
        ]);
    }

    /**
     * Displays a single LdanadesaPenyaluranRkudesa model.
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
     * Creates a new LdanadesaPenyaluranRkudesa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($pemda_id, $pendapatan_desa_id)
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;

        $pemda = RefPemda::findOne($pemda_id);

        // insert
        $model = new LdanadesaPenyaluranRkudesa();
        $model->tahun = $tahun;
        $model->bulan = $this->tahun.$this->bulan;
        $model->perwakilan_id = $pemda->perwakilan_id;
        $model->province_id = $pemda->province_id;
        $model->pemda_id = $pemda->id;
        $model->pendapatan_desa_id = $pendapatan_desa_id;

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
            ]);
        }
    }

    /**
     * Updates an existing LdanadesaPenyaluranRkudesa model.
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
            $model->nilai = str_replace(',', '.', $model->nilai);
            IF($model->save()){
                return 1;
            }ELSE{
                return 0;
            }
        } else {
            if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $model;
            }
            
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LdanadesaPenyaluranRkudesa model.
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
     * Finds the LdanadesaPenyaluranRkudesa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LdanadesaPenyaluranRkudesa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LdanadesaPenyaluranRkudesa::findOne($id)) !== null) {
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
