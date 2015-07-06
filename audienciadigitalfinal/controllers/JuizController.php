<?php

namespace app\controllers;

use Yii;
use app\models\Juiz;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * JuizController implements the CRUD actions for Juiz model.
 */
class JuizController extends Controller
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
        	/*
        	 Configuro um behavior para que eu não precise configurar 
        	 a resposta em cada controller.
        	 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        	 */ 
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
    	if($q = Yii::$app->request->get('q'))
    	{
    		$arrJuiz = Juiz::find()->asArray()->where("dsc_nome like '%$q%' OR num_cpf like '%$q%'")->all();
    		return ['results' =>$arrJuiz];
    	}
    }

    /**
     * Lists all Juiz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Juiz::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Juiz model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Juiz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Juiz();
        
        if($model->load(Yii::$app->request->post()))
        {
        	if($model->save())
        	{
        		return ['status' => true , 'errors' => 'asd'];
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

    /**
     * Updates an existing Juiz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Juiz model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Juiz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Juiz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Juiz::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
