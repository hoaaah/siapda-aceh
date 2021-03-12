<?php

namespace app\modules\penyerapan\controllers;

use app\models\Model;
use Yii;
use app\models\PenyerapanRekening;
use app\models\RefPemda;
use app\models\RefRek3;
use app\modules\penyerapan\models\PenyerapanRekeningSearch;
use app\modules\penyerapan\models\PenyerapanTriwulanSearch;
use app\modules\penyerapan\models\PenyerapanUrusanSearch;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/

class ApipController extends Controller
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
     * Lists all PenyerapanRekening models.
     * @return mixed
     */
    public function actionIndex()
    {

        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun . $bulan;

        $pemda = null;
        if (Yii::$app->user->identity->pemda_id) {
            $pemda = RefPemda::findOne(['id' => Yii::$app->user->identity->pemda_id]);
        }

        $anggaranPenyerapan = null;

        $penyerapanRekeningPeriodeIni = PenyerapanRekening::find()->select('MAX(tanggal_pelaporan) AS tanggal_pelaporan')->where(['pemda_id' => $pemda->id, 'bulan' => $tahunBulan])->one();
        if (isset($penyerapanRekeningPeriodeIni->tanggal_pelaporan)) {
            $anggaranPenyerapan = PenyerapanRekening::find()->select("tanggal_pelaporan, kd_rek_1, sum(
                CASE
                    WHEN kd_rek_1 = 6 AND kd_rek_2 = 1 THEN anggaran
                    WHEN kd_rek_1 = 6 AND kd_rek_2 = 2 THEN -anggaran
                    ELSE anggaran
                END
            ) AS anggaran")->where(['pemda_id' => $pemda->id, 'bulan' => $tahunBulan, 'tanggal_pelaporan' => $penyerapanRekeningPeriodeIni->tanggal_pelaporan])
                ->groupBy('tanggal_pelaporan, kd_rek_1')->all();
        };

        $searchModel = new PenyerapanRekeningSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'bulan' => $tahunBulan,
            'pemda_id' => Yii::$app->user->identity->pemda_id
        ]);
        $dataProvider->pagination->pageSize = 0;
        $dataProvider->sort = false;

        $dataProviderUrusan = (new PenyerapanUrusanSearch())->search(Yii::$app->request->queryParams);
        $dataProviderUrusan->query->andWhere([
            'bulan' => $tahunBulan,
            'pemda_id' => Yii::$app->user->identity->pemda_id
        ]);
        $dataProviderUrusan->pagination->pageSize = 0;
        $dataProviderUrusan->sort = false;

        $dataProviderTriwulan = (new PenyerapanTriwulanSearch())->search(Yii::$app->request->queryParams);
        $dataProviderTriwulan->query->andWhere([
            'bulan' => $tahunBulan,
            'pemda_id' => Yii::$app->user->identity->pemda_id
        ]);
        $dataProviderTriwulan->pagination->pageSize = 0;
        $dataProviderTriwulan->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'Tahun' => $this->getTahun(),
            'dataProviderTriwulan' => $dataProviderTriwulan,
            'dataProviderUrusan' => $dataProviderUrusan,
            'penyerapanRekeningPeriodeIni' => $penyerapanRekeningPeriodeIni,
            'anggaranPenyerapan' => $anggaranPenyerapan
        ]);
    }

    /**
     * Displays a single PenyerapanRekening model.
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
     * Creates a new PenyerapanRekening model.
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

        $rekening3 = Yii::$app->db->createCommand("SELECT CONCAT(kd_rek_1, '.', kd_rek_2, '.', kd_rek_3) AS rek3, CONCAT(kd_rek_1, '.', kd_rek_2, '.', kd_rek_3, ' ', nm_rek_3) AS nm_rek_3 FROM ref_rek_3 WHERE kd_rek_1 IN (4,5,6) AND (kd_rek_1, kd_rek_2) NOT IN ((6,3))")->queryAll();
        $rekening3ArrayList = ArrayHelper::map($rekening3, 'rek3', 'nm_rek_3');

        $model = new PenyerapanRekening();
        $model->bulan = $this->tahun . $this->bulan;
        $model->perwakilan_id = $pemda->perwakilan_id;
        $model->province_id = $pemda->province_id;
        $model->pemda_id = $pemda->id;

        // $modelRekening = [new PenyerapanRekening];
        $modelRekening = [];
        $i = 0;
        foreach ($rekening3 as $value) {
            $modelRekening[$value['rek3']] = new PenyerapanRekening();
            $modelRekening[$value['rek3']]->setAttributes($model->attributes);
            $modelRekening[$value['rek3']]->rek3_gabung = $value['rek3'];
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
                'rekening3ArrayList' => $rekening3ArrayList,
                'rekening3' => $rekening3
            ]);
        }
    }

    /**
     * Updates an existing PenyerapanRekening model.
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
                $return = "";
                if ($model->errors) $return .= $this->setErrorMessage($model->errors);
                return $return;
            }
        } else {
            return $this->renderAjax('_formindividu', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PenyerapanRekening model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {


        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }


    protected function setErrorMessage($errors)
    {
        $return = '<div class="alert alert-warning">';
        foreach ($errors as $key => $data) {
            $return .= $key . ": " . $data['0'] . '<br>';
        }
        $return .= '</div>';
        return $return;
    }

    /**
     * Finds the PenyerapanRekening model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PenyerapanRekening the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PenyerapanRekening::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Delete multiple existing RefPermasalahanPenyerapan model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            // Yii::$app->response->format = Response::FORMAT_JSON;
            // return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
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
