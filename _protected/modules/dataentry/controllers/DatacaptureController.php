<?php

namespace app\modules\dataentry\controllers;

use Yii;
use app\models\RefPemda;
use app\models\RefBantuan;
use app\modules\dataentry\models\RefPemdaSearch;
use app\models\Llkpd;
use app\models\Lmou;
use app\modules\dataentry\models\LlkpdSearch;
use app\models\Lapbul;
use app\models\Lapbds;
use app\modules\dataentry\models\LapbdsSearch;
use app\models\LprofilPemda;
use app\modules\dataentry\models\LprofilPemdaSearch;
use app\models\Lkada;
use app\modules\dataentry\models\LkadaSearch;
use app\models\Levals;
use app\modules\dataentry\models\LevalsSearch;
use app\models\Lkasus;
use app\modules\dataentry\models\LkasusSearch;
use app\models\SkorPemda;
use app\models\Lappds;
use app\modules\dataentry\models\LappdsSearch;
use app\models\Lsimdas;
use app\modules\dataentry\models\LsimdasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * DatacaptureController implements the CRUD actions for RefPemda model.
 */
class DatacaptureController extends Controller
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
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
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
     * Lists all RefPemda models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $perwakilan_id = Yii::$app->user->identity->perwakilan_id;

        $searchModel = new RefPemdaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if($perwakilan_id) $dataProvider->query->andWhere(['perwakilan_id' => $perwakilan_id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RefPemda model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;
        
        // data pemda
        $model = $this->findModel($id);
        $lapbul = Lapbul::find()->where(['perwakilan_id' => $model->perwakilan_id, 'bulan' => $tahunBulan])->orderBy('bulan DESC')->one();

        // data MoU
        $mou =  Lmou::find()->where(['pemda_id' => $id])
        // ->andWhere("bulan LIKE '$tahun%'")
        ->andWhere("bulan <= $tahunBulan")
        ->orderBy('id DESC')->one();

        // data profil
        $profilPemda =  LprofilPemda::find()->where(['pemda_id' => $id])
        // ->andWhere("bulan LIKE '$tahun%'")
        ->andWhere("bulan <= $tahunBulan")
        ->orderBy('id DESC')->one();

        // daftar profil tahun ini
        $searchModelProfil = new LprofilPemdaSearch();
        $dataProviderProfil = $searchModelProfil->search(Yii::$app->request->queryParams);
        $dataProviderProfil->query->andWhere(['pemda_id' => $id]);
        // $dataProviderProfil->query->andWhere("bulan LIKE '$tahun%'");
        $dataProviderProfil->query->andWhere("bulan <= $tahunBulan");
        $dataProviderProfil->query->orderBy('bulan DESC');
        $dataProviderProfil->pagination->pageParam = 'profil-page';
        $dataProviderProfil->sort->sortParam = 'profil-sort';

        // data kada
        $kada =  Lkada::find()->where(['pemda_id' => $id, 'status_kada' => 1])
        // ->andWhere("bulan LIKE '$tahun%'")
        ->andWhere("bulan <= $tahunBulan")
        ->orderBy('id DESC')->one();
        $waKada =  Lkada::find()->where(['pemda_id' => $id, 'status_kada' => 2])
        // ->andWhere("bulan LIKE '$tahun%'")
        ->andWhere("bulan <= $tahunBulan")
        ->orderBy('id DESC')->one();

        // daftar kada tahun ini
        $searchModelKada = new LkadaSearch();
        $dataProviderKada = $searchModelKada->search(Yii::$app->request->queryParams);
        $dataProviderKada->query->andWhere(['pemda_id' => $id]);
        // $dataProviderProfil->query->andWhere("bulan LIKE '$tahun%'");
        $dataProviderKada->query->andWhere("bulan <= $tahunBulan");
        $dataProviderKada->query->orderBy('bulan DESC');
        $dataProviderKada->pagination->pageParam = 'kada-page';
        $dataProviderKada->sort->sortParam = 'kada-sort';

        // data opini
        $opini =  Llkpd::find()->where(['pemda_id' => $id])
        ->andWhere("bulan LIKE '$tahun%'")
        ->andWhere("bulan <= $tahunBulan")
        ->orderBy('id DESC')->one();

        // daftar opini tahun ini
        $searchModelOpini = new LlkpdSearch();
        $dataProviderOpini = $searchModelOpini->search(Yii::$app->request->queryParams);
        $dataProviderOpini->query->andWhere(['pemda_id' => $id]);
        $dataProviderOpini->query->andWhere("bulan LIKE '$tahun%'");
        $dataProviderOpini->query->andWhere("bulan <= $tahunBulan");
        $dataProviderOpini->query->orderBy('bulan DESC');
        $dataProviderOpini->pagination->pageParam = 'opini-page';
        $dataProviderOpini->sort->sortParam = 'opini-sort';

        // data APBD
        $apbd =  Lapbds::find()->where(['pemda_id' => $id])
        ->andWhere("bulan LIKE '$tahun%'")
        ->andWhere("bulan <= $tahunBulan")
        ->orderBy('id DESC')->one();
        
        // daftar APBD tahun ini
        $searchModelApbd = new LapbdsSearch();
        $dataProviderApbd = $searchModelApbd->search(Yii::$app->request->queryParams);
        $dataProviderApbd->query->andWhere(['pemda_id' => $id]);
        $dataProviderApbd->query->andWhere("bulan LIKE '$tahun%'");
        $dataProviderApbd->query->andWhere("bulan <= $tahunBulan");
        $dataProviderApbd->query->orderBy('bulan DESC');
        $dataProviderApbd->pagination->pageParam = 'apbd-page';
        $dataProviderApbd->sort->sortParam = 'apbd-sort';

        // data Evaluasi
        $evaluasi =  Levals::find()->where(['pemda_id' => $id])
        ->andWhere("bulan LIKE '$tahun%'")
        ->andWhere("bulan <= $tahunBulan")
        ->orderBy('id DESC')->one();
        
        // daftar Evaluasi tahun ini
        $searchModelEvaluasi = new LevalsSearch();
        $dataProviderEvaluasi = $searchModelEvaluasi->search(Yii::$app->request->queryParams);
        $dataProviderEvaluasi->query->andWhere(['pemda_id' => $id]);
        $dataProviderEvaluasi->query->andWhere("bulan LIKE '$tahun%'");
        $dataProviderEvaluasi->query->andWhere("bulan <= $tahunBulan");
        $dataProviderEvaluasi->query->orderBy('bulan DESC');
        $dataProviderEvaluasi->pagination->pageParam = 'evaluasi-page';
        $dataProviderEvaluasi->sort->sortParam = 'evaluasi-sort';     
        
        // daftar Kasus tahun ini
        $searchModelKasus = new LKasusSearch();
        $dataProviderKasus = $searchModelKasus->search(Yii::$app->request->queryParams);
        $dataProviderKasus->query->andWhere(['pemda_id' => $id]);
        // tampilkan 3 tahun terakhir saja
        $tahun3Lalu = $tahun - 3;
        $dataProviderKasus->query->andWhere("bulan >= '$tahun3Lalu\01'");
        $dataProviderKasus->query->andWhere("bulan <= $tahunBulan");
        $dataProviderKasus->query->orderBy('bulan DESC');
        $dataProviderKasus->pagination->pageParam = 'kasus-page';
        $dataProviderKasus->sort->sortParam = 'kasus-sort';     

        // skor Pemda 
        $skorPemdaClass = new SkorPemda();
        $skorPemda = $skorPemdaClass->skorPemda($tahun, $model->perwakilan_id, $model->id);
        $rekomendasi = $skorPemda >= 7 ? "Re-Assesment" : "Bimtek SPIP";

        // daftar Penyampaian SAKIP dan LPPD tahun ini
        $searchModelLappd = new LappdsSearch();
        $dataProviderLappd = $searchModelLappd->search(Yii::$app->request->queryParams);
        $dataProviderLappd->query->andWhere(['pemda_id' => $id]);
        $dataProviderLappd->query->andWhere("bulan LIKE '$tahun%'");
        $dataProviderLappd->query->andWhere("bulan <= $tahunBulan");
        $dataProviderLappd->query->orderBy('bulan DESC');
        $dataProviderLappd->pagination->pageParam = 'lappd-page';
        $dataProviderLappd->sort->sortParam = 'lappd-sort';
        
        // daftar pengguna Simda tahun ini
        $searchModelSimda = new LsimdasSearch();
        $dataProviderSimda = $searchModelSimda->search(Yii::$app->request->queryParams);
        $dataProviderSimda->query->andWhere(['pemda_id' => $id]);
        $dataProviderSimda->query->andWhere("bulan LIKE '$tahun%'");
        $dataProviderSimda->query->andWhere("bulan <= $tahunBulan");
        $dataProviderSimda->query->orderBy('bulan DESC');
        $dataProviderSimda->pagination->pageParam = 'simda-page';
        $dataProviderSimda->sort->sortParam = 'simda-sort';   

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "RefPemda #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'pemda' => $model,
                'tahunBulan' => $tahunBulan,
                'lapbul' => $lapbul,
                'opini' => $opini,
                'apbd' => $apbd,
                'profilPemda' => $profilPemda,
                'kada' => $kada,
                'waKada' => $waKada,
                'evaluasi' => $evaluasi,
                'mou' => $mou,
                'dataProviderProfil' => $dataProviderProfil,
                'dataProviderOpini' => $dataProviderOpini,
                'dataProviderApbd' => $dataProviderApbd,
                'dataProviderKada' => $dataProviderKada,
                'dataProviderEvaluasi' => $dataProviderEvaluasi,
                'dataProviderKasus' => $dataProviderKasus,
                'dataProviderLappd' => $dataProviderLappd,
                'dataProviderSimda' => $dataProviderSimda,
                'skorPemda' => $skorPemda,
                'rekomendasi' => $rekomendasi,
            ]);
        }
    }

    public function actionPemda($id)
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
                if($image && ($image->name != $model->image_name)){
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
            return $this->renderAjax('_formPemda', [
                'model' => $model,
            ]);
        }
    }

    public function actionMou($id)
    {
        $pemda = RefPemda::findOne($id);
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;

        $pemda = $this->findModel($id);
        $latestMou = Lmou::find()->where(['pemda_id' => $id])->orderBy('id DESC')->one();
        if($tahunBulan == $latestMou['bulan']){
            $model = $latestMou;
        }else{
            $model = new Lmou();
            $model->bulan = $this->tahun.$this->bulan;
            $model->perwakilan_id = $pemda->perwakilan_id;
            $model->province_id = $pemda->province_id;
            $model->pemda_id = $pemda->id;
        }


        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
            }
            IF($model->save()){
                // return 1;
                return $this->redirect(Yii::$app->request->referrer);
            }ELSE{
                var_dump($model->getErrors());
                // return 0;
            }
        } else {
            return $this->renderAjax('_formMou', [
                'model' => $model,
            ]);
        }
    }    

    public function actionDeleteImage($id)
    {
        $model = RefPemda::findOne($id);
        if(!$model->deleteImage()){
            return false;
        }
        return true;
    }

    protected function findModel($id)
    {
        if (($model = RefPemda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
