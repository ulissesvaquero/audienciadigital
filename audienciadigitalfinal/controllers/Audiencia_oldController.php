<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;
use app\models\UploadForm;

class AudienciaController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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

    public function actionIndex()
    {
        return $this->render('index');
    }
	
    
    public function actionCadastrar()
    {
    	return $this->render('cadastrar');
    }
    
    public function actionGravar()
    {
    	return $this->render('gravar');
    }
    
    
    public function actionUpload()
    {
    	$model = new UploadForm();
    	if(Yii::$app->request->isPost)
    	{
    		$nomeArquivo = md5(microtime(true));
    		$caminhoCompleto = Yii::getAlias('@upload').'/'.$nomeArquivo;
    		$model->audio = UploadedFile::getInstanceByName('audio');
    		$model->video = UploadedFile::getInstanceByName('video');
    		$model->audio->saveAs($caminhoCompleto.'.wav');
    		$model->video->saveAs($caminhoCompleto.'.webm');
    		
    		//Tranformo o arquivo em um WEBM com audio
    		$cmd = ' -i ' . $caminhoCompleto.'.wav' . ' -i ' . $caminhoCompleto.'.webm' . ' -vcodec copy ' . $caminhoCompleto.'-merged.webm';
    		
    		exec ( Yii::getAlias('@ffmpeg') .' '. $cmd . ' 2>&1', $out, $ret );
    		
    		//Efetuo a exclusao dos demais arquivos separados
    		unlink($caminhoCompleto.'.wav');
    		unlink($caminhoCompleto.'.webm');
    		
    	}	
    }
    
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
