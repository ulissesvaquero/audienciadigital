<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use yii\web\Response;
use app\models\Tema;
class TemaController extends \yii\web\Controller
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
								'application/json' => Response::FORMAT_JSON,
						]
				]
		];
	}
	
	public function actionLista()
	{
		if($id = \Yii::$app->request->get('id'))
		{
			$sql = "SELECT
						*
					FROM
						tema
					WHERE
					id
					NOT IN
					(
					SELECT
					id_tema
					FROM
					tema_audiencia ta
					WHERE
					id_audiencia = $id
					)";
			if($q = \Yii::$app->request->get('q'))
			{
				$sql .= " AND dsc_tema LIKE '%$q%'";
			}
			$arrTema = \Yii::$app->db->createCommand($sql)->queryAll();
			return ['results' =>$arrTema];
		}
	}
	
	public function actionCreate()
	{
	 	$model = new Tema();
        
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
}
