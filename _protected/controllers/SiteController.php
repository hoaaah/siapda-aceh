<?php
namespace app\controllers;

use app\models\User;
use app\models\LoginForm;
use app\models\AccountActivation;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk DJPK Kemenkeu.*/

/**
 * Site controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, and password reset.
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
    

    //Choose what year this application will use as default year --@hoaaah
    public function actionTahun($id)
    {
        $session = Yii::$app->session;
        IF($session['tahun']){
            $session->remove('tahun');
        }
        $session->set('tahun', $id);


        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionBulan($id)
    {
        $session = Yii::$app->session;
        IF($session['bulan']){
            $session->remove('bulan');
        }
        $session->set('bulan', $id);


        return $this->redirect(Yii::$app->request->referrer);
    }
//------------------------------------------------------------------------------------------------//
// STATIC PAGES
//------------------------------------------------------------------------------------------------//

    public function actionCoba(){

        $client = new \yii\httpclient\Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://localhost:8000/siapda-aceh/id-kabkot.json')
            // ->setData(['name' => 'John Doe', 'email' => 'johndoe@example.com'])
            ->send();
        if ($response->isOk) {
            $data = $response->data;
        }        
        
        // return var_dump($data['features'][122]['geometry']['coordinates'][0]);

        $model = $data['features'];
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $model,
            'pagination' => [
            'pageSize' => 100,
        ],
            'sort' => [
                'attributes' => ['id'],
            ],
        ]);

        return $this->render('coba',[
            'dataProvider' => $dataProvider
        ]);        
    }

    public function actionIndex()
    {
        // global parameters
        $tahun = $this->getTahun();
        $bulan = $this->getBulan();
        $tahunBulan = $tahun.$bulan;

        $sticky = \app\models\TaPengumuman::find()->where(['published' => 1, 'sticky' => 1]);
        $pengumuman = \app\models\TaPengumuman::find()->where(['published' => 1, 'sticky' => 0]);
        $sticky->andWhere('diumumkan_di = 3 OR diumumkan_di = 2');
        $sticky = count($sticky->all());
        $pengumuman->andWhere('diumumkan_di = 3 OR diumumkan_di = 2');
        $pengumuman = $pengumuman->orderBy('id DESC');
        // $pengumuman = $pengumuman;
        $dataProvider = new ActiveDataProvider([
            'query' => $pengumuman,
            'pagination' => [
                'pageSize' => $sticky + 3,
            ],
        ]);
        
        // skoring
        $perwakilanId = Yii::$app->user->identity->perwakilan_id ? : '%';
        $skorPemdaClass = new \app\models\SkorPemda();
        $skorBimtek = $skorPemdaClass->querySkorTampil($tahun, $perwakilanId, '%', 1);
        $skorReassesment = $skorPemdaClass->querySkorTampil($tahun, $perwakilanId, '%', 2);

        // grafik opini
        $opiniQuery = Yii::$app->db->createCommand("
            SELECT 
            SUM(a.opini_0) AS tahun0,
            SUM(a.opini_1) AS tahun1,
            SUM(a.opini_2) AS tahun2,
            SUM(a.opini_3) AS tahun3,
            SUM(a.opini_4) AS tahun4
            FROM
            (
                SELECT b.pemda_id, 
                SUM(b.opini_tahun_0) AS opini_0,
                SUM(b.opini_tahun_1) AS opini_1,
                SUM(b.opini_tahun_2) AS opini_2,
                SUM(b.opini_tahun_3) AS opini_3,
                SUM(b.opini_tahun_4) AS opini_4
                FROM
                (
                    SELECT pemda_id, 1 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 , 0 AS opini_tahun_4
                    FROM llkpd WHERE bulan LIKE CONCAT(:tahun ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                    UNION ALL
                    SELECT pemda_id, 0 AS opini_tahun_0, 1 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 , 0 AS opini_tahun_4
                    FROM llkpd WHERE bulan LIKE CONCAT((:tahun -1) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                    UNION ALL
                    SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 1 AS opini_tahun_2, 0 AS opini_tahun_3 , 0 AS opini_tahun_4
                    FROM llkpd WHERE bulan LIKE CONCAT((:tahun -2) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                    UNION ALL
                    SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 , 1 AS opini_tahun_4
                    FROM llkpd WHERE bulan LIKE CONCAT((:tahun -4) ,'%') AND opini_id = 'B4' AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                ) b GROUP BY b.pemda_id
            ) a
        ")->bindValues([
            ':tahun' => $tahun,
            ':perwakilanId' => $perwakilanId,
            ':pemdaId' => '%',
        ])->queryOne();

        // grafik simda
        $simdaQuery = Yii::$app->db->createCommand("
            SELECT COUNT(id) AS pemda, SUM(a.keu) AS keu, SUM(a.bmd) AS bmd, SUM(pendapatan) AS pendapatan, SUM(perencanaan) AS perencanaan
            FROM
            (
                SELECT
                a.id, a.name, IFNULL(b.keu,0) AS keu, IFNULL(c.bmd, 0) AS bmd, IFNULL(d.pendapatan, 0) AS pendapatan, IFNULL(e.perencanaan, 0) AS perencanaan
                FROM
                (
                    SELECT * FROM ref_pemda
                    WHERE id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                )a LEFT JOIN
                (
                    SELECT pemda_id, use_keu AS keu
                    FROM lsimdas
                    WHERE bulan LIKE CONCAT(:tahun ,'%') AND bulan <= :tahunBulan AND use_keu = 1 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                ) b ON a.id = b.pemda_id
                LEFT JOIN
                (
                    SELECT pemda_id, use_bmd AS bmd
                    FROM lsimdas
                    WHERE bulan LIKE CONCAT(:tahun ,'%') AND bulan <= :tahunBulan AND use_bmd = 1 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                ) c ON a.id = c.pemda_id
                LEFT JOIN
                (
                    SELECT pemda_id, use_pendapatan AS pendapatan
                    FROM lsimdas
                    WHERE bulan LIKE CONCAT(:tahun ,'%') AND bulan <= :tahunBulan AND use_pendapatan = 1 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                ) d ON a.id = d.pemda_id
                LEFT JOIN
                (
                    SELECT pemda_id, use_perencanaan AS perencanaan
                    FROM lsimdas
                    WHERE bulan LIKE CONCAT(:tahun ,'%') AND bulan <= :tahunBulan AND use_perencanaan = 1 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                    GROUP BY pemda_id
                ) e ON a.id = e.pemda_id
            ) a
        ")->bindValues([
            ':tahun' => $tahun,
            ':perwakilanId' => $perwakilanId,
            ':pemdaId' => '%',
            ':tahunBulan' => $tahunBulan,
        ])->queryOne();

        // grafik siskeudes
        $siskeudesQuery = Yii::$app->db->createCommand("
            SELECT COUNT(id) AS pemda, SUM(a.siskeudes) AS siskeudes, SUM(a.kompilasi) AS kompilasi
            FROM
            (
                -- Pilih yang terbesar saja nih
                SELECT a.id, a.name, IFNULL(b.siskeudes, 0) AS siskeudes, IFNULL(c.kompilasi, 0) AS kompilasi
                FROM ref_pemda a
                LEFT JOIN
                (
                    SELECT
                    a.pemda_id, CASE WHEN jumlah_desa_implementasi > 0 THEN 1 ELSE 0 END AS siskeudes
                    FROM ldanadesa_siskeudes a
                    WHERE bulan LIKE CONCAT(:tahun ,'%') AND bulan <= :tahunBulan AND perwakilan_id LIKE :perwakilanId AND pemda_id LIKE :pemdaId AND
                    a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_siskeudes b WHERE b.pemda_id = a.pemda_id)
                ) b ON a.id = b.pemda_id
                LEFT JOIN
                (
                    SELECT
                    a.pemda_id, CASE WHEN kompilasi > 0 THEN 1 ELSE 0 END AS kompilasi
                    FROM ldanadesa_siskeudes a
                    WHERE bulan LIKE CONCAT(:tahun ,'%') AND bulan <= :tahunBulan AND perwakilan_id LIKE :perwakilanId AND pemda_id LIKE :pemdaId AND
                    a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_siskeudes b WHERE b.pemda_id = a.pemda_id)
                ) c ON a.id = c.pemda_id
                WHERE a.perwakilan_id LIKE :perwakilanId AND a.id LIKE '%'
            ) a
        ")->bindValues([
            ':tahun' => $tahun,
            ':perwakilanId' => $perwakilanId,
            ':pemdaId' => '%',
            ':tahunBulan' => $tahunBulan,
        ])->queryOne();

        $simdaKeuGeometryDataQuery = Yii::$app->db->createCommand("
            SELECT
            CONCAT(c.id, ', ', IFNULL(b.keu,0)) AS data,
            c.id AS geometry_id, IFNULL(b.keu,0) AS keu
            FROM
            (
                SELECT * FROM ref_pemda
                WHERE id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
            )a LEFT JOIN
            (
                SELECT pemda_id, use_keu AS keu
                FROM lsimdas
                WHERE bulan LIKE CONCAT(:tahun ,'%') AND bulan <= :tahunBulan AND use_keu = 1 AND pemda_id LIKE :pemdaId AND perwakilan_id LIKE :perwakilanId
                GROUP BY pemda_id
            ) b ON a.id = b.pemda_id
            LEFT JOIN ref_pemda_geometry c ON a.id = c.pemda_id
            WHERE c.id IS NOT NULL 
            -- AND b.keu = 1
        ")->bindValues([
            ':tahun' => $tahun,
            ':perwakilanId' => $perwakilanId,
            ':pemdaId' => '%',
            ':tahunBulan' => $tahunBulan,
        ])->queryAll();

        // $simdaKeuGeometryData = ArrayHelper::map($simdaKeuGeometryDataQuery, 'geometry_id', 'keu');
        
        $simdaKeuGeometryData = [];
        foreach($simdaKeuGeometryDataQuery AS $data){
            $simdaKeuGeometryData[] = [$data['geometry_id'], (int) $data['keu']];
        }

        // return var_dump($simdaKeuGeometryData);

        return $this->render('index',[
            'tahun' => $tahun,
            'dataProvider' => $dataProvider,
            'skorBimtek' => $skorBimtek,
            'skorReassesment' => $skorReassesment,
            'opiniGrafik' => $opiniQuery,
            'simdaGrafik' => $simdaQuery,
            'siskeudesQuery' => $siskeudesQuery,
            'simdaKeuGeometryData' => $simdaKeuGeometryData,
        ]);
        
    }

    public function actionAsses()
    {
        IF(Yii::$app->session->get('tahun'))
        {
            $tahun = Yii::$app->session->get('tahun');
        }ELSE{
            $tahun = DATE('Y');
        }

        $perwakilanId = Yii::$app->user->identity->perwakilan_id ? : '%';
        $skorPemdaClass = new \app\models\SkorPemda();
        $skorReassesment = $skorPemdaClass->querySkorTampil($tahun, $perwakilanId, '%', 2);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $skorReassesment,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->renderAjax('skor', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBimtek()
    {
        IF(Yii::$app->session->get('tahun'))
        {
            $tahun = Yii::$app->session->get('tahun');
        }ELSE{
            $tahun = DATE('Y');
        }

        $perwakilanId = Yii::$app->user->identity->perwakilan_id ? : '%';
        $skorPemdaClass = new \app\models\SkorPemda();
        $skorBimtek = $skorPemdaClass->querySkorTampil($tahun, $perwakilanId, '%', 1);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $skorBimtek,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->renderAjax('skor', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // Bagian ini untuk menampilkan pengumuman
    public function actionView($id)
    {
        return $this->render('pengumuman', ['model' => \app\models\TaPengumuman::findOne(['id' => $id])]);
    }

    // Bagian ini untuk menampilkan user profile
    public function actionProfile()
    {
        $id = Yii::$app->user->identity->id;
        $model = \app\models\User::findOne(['id' => $id]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('kv-detail-success', 'Perubahan disimpan');
            return $this->redirect(Yii::$app->request->referrer); 
        }

        return $this->render('profile', ['model' => $model]);
    } 

    public function actionUbahpassword()
    {
        $id = Yii::$app->user->identity->id;
        // load user data
        $model = \app\models\User::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            IF(Yii::$app->security->validatePassword($model->passwordlama, $model->password_hash)){
                $model->setPassword($model->password);
                $model->save();
                Yii::$app->getSession()->setFlash('success',  'Password sudah diganti');
                return $this->redirect(Yii::$app->request->referrer);                                                
            }ELSE{
                Yii::$app->getSession()->setFlash('warning',  'Password lama anda salah');
                return $this->redirect(Yii::$app->request->referrer);                
            }
           
        } else {
            return $this->renderAjax('ubahpwd', [
                'user' => $model,
            ]);
        }        
    }          

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionContact()
    {
        $model = new ContactForm();

        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('contact', ['model' => $model]);
        }

        if (!$model->sendEmail(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while sending email.'));
            return $this->refresh();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 
            'Thank you for contacting us. We will respond to you as soon as possible.'));
        
        return $this->refresh();
    }

//------------------------------------------------------------------------------------------------//
// LOG IN / LOG OUT / PASSWORD RESET
//------------------------------------------------------------------------------------------------//

    /**
     * Logs in the user if his account is activated,
     * if not, displays appropriate message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        // user is logged in, he doesn't need to login
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // get setting value for 'Login With Email'
        $lwe = Yii::$app->params['lwe'];

        // if 'lwe' value is 'true' we instantiate LoginForm in 'lwe' scenario
        $model = $lwe ? new LoginForm(['scenario' => 'lwe']) : new LoginForm();

        // monitor login status
        $successfulLogin = true;

        // posting data or login has failed
        if (!$model->load(Yii::$app->request->post()) || !$model->login()) {
            $successfulLogin = false;
        }

        // if user's account is not activated, he will have to activate it first
        if ($model->status === User::STATUS_INACTIVE && $successfulLogin === false) {
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'You have to activate your account first. Please check your email.'));
            return $this->refresh();
        } 

        // if user is not denied because he is not active, then his credentials are not good
        if ($successfulLogin === false) {
            return $this->render('login', ['model' => $model]);
        }

        // login was successful, let user go wherever he previously wanted
        return $this->goBack();
    }

    /**
     * Logs out the user.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

/*----------------*
 * PASSWORD RESET *
 *----------------*/

    /**
     * Sends email that contains link for password reset action.
     *
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('requestPasswordResetToken', ['model' => $model]);
        }

        if (!$model->sendEmail()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'Sorry, we are unable to reset password for email provided.'));
            return $this->refresh();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));

        return $this->goHome();
    }

    /**
     * Resets password.
     *
     * @param  string $token Password reset token.
     * @return string|\yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$model->load(Yii::$app->request->post()) || !$model->validate() || !$model->resetPassword()) {
            return $this->render('resetPassword', ['model' => $model]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'New password was saved.'));

        return $this->goHome();      
    }    

//------------------------------------------------------------------------------------------------//
// SIGN UP / ACCOUNT ACTIVATION
//------------------------------------------------------------------------------------------------//

    /**
     * Signs up the user.
     * If user need to activate his account via email, we will display him
     * message with instructions and send him account activation email with link containing account activation token. 
     * If activation is not necessary, we will log him in right after sign up process is complete.
     * NOTE: You can decide whether or not activation is necessary, @see config/params.php
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {  
        // get setting value for 'Registration Needs Activation'
        $rna = Yii::$app->params['rna'];

        // if 'rna' value is 'true', we instantiate SignupForm in 'rna' scenario
        $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();

        // if validation didn't pass, reload the form to show errors
        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('signup', ['model' => $model]);  
        }

        // try to save user data in database, if successful, the user object will be returned
        $user = $model->signup();

        if (!$user) {
            // display error message to user
            Yii::$app->session->setFlash('error', Yii::t('app', 'We couldn\'t sign you up, please contact us.'));
            return $this->refresh();
        }

        // user is saved but activation is needed, use signupWithActivation()
        if ($user->status === User::STATUS_INACTIVE) {
            $this->signupWithActivation($model, $user);
            return $this->refresh();
        }

        // now we will try to log user in
        // if login fails we will display error message, else just redirect to home page
    
        if (!Yii::$app->user->login($user)) {
            // display error message to user
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Please try to log in.'));

            // log this error, so we can debug possible problem easier.
            Yii::error('Login after sign up failed! User '.Html::encode($user->username).' could not log in.');
        }
                      
        return $this->goHome();
    }

    /**
     * Tries to send account activation email.
     *
     * @param $model
     * @param $user
     */
    private function signupWithActivation($model, $user)
    {
        // sending email has failed
        if (!$model->sendAccountActivationEmail($user)) {
            // display error message to user
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'We couldn\'t send you account activation email, please contact us.'));

            // log this error, so we can debug possible problem easier.
            Yii::error('Signup failed! User '.Html::encode($user->username).' could not sign up. 
                Possible causes: verification email could not be sent.');
        }

        // everything is OK
        Yii::$app->session->setFlash('success', Yii::t('app', 'Hello').' '.Html::encode($user->username). '. ' .
            Yii::t('app', 'To be able to log in, you need to confirm your registration. 
                Please check your email, we have sent you a message.'));
    }

/*--------------------*
 * ACCOUNT ACTIVATION *
 *--------------------*/

    /**
     * Activates the user account so he can log in into system.
     *
     * @param  string $token
     * @return \yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionActivateAccount($token)
    {
        try {
            $user = new AccountActivation($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$user->activateAccount()) {
            Yii::$app->session->setFlash('error', Html::encode($user->username). Yii::t('app', 
                ' your account could not be activated, please contact us!'));
            return $this->goHome();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Success! You can now log in.').' '.
            Yii::t('app', 'Thank you').' '.Html::encode($user->username).' '.Yii::t('app', 'for joining us!'));

        return $this->redirect('login');
    }
}
