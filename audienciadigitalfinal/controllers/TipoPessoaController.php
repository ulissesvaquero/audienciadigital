<?php
namespace app\controllers;

use yii\rest\ActiveController;
use app\models\TipoPessoa;
use yii\filters\VerbFilter;
use yii\base\Response;

class TipoPessoaController extends \yii\web\Controller
{
    
	public function behaviors()
	{
		return [
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'delete' => ['post'],
						],
				],
				'contentNegotiator' =>
				[
						'class' => 'yii\filters\ContentNegotiator',
						'only' => ['lista','create'],
						'formats' => [
								'application/json' => \yii\web\Response::FORMAT_JSON,
						]
				]
		];
	}
	
    
	public function actionCreate()
	{
		$model = new TipoPessoa();
		if($model->load(\Yii::$app->request->post()))
		{
			if($model->save())
			{
				return ['status' => true , 'errors' => []];
			}else
			{
				return ['status' => false , 'errors' => $model->errors];
			}
		}else
		{
			return $this->render('create', [
					'model' => $model,
			]);
		}
	}
	
    public function actionLista()
    {
    	if($q = \Yii::$app->request->get('q'))
    	{
	    	return ['results' => TipoPessoa::find()->where("dsc_tipo_pessoa like '%$q%'")->all()];
    	}
    }
}
