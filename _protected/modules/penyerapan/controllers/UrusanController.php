<?php

namespace app\modules\penyerapan\controllers;

use app\models\Model;
use Yii;
use app\models\PenyerapanUrusan;
use app\models\RefPemda;
use app\modules\penyerapan\models\PenyerapanUrusanSearch;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/

class UrusanController extends Controller
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
     * Lists all PenyerapanUrusan models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new PenyerapanUrusanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Tahun' => $tahun,
        ]);
    }

    /**
     * Displays a single PenyerapanUrusan model.
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
     * Creates a new PenyerapanUrusan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun . $bulan;

        $pemda = null;
        if (Yii::$app->user->identity->pemda_id) {
            $pemda = RefPemda::findOne(['id' => Yii::$app->user->identity->pemda_id]);
        }

        $urbid = Yii::$app->db->createCommand("SELECT CONCAT(kd_urusan, '.', kd_bidang) AS kd_urbid, CONCAT(kd_urusan, '.', kd_bidang, ' ', nm_bidang) AS nm_bidang FROM ref_bidang ")->queryAll();
        $urbidArrayList = ArrayHelper::map($urbid, 'kd_urbid', 'nm_bidang');

        $model = new PenyerapanUrusan();
        $model->bulan = $this->tahun . $this->bulan;
        $model->perwakilan_id = $pemda->perwakilan_id;
        $model->province_id = $pemda->province_id;
        $model->pemda_id = $pemda->id;

        // $modelRekening = [new PenyerapanRekening];
        $modelRekening = [];
        $i = 0;
        foreach ($urbid as $value) {
            $modelRekening[$value['kd_urbid']] = new PenyerapanUrusan();
            $modelRekening[$value['kd_urbid']]->setAttributes($model->attributes);
            $modelRekening[$value['kd_urbid']]->kd_urbid = $value['kd_urbid'];
            $i++;
        }

        if ($model->load(Yii::$app->request->post())) {
            // $modelRekening = Model::createMultiple(PenyerapanRekening::class);
            Model::loadMultiple($modelRekening, Yii::$app->request->post());

            // return var_dump(Yii::$app->request->post());

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $flag = null;
                foreach ($modelRekening as $key => $rincianRekening) {
                    // if ($rincianRekening->anggaran || $rincianRekening->realisasi)
                    $rincianRekening->tanggal_pelaporan = $model->tanggal_pelaporan;
                    if (!($flag = $rincianRekening->save(false))) {
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return 1;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return 0;
            }
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modelRekening' => $modelRekening,
                'urbidArrayList' => $urbidArrayList,
                'urbid' => $urbid
            ]);
        }
    }

    /**
     * Updates an existing PenyerapanUrusan model.
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
     * Deletes an existing PenyerapanUrusan model.
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
     * Finds the PenyerapanUrusan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PenyerapanUrusan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PenyerapanUrusan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function cekakses()
    {

        if (Yii::$app->user->identity) {
            $akses = \app\models\RefUserMenu::find()->where(['kd_user' => Yii::$app->user->identity->kd_user, 'menu' => 310])->one();
            if ($akses) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
