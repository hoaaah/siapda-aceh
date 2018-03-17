<?php

namespace app\modules\pelaporan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk DJPK Kemenkeu.*/

class PelaporanprovinsiController extends Controller
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

    protected function getViewCompilation($model, $getParam)
    {
        switch ($getParam['kd_laporan']) {
            case 2:
                return $model->andWhere('kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id LIKE :wilayah_id)', [':wilayah_id' => $getParam['kd_wilayah']]);
                break;
            case 3:
                return $model->andWhere(['kd_provinsi' => $getParam['kd_provinsi']]);
                break;
            case 4:
                return $model->andWhere(['kd_pemda' => $getParam['kd_pemda']]);
                break;
            
            default:
                return false;
                break;
        }
        return false;
    }

    public function actionView($id){
        IF($this->cekakses() !== true){
            Yii::$app->getSession()->setFlash('warning',  'Anda tidak memiliki hak akses');
            return $this->redirect(Yii::$app->request->referrer);
        }    
        IF(Yii::$app->session->get('tahun'))
        {
            $Tahun = Yii::$app->session->get('tahun');
        }ELSE{
            $Tahun = DATE('Y');
        }
        try {
            list($kd_rek_1, $kd_rek_2, $kd_rek_3) = explode('.',$id);
        } catch (Exception $e) {
            throw new Exception("Error Processing Request: ".$e->getMessage(), 1);
        }
        $getParam = Yii::$app->request->queryParams;
        $model = \app\models\CompilationRecord5::find()->where(['tahun' => $Tahun, 'periode_id' => $getParam['periode_id'], 'kd_rek_1' => $kd_rek_1, 'kd_rek_2' => $kd_rek_2, 'kd_rek_3' => $kd_rek_3])->select(['kd_pemda', 'kd_rek_1', 'kd_rek_2', 'kd_rek_3', 'nm_rek_3', "SUM(realisasi) AS realisasi"])->groupBy(['kd_pemda', 'kd_rek_1', 'kd_rek_2', 'kd_rek_3', 'nm_rek_3']);
        if($this->getViewCompilation($model, $getParam)){
            $model = $this->getViewCompilation($model, $getParam);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
        ]);
        $heading = "Kode Akun: $id";
        return $this->renderAjax('view', [
            'heading' => $heading,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex()
    {
        IF($this->cekakses() !== true){
            Yii::$app->getSession()->setFlash('warning',  'Anda tidak memiliki hak akses');
            return $this->redirect(Yii::$app->request->referrer);
        }    
        IF(Yii::$app->session->get('tahun'))
        {
            $Tahun = Yii::$app->session->get('tahun');
        }ELSE{
            $Tahun = DATE('Y');
        }

        $get = new \app\models\Laporan();
        $Kd_Laporan = NULL;
        $data = NULL;
        $data1 = NULL;
        $data2 = NULL;
        $data3 = NULL;
        $data4 = NULL;
        $data5 = NULL;
        $data6 = NULL;
        $render = NULL;
        $getparam = NULL;
        $totalPemda = NULL;
        IF(Yii::$app->request->queryParams){
            $getparam = Yii::$app->request->queryParams;
            // this is for array in pemda
            // $kd_pemda_params = NULL;
            // foreach($getparam['Laporan']['kd_pemda'] as $data){
            //     $kd_pemda_params = $kd_pemda_params.$data.',';
            // }            
            // if(!($getparam['Laporan']['kd_pemda']) || in_array('%', $getparam['Laporan']['kd_pemda'])){
            //     $getparam['Laporan']['kd_pemda'] = \app\models\RefPemda::find()->select(['id'])->asArray()->all();
            //     $kd_pemda_params = NULL;
            //     foreach($getparam['Laporan']['kd_pemda'] as $data){
            //         $kd_pemda_params = $kd_pemda_params.$data['id'].',';
            //     }
            // }
            // $kd_pemda_params = substr($kd_pemda_params, 0, -1);            
            IF($getparam['Laporan']['Kd_Laporan']){
                $Kd_Laporan = Yii::$app->request->queryParams['Laporan']['Kd_Laporan'];
                switch ($Kd_Laporan) {
                    case 1:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.kd_rek_1) FROM
                            (
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                            ) a
                            ", [
                                ':transfer_id' => 3,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                                    ",
                            'params' => [
                                ':transfer_id' => 3,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan1';
                        break;
                    case 2:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.kd_rek_1) FROM
                            (
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7) AND A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND B.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                            ) a
                            ", [
                                ':transfer_id' => 2,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':wilayah_id' => $getparam['Laporan']['kd_wilayah'],
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7) AND A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND B.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                                    ",
                            'params' => [
                                ':transfer_id' => 2,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':wilayah_id' => $getparam['Laporan']['kd_wilayah'],
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan1';
                        break;  
                    case 3:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.kd_rek_1) FROM
                            (
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7) AND A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND B.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                            ) a
                            ", [
                                ':transfer_id' => 1,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':province_id' => $getparam['Laporan']['kd_provinsi'],
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7) AND A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND B.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                                    ",
                            'params' => [
                                ':transfer_id' => 1,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':province_id' => $getparam['Laporan']['kd_provinsi'],
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan1';
                        break;
                    case 4:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.kd_rek_1) FROM
                            (
                                SELECT
                                a.kd_rek_1, a.kd_rek_2, a.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(a.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5, SUM(A.realisasi) AS realisasi
                                    FROM compilation_record5 A
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND 
                                        A.kd_pemda = :pemda_id AND A.kd_rek_1 IN (4,5,6,7)
                                    GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5       
                                ) a
                                LEFT JOIN
                                ref_akrual_3 b ON a.kd_rek_1 = b.kd_akrual_1 AND a.kd_rek_2 = b.kd_akrual_2 AND a.kd_rek_3 = b.kd_akrual_3
                                GROUP BY a.kd_rek_1, a.kd_rek_2, a.kd_rek_3, b.nm_akrual_3
                                ORDER BY a.kd_rek_1, a.kd_rek_2, a.kd_rek_3
                            ) a
                            ", [
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':pemda_id' => $getparam['Laporan']['kd_pemda'],
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT
                                a.kd_rek_1, a.kd_rek_2, a.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(a.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5, SUM(A.realisasi) AS realisasi
                                    FROM compilation_record5 A
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND 
                                        A.kd_pemda = :pemda_id AND A.kd_rek_1 IN (4,5,6,7)
                                    GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5       
                                ) a
                                LEFT JOIN
                                ref_akrual_3 b ON a.kd_rek_1 = b.kd_akrual_1 AND a.kd_rek_2 = b.kd_akrual_2 AND a.kd_rek_3 = b.kd_akrual_3
                                GROUP BY a.kd_rek_1, a.kd_rek_2, a.kd_rek_3, b.nm_akrual_3
                                ORDER BY a.kd_rek_1, a.kd_rek_2, a.kd_rek_3
                                    ",
                            'params' => [
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':pemda_id' => $getparam['Laporan']['kd_pemda'],
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan1';
                        break;                                              
                    case 5:
                        $query = \app\models\EliminationAccount::find()->where(['tahun' => $Tahun,])->andWhere('kd_rek_1 IN (4,5,6,7)');
                        switch ($getparam['Laporan']['elimination_level']) {
                            case 1:
                                $pemda = \app\models\RefPemda::find()->select('id')->where(['province_id' => $getparam['Laporan']['kd_provinsi']])->asArray()->all();
                                $arrayPemda = ArrayHelper::getColumn($pemda, 'id');
                                if(count($arrayPemda) != 0){
                                    $stringArrayPemda = implode(',', $arrayPemda);
                                    $query->andWhere("kd_pemda IN($stringArrayPemda)");
                                }
                                break;
                            case 2:
                                $pemda = \app\models\PemdaWilayah::find()->select('pemda_id')->where(['wilayah_id' => $getparam['Laporan']['kd_wilayah']])->asArray()->all();
                                $arrayPemda = ArrayHelper::getColumn($pemda, 'pemda_id');
                                if(count($arrayPemda) != 0){
                                    $stringArrayPemda = implode(',', $arrayPemda);
                                    $query->andWhere("kd_pemda IN($stringArrayPemda)");
                                }
                                break;
                            case 3:
                                if(Yii::$app->user->identity->pemda_id){
                                    $pemda = \app\models\RefPemda::find()->select('id')->where(['province_id' => Yii::$app->user->identity->refPemda->province_id])->asArray()->all();
                                    $arrayPemda = ArrayHelper::getColumn($pemda, 'id');
                                    if(count($arrayPemda) != 0){
                                        $stringArrayPemda = implode(',', $arrayPemda);
                                        $query->andWhere("kd_pemda IN($stringArrayPemda)");
                                    }
                                }
                                break;                            
                            
                            default:
                                # code...
                                break;
                        }
                        $data = new ActiveDataProvider([
                            'query' => $query->orderBy('transfer_id, kd_pemda'),
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan2';
                        break;                                                      

                    default:
                        # code...
                        break;
                }
                $totalPemda = Yii::$app->db->createCommand("
                    SELECT COUNT(a.kd_pemda) FROM (
                        SELECT kd_pemda
                        FROM compilation_record5 
                        WHERE tahun = :tahun 
                        AND periode_id = :periode_id 
                        AND kd_rek_1 IN (4,5,6,7) 
                        AND kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id LIKE :wilayah_id) 
                        AND kd_provinsi LIKE :province_id
                        AND kd_pemda LIKE :pemda_id
                        GROUP BY kd_pemda
                    ) a
                ",[
                    ':tahun' => $Tahun,
                    ':periode_id' => $getparam['Laporan']['periode_id'],
                    ':wilayah_id' => $getparam['Laporan']['Kd_Laporan'] == 2 ?  $getparam['Laporan']['kd_wilayah'] : '%',
                    ':province_id' => $getparam['Laporan']['Kd_Laporan'] == 3 ?  $getparam['Laporan']['kd_provinsi'] : '%',
                    ':pemda_id' => $getparam['Laporan']['Kd_Laporan'] == 4 ?  $getparam['Laporan']['kd_pemda'] : '%',
                ])->queryScalar();
                if($getparam['Laporan']['Kd_Laporan'] == 5) $totalPemda = null;
            }

        }

        return $this->render('index', [
            'get' => $get,
            'Kd_Laporan' => $Kd_Laporan,
            'data' => $data,
            'data1' => $data1,
            'data2' => $data2,
            'data3' => $data3,
            'data4' => $data4,
            'data5' => $data5,
            'data6' => $data6,
            'render' => $render,
            'getparam' => $getparam,
            'Tahun' => $Tahun,
            'totalPemda' => $totalPemda
        ]);
    }


    public function actionCetak()
    {
        IF($this->cekakses() !== true){
            Yii::$app->getSession()->setFlash('warning',  'Anda tidak memiliki hak akses');
            return $this->redirect(Yii::$app->request->referrer);
        }    
        IF(Yii::$app->session->get('tahun'))
        {
            $Tahun = Yii::$app->session->get('tahun');
        }ELSE{
            $Tahun = DATE('Y');
        }

        $get = new \app\models\Laporan();
        $Kd_Laporan = NULL;
        $data = NULL;
        $data1 = NULL;
        $data2 = NULL;
        $data3 = NULL;
        $data4 = NULL;
        $data5 = NULL;
        $data6 = NULL;
        $render = NULL;
        $getparam = NULL;
        IF(Yii::$app->request->queryParams){
            $getparam = Yii::$app->request->queryParams;         
            IF($getparam['Laporan']['Kd_Laporan']){
                $Kd_Laporan = Yii::$app->request->queryParams['Laporan']['Kd_Laporan'];
                switch ($Kd_Laporan) {
                    case 1:
                        $data = Yii::$app->db->createCommand("
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                                    ")
                        ->bindValues([
                            ':transfer_id' => 3,
                            ':tahun' => $Tahun,
                            ':periode_id' => $getparam['Laporan']['periode_id'],
                        ])->queryAll();

                        $render = 'cetaklaporan1';
                        break;
                    case 2:
                        $data = Yii::$app->db->createCommand("
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7) AND A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND B.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.kd_pemda IN (SELECT pemda_id FROM pemda_wilayah WHERE wilayah_id = :wilayah_id) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                                    ")
                            ->bindValues([
                                ':transfer_id' => 2,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':wilayah_id' => $getparam['Laporan']['kd_wilayah'],
                            ])->queryAll();
                        $render = 'cetaklaporan1';
                        break;  
                    case 3:
                        $data = Yii::$app->db->createCommand("
                                SELECT
                                c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(c.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A 
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7) AND A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id)
                                ) c 
                                LEFT JOIN
                                (
                                    SELECT A.*
                                    FROM compilation_record5 A LEFT OUTER JOIN
                                        (
                                        SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        FROM compilation_record5 A,
                                            elimination_account B
                                        WHERE (B.transfer_id <= :transfer_id) AND A.tahun = :tahun AND B.tahun = :tahun AND A.periode_id = :periode_id AND 
                                            A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND B.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND 
                                            (A.tahun = B.tahun) AND (A.kd_pemda = B.kd_pemda) AND (A.kd_rek_1 = B.kd_rek_1) AND (A.kd_rek_2 = B.kd_rek_2) AND (A.kd_rek_3 = B.kd_rek_3) 
                                            AND ((B.kd_rek_4 = 0)
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (B.kd_rek_4 <> 0) AND (B.kd_rek_5 = 0))
                                            OR ((A.kd_rek_4 = B.kd_rek_4) AND (A.kd_rek_5 = B.kd_rek_5) AND (B.kd_rek_5 <> 0)))
                                        GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5
                                        ) B ON A.tahun = B.tahun AND A.kd_pemda = B.kd_pemda AND A.kd_rek_1 = B.kd_rek_1 AND A.kd_rek_2 = B.kd_rek_2 AND A.kd_rek_3 = B.kd_rek_3 AND A.kd_rek_4 = B.kd_rek_4 AND A.kd_rek_5 = B.kd_rek_5
                                    WHERE (B.tahun IS NULL) AND A.kd_pemda IN (SELECT id FROM ref_pemda WHERE province_id = :province_id) AND A.tahun = :tahun AND A.periode_id = :periode_id AND A.kd_rek_1 IN (4,5,6,7)
                                ) a ON a.tahun = c.tahun AND a.kd_provinsi = c.kd_provinsi AND a.kd_pemda = c.kd_pemda AND a.periode_id = c.periode_id AND a.perubahan_id = c.perubahan_id AND 
                                a.kd_rek_1 = c.kd_rek_1 AND a.kd_rek_2 = c.kd_rek_2 AND a.kd_rek_3 = c.kd_rek_3 AND a.kd_rek_4 = c.kd_rek_4 AND a.kd_rek_5 = c.kd_rek_5
                                LEFT JOIN
                                ref_akrual_3 b ON c.kd_rek_1 = b.kd_akrual_1 AND c.kd_rek_2 = b.kd_akrual_2 AND c.kd_rek_3 = b.kd_akrual_3
                                GROUP BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3, b.nm_akrual_3 
                                ORDER BY c.kd_rek_1, c.kd_rek_2, c.kd_rek_3
                                    ")
                            ->bindValues([
                                ':transfer_id' => 1,
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':province_id' => $getparam['Laporan']['kd_provinsi'],
                            ])->queryAll();
                        $render = 'cetaklaporan1';
                        break;
                    case 4:
                        $data = Yii::$app->db->createCommand("
                                SELECT
                                a.kd_rek_1, a.kd_rek_2, a.kd_rek_3, IFNULL(b.nm_akrual_3, '[--Rekening Tidak Terdaftar--]' )AS nm_akrual_3, SUM(a.realisasi) AS realisasi_sebelum, SUM(a.realisasi) AS realisasi_sesudah
                                FROM
                                (
                                    SELECT A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5, SUM(A.realisasi) AS realisasi
                                    FROM compilation_record5 A
                                    WHERE A.tahun = :tahun AND A.periode_id = :periode_id AND 
                                        A.kd_pemda = :pemda_id AND A.kd_rek_1 IN (4,5,6,7)
                                    GROUP BY A.tahun, A.kd_pemda, A.kd_rek_1, A.kd_rek_2, A.kd_rek_3, A.kd_rek_4, A.kd_rek_5       
                                ) a
                                LEFT JOIN
                                ref_akrual_3 b ON a.kd_rek_1 = b.kd_akrual_1 AND a.kd_rek_2 = b.kd_akrual_2 AND a.kd_rek_3 = b.kd_akrual_3
                                GROUP BY a.kd_rek_1, a.kd_rek_2, a.kd_rek_3, b.nm_akrual_3
                                ORDER BY a.kd_rek_1, a.kd_rek_2, a.kd_rek_3
                                    ")
                            ->bindValues([
                                ':tahun' => $Tahun,
                                ':periode_id' => $getparam['Laporan']['periode_id'],
                                ':pemda_id' => $getparam['Laporan']['kd_pemda'],
                            ])->queryAll();
                        $render = 'cetaklaporan1';
                        break;                                              
                    case 5:
                        $query = \app\models\EliminationAccount::find()->where(['tahun' => $Tahun,])->andWhere('kd_rek_1 IN (4,5,6,7)');
                        switch ($getparam['Laporan']['elimination_level']) {
                            case 1:
                                $pemda = \app\models\RefPemda::find()->select('id')->where(['province_id' => $getparam['Laporan']['kd_provinsi']])->asArray()->all();
                                $arrayPemda = ArrayHelper::getColumn($pemda, 'id');
                                if(count($arrayPemda) != 0){
                                    $stringArrayPemda = implode(',', $arrayPemda);
                                    $query->andWhere("kd_pemda IN($stringArrayPemda)");
                                }
                                break;
                            case 2:
                                $pemda = \app\models\PemdaWilayah::find()->select('pemda_id')->where(['wilayah_id' => $getparam['Laporan']['kd_wilayah']])->asArray()->all();
                                $arrayPemda = ArrayHelper::getColumn($pemda, 'pemda_id');
                                if(count($arrayPemda) != 0){
                                    $stringArrayPemda = implode(',', $arrayPemda);
                                    $query->andWhere("kd_pemda IN($stringArrayPemda)");
                                }
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                        $data = new ActiveDataProvider([
                            'query' => $query->orderBy('transfer_id, kd_pemda'),
                            'pagination' => [
                                'pageSize' => 0,
                            ],
                        ]);
                        $render = 'laporan2';
                        break;                                                      

                    default:
                        # code...
                        break;
                }
            }

        }

        return $this->render($render, [
            'get' => $get,
            'Kd_Laporan' => $Kd_Laporan,
            'data' => $data,
            'data1' => $data1,
            'data2' => $data2,
            'data3' => $data3,
            'data4' => $data4,
            'data5' => $data5,
            'data6' => $data6,
            'render' => $render,
            'getparam' => $getparam,
            'Tahun' => $Tahun,
        ]);
    }    


    protected function cekakses(){

        IF(Yii::$app->user->identity){
            $akses = \app\models\RefUserMenu::find()->where(['kd_user' => Yii::$app->user->identity->kd_user, 'menu' => 602])->one();
            IF($akses){
                return true;
            }else{
                return false;
            }
        }ELSE{
            return false;
        }
    }      
}
