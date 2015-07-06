<?php
namespace app\controllers;

use yii\rest\ActiveController;
use app\models\TipoAudiencia;

class TipoAudienciaController extends ActiveController
{
    public $modelClass = 'app\models\TipoAudiencia';
    
    public function behaviors()
    {
        return 
        \yii\helpers\ArrayHelper::merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
            ],
        ]);
    }
    
    public function actionGetflag()
    {
    	if($idTipoAudiencia = \Yii::$app->request->get('id_tipo_audiencia'))
    	{
    		return TipoAudiencia::findOne($idTipoAudiencia);
    	}
    }
}
