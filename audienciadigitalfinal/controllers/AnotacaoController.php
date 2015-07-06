<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Html;
use app\models\Anotacao;

class AnotacaoController extends Controller
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
	
    public function actionSalvar()
    {
    	$request = Yii::$app->request->post();
    	$flg_link = false;
    	$anotacao = new Anotacao();
    	$anotacao->dsc_anotacao = $request['texto'];
    	$anotacao->num_segundo = substr($request['tempo'],0,-1);
    	$anotacao->id_audiencia = $request['idAudiencia'];
    	if(isset($request['idUsuario']))
    	{
    		$anotacao->id_usuario = $request['idUsuario'];
    		$flg_link = true;
    	}
    	$anotacao->save();
    	if($flg_link)
    	{
    		$li =  Html::tag('li',$request['texto'].Html::tag('span',$request['tempo'],array('class'=>'badge')),array('class' => 'list-group-item'));
    		echo Html::tag('a',$li,['onclick' => 'goTo('.$anotacao->num_segundo.')']);
    	}else {
	    	echo Html::tag('li',$request['texto'].Html::tag('span',$request['tempo'],array('class'=>'badge')),array('class' => 'list-group-item'));
    	}
    	exit;
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
