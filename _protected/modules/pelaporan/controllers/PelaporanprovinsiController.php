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

        // global parameters
        $tahun = $this->getTahun();
        $Tahun = $tahun;
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;

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
                    case 5:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.id) FROM
                            (
                                SELECT a.id, a.name, b.no_perkada, b.tanggal_perkada, b.no_sk_satgas, b.tanggal_sk, c.kat_spip, d.no_laporan, d.tgl_laporan, d.nilai_spip
                                FROM ref_pemda a LEFT JOIN
                                    -- part lspips
                                    (
                                        SELECT
                                        a.pemda_id, a.no_perkada, a.tanggal_perkada, a.no_sk_satgas, a.tanggal_sk
                                        FROM lspips a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspips b WHERE b.pemda_id = a.pemda_id)        
                                    ) b ON a.id = b.pemda_id LEFT JOIN
                                    -- part target
                                    (
                                        SELECT
                                        a.pemda_id, (a.kat_spip-1) AS kat_spip
                                        FROM lspip_target a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspip_target b WHERE b.pemda_id = a.pemda_id)        
                                    ) c ON a.id = c.pemda_id LEFT JOIN
                                    -- part laporan
                                    (
                                        SELECT
                                        a.pemda_id, a.no_laporan, a.tgl_laporan, a.nilai_spip, a.f1, a.f2, a.f3, a.f4, a.f5, a.f6, a.f7, a.f8, a.f9, a.f10, a.f11, a.f12, a.f13, a.f14, a.f15, a.f16, a.f17, a.f18, a.f19, a.f20, a.f21, a.f22, a.f23, a.f24, a.f25
                                        FROM lspip_evaluasi a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspip_evaluasi b WHERE b.pemda_id = a.pemda_id)        
                                    ) d ON a.id = d.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ) a
                            ", [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT a.id, a.name, b.no_mou,
                                b.no_mou_pemda, b.tanggal_mou, b.judul, b.ruang_lingkup, b.tanggal_berlaku AS expire
                                FROM ref_pemda a LEFT JOIN
                                    -- part lspips
                                    (
                                        SELECT
                                        *
                                        FROM lmou a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lmou b WHERE b.pemda_id = a.pemda_id)
                                    ) b ON a.id = b.pemda_id
                                WHERE a.id LIKE '%' AND a.perwakilan_id LIKE 1
                                ORDER BY a.id
                                    ",
                            'params' => [
                                ':pemdaId' => '%',
                                // ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan5';
                        break;
                   
                    case 6:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.id) FROM
                            (
                                SELECT a.id, a.name, b.no_perkada, b.tanggal_perkada, b.no_sk_satgas, b.tanggal_sk, c.kat_spip, d.no_laporan, d.tgl_laporan, d.nilai_spip
                                FROM ref_pemda a LEFT JOIN
                                    -- part lspips
                                    (
                                        SELECT
                                        a.pemda_id, a.no_perkada, a.tanggal_perkada, a.no_sk_satgas, a.tanggal_sk
                                        FROM lspips a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspips b WHERE b.pemda_id = a.pemda_id)        
                                    ) b ON a.id = b.pemda_id LEFT JOIN
                                    -- part target
                                    (
                                        SELECT
                                        a.pemda_id, (a.kat_spip-1) AS kat_spip
                                        FROM lspip_target a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspip_target b WHERE b.pemda_id = a.pemda_id)        
                                    ) c ON a.id = c.pemda_id LEFT JOIN
                                    -- part laporan
                                    (
                                        SELECT
                                        a.pemda_id, a.no_laporan, a.tgl_laporan, a.nilai_spip, a.f1, a.f2, a.f3, a.f4, a.f5, a.f6, a.f7, a.f8, a.f9, a.f10, a.f11, a.f12, a.f13, a.f14, a.f15, a.f16, a.f17, a.f18, a.f19, a.f20, a.f21, a.f22, a.f23, a.f24, a.f25
                                        FROM lspip_evaluasi a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspip_evaluasi b WHERE b.pemda_id = a.pemda_id)        
                                    ) d ON a.id = d.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ) a
                            ", [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT a.id, a.name, b.no_perkada, b.tanggal_perkada, b.no_sk_satgas, b.tanggal_sk, c.kat_spip, d.no_laporan, d.tgl_laporan, d.nilai_spip
                                FROM ref_pemda a LEFT JOIN
                                    -- part lspips
                                    (
                                        SELECT
                                        a.pemda_id, a.no_perkada, a.tanggal_perkada, a.no_sk_satgas, a.tanggal_sk
                                        FROM lspips a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspips b WHERE b.pemda_id = a.pemda_id)        
                                    ) b ON a.id = b.pemda_id LEFT JOIN
                                    -- part target
                                    (
                                        SELECT
                                        a.pemda_id, (a.kat_spip-1) AS kat_spip
                                        FROM lspip_target a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspip_target b WHERE b.pemda_id = a.pemda_id)        
                                    ) c ON a.id = c.pemda_id LEFT JOIN
                                    -- part laporan
                                    (
                                        SELECT
                                        a.pemda_id, a.no_laporan, a.tgl_laporan, a.nilai_spip, a.f1, a.f2, a.f3, a.f4, a.f5, a.f6, a.f7, a.f8, a.f9, a.f10, a.f11, a.f12, a.f13, a.f14, a.f15, a.f16, a.f17, a.f18, a.f19, a.f20, a.f21, a.f22, a.f23, a.f24, a.f25
                                        FROM lspip_evaluasi a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lspip_evaluasi b WHERE b.pemda_id = a.pemda_id)        
                                    ) d ON a.id = d.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                                    ",
                            'params' => [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan6';
                        break;
                    case 8:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.id) FROM
                            (
                                SELECT a.id, a.name, b.no_apbd, b.tanggal, b.total_pendapatan, b.total_belanja, b.total_pembiayaan
                                FROM ref_pemda a LEFT JOIN
                                    -- part apbd
                                    (
                                        SELECT
                                        a.pemda_id, a.no_apbd, a.tanggal, a.total_pendapatan, a.total_belanja, a.total_pembiayaan, a.ket
                                        FROM lapbds a
                                        WHERE a.bulan <= :bulan AND a.bulan LIKE CONCAT(:tahun, '%') AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lapbds b WHERE b.pemda_id = a.pemda_id AND b.bulan LIKE CONCAT(:tahun, '%'))        
                                    ) b ON a.id = b.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ) a
                            ", [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT a.id, a.name, b.no_apbd, b.tanggal, b.total_pendapatan, b.total_belanja, b.total_pembiayaan, b.ket
                                FROM ref_pemda a LEFT JOIN
                                    -- part apbd
                                    (
                                        SELECT
                                        a.pemda_id, a.no_apbd, a.tanggal, a.total_pendapatan, a.total_belanja, a.total_pembiayaan, a.ket
                                        FROM lapbds a
                                        WHERE a.bulan <= :bulan AND a.bulan LIKE CONCAT(:tahun, '%') AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lapbds b WHERE b.pemda_id = a.pemda_id AND b.bulan LIKE CONCAT(:tahun, '%'))        
                                    ) b ON a.id = b.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                                    ",
                            'params' => [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan8';
                        break;
                    case 9:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.id) FROM
                            (
                                SELECT a.id, a.name, b.tanggal, b.opini, b.pihak_bantu, b.ket, c.use_keu_pelaporan AS simda
                                FROM ref_pemda a LEFT JOIN
                                    -- part opini
                                    (
                                        SELECT
                                        a.pemda_id, a.tanggal, a.opini_id, d.name AS opini, a.pihak_bantu_susun, c.name AS pihak_bantu, a.ket
                                        FROM llkpd a
                                        LEFT JOIN ref_bantuan c ON a.pihak_bantu_susun = c.id 
                                        LEFT JOIN ref_opini d ON a.opini_id = d.id
                                        WHERE a.bulan <= :bulan AND a.bulan LIKE CONCAT(:tahun, '%') AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM llkpd b WHERE b.pemda_id = a.pemda_id AND b.bulan LIKE CONCAT(:tahun, '%'))        
                                    ) b ON a.id = b.pemda_id LEFT JOIN
                                    -- part simda pelaporan
                                    (
                                        SELECT
                                        a.pemda_id, a.use_keu_pelaporan
                                        FROM lsimdas a
                                        WHERE a.bulan <= :bulan AND  a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lsimdas b WHERE b.pemda_id = a.pemda_id)        
                                    ) c ON a.id = c.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ) a
                            ", [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT a.id, a.name, b.tanggal, b.opini, b.pihak_bantu, b.ket, IFNULL(c.use_keu_pelaporan,0) AS simda, 
                                CASE
                                    WHEN b.tanggal <= ':tahun-03-31' THEN 'TW'
                                    ELSE 'TTW'
                                END AS status
                                FROM ref_pemda a LEFT JOIN
                                    -- part opini
                                    (
                                        SELECT
                                        a.pemda_id, a.tanggal, a.opini_id, d.name AS opini, a.pihak_bantu_susun, c.name AS pihak_bantu, a.ket
                                        FROM llkpd a
                                        LEFT JOIN ref_bantuan c ON a.pihak_bantu_susun = c.id 
                                        LEFT JOIN ref_opini d ON a.opini_id = d.id
                                        WHERE a.bulan <= :bulan AND a.bulan LIKE CONCAT(:tahun, '%') AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM llkpd b WHERE b.pemda_id = a.pemda_id AND b.bulan LIKE CONCAT(:tahun, '%'))        
                                    ) b ON a.id = b.pemda_id LEFT JOIN
                                    -- part simda pelaporan
                                    (
                                        SELECT
                                        a.pemda_id, a.use_keu_pelaporan
                                        FROM lsimdas a
                                        WHERE a.bulan <= :bulan AND  a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lsimdas b WHERE b.pemda_id = a.pemda_id)        
                                    ) c ON a.id = c.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ",
                            'params' => [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan9';
                        break;
                    case 12:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.id) FROM
                            (
                                SELECT a.id, a.name, b.use_keu, b.use_bmd, b.use_gaji, b.use_pendapatan, b.use_perencanaan, b.use_cms, b.ket
                                FROM ref_pemda a LEFT JOIN
                                    -- part simda
                                    (
                                        SELECT
                                        a.pemda_id, a.use_keu, a.use_bmd, a.use_gaji, a.use_pendapatan, a.use_perencanaan, a.use_cms, a.ket
                                        FROM lsimdas a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lsimdas b WHERE b.pemda_id = a.pemda_id)        
                                    ) b ON a.id = b.pemda_id
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ) a
                            ", [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT a.id, a.name, b.use_keu, b.use_bmd, b.use_gaji, b.use_pendapatan, b.use_perencanaan, b.use_cms, b.ket
                                FROM ref_pemda a LEFT JOIN
                                    -- part simda
                                    (
                                        SELECT
                                        a.pemda_id, a.use_keu, a.use_bmd, a.use_gaji, a.use_pendapatan, a.use_perencanaan, a.use_cms, a.ket
                                        FROM lsimdas a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM lsimdas b WHERE b.pemda_id = a.pemda_id)        
                                    ) b ON a.id = b.pemda_id
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ",
                            'params' => [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan12';
                        break;
                    case 13:
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.id) FROM
                            (
                                SELECT a.id, a.name, b.jumlah_desa AS jumlah_desa_alokasi, b.nilai AS nilai_alokasi, c.jumlah_desa AS jumlah_desa_rkud, c.nilai AS nilai_rkud, d.jumlah_desa AS jumlah_desa_rkudesa, d.nilai AS nilai_rkudesa, e.jumlah_desa_implementasi, e.kompilasi
                                FROM ref_pemda a LEFT JOIN
                                    -- part alokasi dana desa
                                    (
                                        SELECT
                                        a.pemda_id, a.jumlah_desa, a.nilai
                                        FROM ldanadesa_alokasi a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND a.pendapatan_desa_id = 2 AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_alokasi b WHERE b.pemda_id = a.pemda_id AND b.tahun = :tahun AND b.pendapatan_desa_id = 2)        
                                    ) b ON a.id = b.pemda_id LEFT JOIN
                                    -- part penyaluran ke RKUD
                                    (
                                        SELECT
                                        a.pemda_id, SUM(a.jumlah_desa) AS jumlah_desa, SUM(a.nilai) AS nilai
                                        FROM ldanadesa_penyaluran_rkud a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND a.pendapatan_desa_id = 2   
                                        GROUP BY a.pemda_id  
                                    ) c ON a.id = c.pemda_id LEFT JOIN
                                    -- part penyaluran ke RKUD
                                    (
                                        SELECT
                                        a.pemda_id, SUM(a.jumlah_desa) AS jumlah_desa, SUM(a.nilai) AS nilai
                                        FROM ldanadesa_penyaluran_rkudesa a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND a.pendapatan_desa_id = 2   
                                        GROUP BY a.pemda_id  
                                    ) d ON a.id = d.pemda_id LEFT JOIN
                                    -- part siskeudes
                                    (
                                        SELECT
                                        a.pemda_id, a.jumlah_desa_implementasi, a.kompilasi
                                        FROM ldanadesa_siskeudes a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId  AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_siskeudes b WHERE b.pemda_id = a.pemda_id)        
                                    ) e ON a.id = e.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ) a
                            ", [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT a.id, a.name, b.jumlah_desa AS jumlah_desa_alokasi, b.nilai AS nilai_alokasi, c.jumlah_desa AS jumlah_desa_rkud, c.nilai AS nilai_rkud, d.jumlah_desa AS jumlah_desa_rkudesa, d.nilai AS nilai_rkudesa, e.jumlah_desa_implementasi, e.kompilasi
                                FROM ref_pemda a LEFT JOIN
                                    -- part alokasi dana desa
                                    (
                                        SELECT
                                        a.pemda_id, a.jumlah_desa, a.nilai
                                        FROM ldanadesa_alokasi a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND a.pendapatan_desa_id = 2 AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_alokasi b WHERE b.pemda_id = a.pemda_id AND b.tahun = :tahun AND b.pendapatan_desa_id = 2)        
                                    ) b ON a.id = b.pemda_id LEFT JOIN
                                    -- part penyaluran ke RKUD
                                    (
                                        SELECT
                                        a.pemda_id, SUM(a.jumlah_desa) AS jumlah_desa, SUM(a.nilai) AS nilai
                                        FROM ldanadesa_penyaluran_rkud a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND a.pendapatan_desa_id = 2   
                                        GROUP BY a.pemda_id  
                                    ) c ON a.id = c.pemda_id LEFT JOIN
                                    -- part penyaluran ke RKUD
                                    (
                                        SELECT
                                        a.pemda_id, SUM(a.jumlah_desa) AS jumlah_desa, SUM(a.nilai) AS nilai
                                        FROM ldanadesa_penyaluran_rkudesa a
                                        WHERE a.bulan <= :bulan AND a.tahun = :tahun AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId AND a.pendapatan_desa_id = 2   
                                        GROUP BY a.pemda_id  
                                    ) d ON a.id = d.pemda_id LEFT JOIN
                                    -- part siskeudes
                                    (
                                        SELECT
                                        a.pemda_id, a.jumlah_desa_implementasi, a.kompilasi
                                        FROM ldanadesa_siskeudes a
                                        WHERE a.bulan <= :bulan AND a.perwakilan_id LIKE :perwakilanId AND a.pemda_id LIKE :pemdaId  AND
                                        a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_siskeudes b WHERE b.pemda_id = a.pemda_id)        
                                    ) e ON a.id = e.pemda_id 
                                WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                ORDER BY a.id
                            ",
                            'params' => [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan13';
                        break;
                    case 14:

                        // Change this with model query asap
                        $totalCount = Yii::$app->db->createCommand("
                            SELECT COUNT(a.id) FROM
                            (
                                SELECT a.id, a.name, a.opini, a.sakip, a.lppd, a.spip, a.kasus, a.pilkada, 
                                (IFNULL(a.opini, 0) + a.sakip + a.lppd + a.spip + a.kasus + a.pilkada) AS skor
                                FROM
                                (
                                    SELECT a.id, a.name,
                                    CASE 
                                        WHEN b.opini_0 IS NULL THEN (b.opini_1 + b.opini_2 + b.opini_3)
                                        ELSE (b.opini_0 + b.opini_1 + b.opini_2)
                                    END AS opini, IFNULL(c.sakip, 0) AS sakip, IFNULL(d.lppd, 0) AS lppd, IFNULL(e.spip, 0) AS spip, IFNULL(f.kasus, 0) AS kasus, IFNULL(g.pilkada, 0) AS pilkada
                                    FROM ref_pemda a LEFT JOIN
                                        -- part opini
                                        (
                                            SELECT b.pemda_id, 
                                            SUM(b.opini_tahun_0) AS opini_0,
                                            SUM(b.opini_tahun_1) AS opini_1,
                                            SUM(b.opini_tahun_2) AS opini_2,
                                            SUM(b.opini_tahun_3) AS opini_3
                                            FROM
                                            (
                                                SELECT pemda_id, 1 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT(:tahun ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                                UNION ALL
                                                SELECT pemda_id, 0 AS opini_tahun_0, 1 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT((:tahun -1) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                                UNION ALL
                                                SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 1 AS opini_tahun_2, 0 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT((:tahun -2) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                                UNION ALL
                                                SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 1 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT((:tahun -3) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                            ) b GROUP BY b.pemda_id
                                        )b ON a.id = b.pemda_id LEFT JOIN
                                        -- part sakip
                                        (
                                            SELECT a.pemda_id, a.sakip FROM
                                            (
                                                SELECT pemda_id, 2 AS sakip 
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_sakip >= 4 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id
                                            UNION ALL
                                            SELECT a.pemda_id, a.sakip FROM
                                            (
                                                SELECT pemda_id, 1 AS sakip 
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_sakip = 3 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id
                                        ) c ON a.id = c.pemda_id LEFT JOIN
                                        -- part lppd
                                        (
                                            SELECT a.pemda_id, a.lppd FROM
                                            (
                                                SELECT pemda_id, 3 AS lppd
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_lppd >= 3 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id        
                                        ) d ON a.id = d.pemda_id LEFT JOIN
                                        -- part spip
                                        (
                                            SELECT a.pemda_id, a.spip FROM
                                            (
                                                SELECT pemda_id, 1 AS spip
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_spip >= 2 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id
                                        ) e ON a.id = e.pemda_id LEFT JOIN 
                                        -- part kasus
                                        (
                                            SELECT pemda_id, -COUNT(id) AS kasus FROM lkasus 
                                            WHERE (bulan LIKE CONCAT(:tahun ,'%') OR bulan LIKE CONCAT((:tahun -1) ,'%') OR bulan LIKE CONCAT((:tahun -2) ,'%')) AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                            GROUP BY pemda_id
                                        ) f ON a.id = f.pemda_id LEFT JOIN 
                                        -- part tahun politik
                                        (
                                            SELECT pemda_id, -COUNT(id) AS pilkada FROM lprofil_pemda
                                            WHERE bulan LIKE CONCAT(:tahun ,'%') AND tahun_politik = 1 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                            GROUP BY pemda_id
                                        ) g ON a.id = g.pemda_id
                                    WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                    ORDER BY a.id
                                ) a HAVING skor like '%'
                            ) a
                            ", [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                // ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ])->queryScalar();

                        $data = new SqlDataProvider([
                            'sql' => "
                                SELECT a.id, a.name, IFNULL(a.opini, 0) AS opini, a.sakip, a.lppd, a.spip, a.kasus, a.pilkada, 
                                (IFNULL(a.opini, 0) + a.sakip + a.lppd + a.spip + a.kasus + a.pilkada) AS skor
                                FROM
                                (
                                    SELECT a.id, a.name,
                                    CASE 
                                        WHEN b.opini_0 IS NULL THEN (b.opini_1 + b.opini_2 + b.opini_3)
                                        ELSE (b.opini_0 + b.opini_1 + b.opini_2)
                                    END AS opini, IFNULL(c.sakip, 0) AS sakip, IFNULL(d.lppd, 0) AS lppd, IFNULL(e.spip, 0) AS spip, IFNULL(f.kasus, 0) AS kasus, IFNULL(g.pilkada, 0) AS pilkada
                                    FROM ref_pemda a LEFT JOIN
                                        -- part opini
                                        (
                                            SELECT b.pemda_id, 
                                            SUM(b.opini_tahun_0) AS opini_0,
                                            SUM(b.opini_tahun_1) AS opini_1,
                                            SUM(b.opini_tahun_2) AS opini_2,
                                            SUM(b.opini_tahun_3) AS opini_3
                                            FROM
                                            (
                                                SELECT pemda_id, 1 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT(:tahun ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                                UNION ALL
                                                SELECT pemda_id, 0 AS opini_tahun_0, 1 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT((:tahun -1) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                                UNION ALL
                                                SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 1 AS opini_tahun_2, 0 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT((:tahun -2) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                                UNION ALL
                                                SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 1 AS opini_tahun_3 
                                                FROM llkpd WHERE bulan LIKE CONCAT((:tahun -3) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                                GROUP BY pemda_id
                                            ) b GROUP BY b.pemda_id
                                        )b ON a.id = b.pemda_id LEFT JOIN
                                        -- part sakip
                                        (
                                            SELECT a.pemda_id, a.sakip FROM
                                            (
                                                SELECT pemda_id, 2 AS sakip 
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_sakip >= 4 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id
                                            UNION ALL
                                            SELECT a.pemda_id, a.sakip FROM
                                            (
                                                SELECT pemda_id, 1 AS sakip 
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_sakip = 3 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id
                                        ) c ON a.id = c.pemda_id LEFT JOIN
                                        -- part lppd
                                        (
                                            SELECT a.pemda_id, a.lppd FROM
                                            (
                                                SELECT pemda_id, 3 AS lppd
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_lppd >= 3 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id        
                                        ) d ON a.id = d.pemda_id LEFT JOIN
                                        -- part spip
                                        (
                                            SELECT a.pemda_id, a.spip FROM
                                            (
                                                SELECT pemda_id, 1 AS spip
                                                FROM levals WHERE bulan LIKE CONCAT(:tahun ,'%') AND kat_spip >= 2 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId ORDER BY id DESC
                                            ) a GROUP BY a.pemda_id
                                        ) e ON a.id = e.pemda_id LEFT JOIN 
                                        -- part kasus
                                        (
                                            SELECT pemda_id, -COUNT(id) AS kasus FROM lkasus 
                                            WHERE (bulan LIKE CONCAT(:tahun ,'%') OR bulan LIKE CONCAT((:tahun -1) ,'%') OR bulan LIKE CONCAT((:tahun -2) ,'%')) AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                            GROUP BY pemda_id
                                        ) f ON a.id = f.pemda_id LEFT JOIN 
                                        -- part tahun politik
                                        (
                                            SELECT pemda_id, -COUNT(id) AS pilkada FROM lprofil_pemda
                                            WHERE bulan LIKE CONCAT(:tahun ,'%') AND tahun_politik = 1 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                                            GROUP BY pemda_id
                                        ) g ON a.id = g.pemda_id
                                    WHERE a.id LIKE :pemdaId AND a.perwakilan_id LIKE :perwakilanId
                                    ORDER BY a.id
                                ) a HAVING skor like '%'
                            ",
                            'params' => [
                                ':pemdaId' => '%',
                                ':tahun' => $Tahun,
                                // ':bulan' => $tahunBulan,
                                ':perwakilanId' => $perwakilan_id,
                            ],
                            'totalCount' => $totalCount,
                            //'sort' =>false, to remove the table header sorting
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]);
                        $render = 'laporan14';
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
