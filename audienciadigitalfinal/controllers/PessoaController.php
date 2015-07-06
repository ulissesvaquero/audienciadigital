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
use app\models\Pessoa;
use app\models\TipoPessoa;
use app\models\Audiencia;

class PessoaController extends Controller
{
	public $enableCsrfValidation = false;
	
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
    
	public function actionGet()
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	if(\Yii::$app->request->get())
    	{
    		$arrPessoa = Pessoa::findAll(['id_audiencia' => \Yii::$app->request->get('id_audiencia')]);
    		return $arrPessoa;
    	}
    }
    
    public function actionAdd()
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	if($params = Yii::$app->getRequest()->getBodyParams())
    	{
    		$pessoa = new Pessoa();
    		$pessoa->load($params);
    		if($pessoa->save())
    		{
    			//Depois que efetuei um insert apenas faço esse link para salvar.
	    		$pessoa->link('idAudiencias', Audiencia::findOne($params['Audiencia']['id']));
    			return $pessoa->getLi();
    		}
    	}
    }
    
    public function actionDelete()
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	if(\Yii::$app->request->post())
    	{
    		$pessoa = Pessoa::findOne(\Yii::$app->request->post('id'));
    		$arrAudienciaPessoa = $pessoa->getAudienciaPessoas()->one();
    		$arrAudienciaPessoa->delete();
    		if($pessoa->delete())
    		{
    			return [ 'id' => \Yii::$app->request->post('id')];
    		}
    	}
    }
    
    
    public function actionCreate()
    {
    	$pessoa = new Pessoa();
    	if(Yii::$app->request->post())
    	{
    		$post = Yii::$app->request->post();
    		$pessoa->load($post);
    		$pessoa->id_audiencia = $post['Audiencia']['id'];
    		if($pessoa->save())
    		{
    			echo $pessoa->getLi();
    		}
    	}
    }
    
    public function actionRemove()
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	if($_POST['id'])
    	{
    		$pessoa = Pessoa::findOne($_POST['id']);
    		if($pessoa->delete())
    		{
    			return $_POST['id'];
    		}
    	}
    }
    
    public function actionTeste()
    {
    	echo 'LIXO';exit;
    }

}
