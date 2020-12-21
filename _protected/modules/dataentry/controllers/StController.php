<?php

namespace app\modules\dataentry\controllers;

use Yii;
use app\models\Lkegiatans;
use app\modules\dataentry\models\LkegiatansSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/

class StController extends Controller
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
    protected function getTahun()
    {
        if (Yii::$app->session->get('tahun')) {
            $tahun = Yii::$app->session->get('tahun');
        } else {
            $tahun = DATE('Y');
        }
        return $tahun;
    }

    // Set Bulan
    protected function getBulan()
    {
        if (Yii::$app->session->get('bulan')) {
            $tahun = Yii::$app->session->get('bulan');
        } else {
            $tahun = DATE('m');
        }
        return substr("0" . $tahun, -2);
    }

    /**
     * Lists all Lkegiatans models.
     * @return mixed
     */
    public function actionIndex()
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun . $bulan;
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;

        $searchModel = new LkegiatansSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($perwakilan_id) $dataProvider->query->andWhere(['perwakilan_id' => $perwakilan_id]);
        $dataProvider->query->andWhere(['bulan' => $tahunBulan]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Tahun' => $this->getTahun()
        ]);
    }

    /**
     * Displays a single Lkegiatans model.
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
     * Creates a new Lkegiatans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun . $bulan;

        $model = new Lkegiatans();
        $model->bulan = $this->tahun . $this->bulan;
        if ($perwakilan_id) $model->perwakilan_id = $perwakilan_id;

        if ($model->load(Yii::$app->request->post())) {
            // return var_dump($model);
            if ($model->save()) {
                return 1;
            } else {
                return var_dump($model->getErrors());
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lkegiatans model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun . $bulan;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lkegiatans model.
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
     * Finds the Lkegiatans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lkegiatans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lkegiatans::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function cekakses()
    {

        if (Yii::$app->user->identity) {
            $akses = \app\models\RefUserMenu::find()->where(['kd_user' => Yii::$app->user->identity->kd_user, 'menu' => 401])->one();
            if ($akses) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
