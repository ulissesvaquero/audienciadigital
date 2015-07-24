<?php

namespace app\controllers;

use Yii;
use app\models\Audiencia;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use app\models\Multimidia;
use yii\web\UploadedFile;
use app\models\Anotacao;
use yii\helpers\VarDumper;
use app\models\Pessoa;
use app\models\Tema;
use yii\web\Response;
use app\models\TemaAudiencia;
use yii\helpers\Html;
use app\models\FilaEnvio;

/**
 * AudienciaController implements the CRUD actions for Audiencia model.
 */
class AudienciaController extends Controller
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
        				'only' => ['addtema','addmarcacao'],
        				'formats' => [
        						'application/json' => Response::FORMAT_JSON,
        				]
        		]
        ];
    }
	
    
    public function actionFinalizar()
    {
    	
    }
    
    
    public function actionAddtema()
    {
    	if($params = Yii::$app->request->post())
    	{
    		$tema = Tema::findOne($params['id_tema']);
    		$audiencia = Audiencia::findOne($params['id_audiencia']);
    		$audiencia->link('idTemas',$tema);
    		$audiencia->save();
    		return $tema->getLi();
    	}
    }
    
    
    public function actionRemovetema()
    {
    	if($params = Yii::$app->request->post())
    	{
    		$temaAudiencia = TemaAudiencia::find()->where($params)->one();
    		$temaAudiencia->delete();
    		return $params['id_tema'];
    	}
    }
    
    public function actionConfigurar($id)
    {
    	$model = Audiencia::findOne($id);
    	$pessoa = new Pessoa();
    	$tema = new Tema();
    	
    	$arrPessoa = $model->getIdPessoas()->all();
    	$arrTema = $model->getIdTemas()->all();
    	
    		return $this->render('configurar', [
    				'model' => $model,
    				'pessoa' => $pessoa,
    				'tema' => $tema,
    				'arrPessoa' => $arrPessoa,
    				'arrTema' => $arrTema
    		]);
    }
    
    /**
     * Lists all Audiencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Audiencia::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Audiencia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
    public function actionGravar($id)
    {
    	$model = Audiencia::findOne($id);
    	$arrPessoa = $model->getIdPessoas()->all();
    	$arrTema = $model->getIdTemas()->all();
    	
    	
    	return $this->render('gravar',['model' => $model , 'arrPessoa' => $arrPessoa , 'arrTema' => $arrTema]);
    }
    
    
    public function actionAddmarcacao()
    {
    	if($getParams = Yii::$app->request->get())
    	{
    		
    		$arrTema = [];
    		$arrPessoa = [];
    		
    		/*
    		 * Já deixei pronto caso precise implementar suporte a multiplos temas e pessoas
    		foreach($getParams['id_tema'] as $idTema)
    		{
    			$arrTema[] = Tema::findOne($idTema)->dsc_tema;
    		}
    	
    		foreach($getParams['id_pessoa'] as $idPessoa)
    		{
    			$pessoa = Pessoa::findOne($idPessoa);
    			$tipoPessoa = $pessoa->getIdTipoPessoa()->one();
    			$arrPessoa[] = $pessoa->dsc_pessoa . ' - ' .$tipoPessoa->dsc_tipo_pessoa;
    		}*/
    		
    		$arrTema[] = Tema::findOne($getParams['id_tema'])->dsc_tema;
    		$pessoa = Pessoa::findOne($getParams['id_pessoa']);
    		$tipoPessoa = $pessoa->getIdTipoPessoa()->one();
    		$arrPessoa[] = $pessoa->dsc_pessoa . ' - ' .$tipoPessoa->dsc_tipo_pessoa;
    		$arrTempo[] = gmdate("H:i:s", $getParams['tempo']);
    		
    		$ulTema = Html::ul($arrTema,['class' => 'list-group','itemOptions' => ['class' => 'list-group-item']]);
    		$ulPessoa = Html::ul($arrPessoa,['class' => 'list-group','itemOptions' => ['class' => 'list-group-item']]);
    		$ulTempo = Html::ul($arrTempo,['class' => 'list-group','itemOptions' => ['class' => 'list-group-item']]);
    		
    		return ['ultema' => $ulTema, 'ulpessoa' => $ulPessoa, 'ultempo' => $ulTempo];
    	}
    	
    	if($postParams = Yii::$app->request->post())
    	{
    		$anotacao =  new Anotacao();
    		$anotacao->id_tema = $postParams['id_tema'];
    		$anotacao->id_pessoa = $postParams['id_pessoa'];
    		$anotacao->id_audiencia = $postParams['id_audiencia'];
    		$anotacao->num_tempo = $postParams['tempo'];
    		$anotacao->id_usuario = Yii::$app->user->identity->id;
    		if($anotacao->save())
    		{
    			$anotacao->refresh();
  					 
    			return ['li' => $anotacao->getLi()];
    		}
    	}
    }
    
    public function actionMerge()
    {
    	//Pego todas as multimidias que estão sem merge
    	$arrMultimidia = Multimidia::findAll(['flg_merge' => 0]);
    	$arrFinal = [];
    	//Se exister as guardo em um array em que a chave sera o id da audiência
    	if(count($arrMultimidia) > 0)
    	{
    		foreach($arrMultimidia as $multimidia)
    		{
    			$arrFinal[$multimidia->id_audiencia][] = $multimidia;
    		}
    		 
    		//Pego todos os arquivos que estão com a flg_merge e os concateno
    		foreach($arrFinal as $idAudiencia => $arrMulAudi)
    		{
    			//Apenas faço este procedimento se tiver mais que 1 arquivo mergeado.
    			if(count($arrMulAudi) > 1)
    			{
    				foreach($arrMulAudi as $objMultimidia)
    				{
    					$path = Yii::getAlias('@upload').'/'.$idAudiencia.'/';
    					$pathFile = Yii::getAlias('@upload').'/'.$idAudiencia.'/'.'FILES.TXT';
    					file_put_contents($pathFile, 'file '.'\''.$path.$objMultimidia->dsc_arquivo.'\''.PHP_EOL,FILE_APPEND);
    					$objMultimidia->flg_merge = 1;
    					$objMultimidia->save();
    				}
    				$arquivoFinal = md5(microtime()).'.webm';
    				$cmd = ' -f concat -i '.$pathFile.' -c copy '.$path.$arquivoFinal;

				//GATO PRA FUNCIONAR NO WINDOWS
				if(strstr(Yii::getAlias('@ffmpeg'), 'windows'))
				{
					$cmd = str_replace('/', '\\', $cmd);
				}
    				
				exec ( Yii::getAlias('@ffmpeg') . $cmd . ' 2>&1', $out, $ret );

    				unlink($pathFile);
    				$arquivo = new Multimidia();
    				$arquivo->dsc_arquivo = $arquivoFinal;
    				$arquivo->id_audiencia = $idAudiencia;
    				$arquivo->save();
    			}
    		}
    	}
    }
    
    
    public function getCurlValue($filename, $contentType, $postname)
    {
    	// PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
    	// See: https://wiki.php.net/rfc/curl-file-upload
    	if (function_exists('curl_file_create')) {
    		return curl_file_create($filename, $contentType, $postname);
    	}
    
    	// Use the old style if using an older version of PHP
    	$value = "@{$this->filename};filename=" . $postname;
    	if ($contentType) {
    		$value .= ';type=' . $contentType;
    	}
    
    	return $value;
    }
    
    /**
     * Ação para ver uma gravação
     */
    public function actionVer($id)
    {
    	$audiencia = Audiencia::findOne($id);
    	//Se audiência existir
    	if($audiencia)
    	{
    		$arrAnotacao = $audiencia->getAnotacaos()->where('flg_publico = 1')->all();
    		$arrMinhaAnotacao = $audiencia->getAnotacaos()->where('flg_publico = 0 AND id_pessoa ='.Yii::$app->user->identity->id)->all();
    		//Faço o merge caso a cron não tenha feito
    		$this->actionMerge();
    		$multimidia = Multimidia::findOne(['flg_merge' => 0,'id_audiencia' => $audiencia->id]);
    		return $this->render('ver',['arrAnotacao' => $arrAnotacao,
    							 'multimidia' => $multimidia,
    							 'audiencia' => $audiencia,
    							 'arrMinhaAnotacao' => $arrMinhaAnotacao
    		]);
    	}
    }
    
    
    public function actionEnviaservidor()
    {
    	//Caminho no diretório 
    	$cfile = $this->getCurlValue($caminhoCompleto.'-m.webm','video/webm','cattle-01.webm');
    	
    	$data = array('audio-blob' => $cfile);
    	$target_url = 'http://titanioh05.cnj.jus.br/audienciadigital/save.php';
    	$ch = curl_init();
    	$options = array(CURLOPT_URL => $target_url,
    			CURLOPT_RETURNTRANSFER => true,
    			CURLINFO_HEADER_OUT => true, //Request header
    			CURLOPT_HEADER => true, //Return header
    			CURLOPT_SSL_VERIFYPEER => false, //Don't veryify server certificate
    			CURLOPT_POST => true,
    			CURLOPT_POSTFIELDS => $data
    	);
    		
    	curl_setopt_array($ch, $options);
    	$result = curl_exec($ch);
    	$header_info = curl_getinfo($ch,CURLINFO_HEADER_OUT);
    	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    	$header = substr($result, 0, $header_size);
    	$body = substr($result, $header_size);
    	curl_close($ch);
    	 
    	echo $result;
    }
    
    public function actionUpload()
    {
    	$model = new UploadForm();
    	if(Yii::$app->request->isPost)
    	{
    		$parametros = Yii::$app->request->post();
    		if($audiencia = Audiencia::findOne($parametros['idAudiencia']))
    		{
    			if(!is_dir(Yii::getAlias('@upload').'/'.$audiencia->id))
    			{
    				mkdir(Yii::getAlias('@upload').'/'.$audiencia->id,0777);
    			}
    			
    			$nomeArquivo = md5(microtime(true));
    			$caminhoCompleto = Yii::getAlias('@upload').'/'.$audiencia->id.'/'.$nomeArquivo;
    			$model->audio = UploadedFile::getInstanceByName('audio');
    			$model->video = UploadedFile::getInstanceByName('video');
    			$model->audio->saveAs($caminhoCompleto.'.wav');
    			$model->video->saveAs($caminhoCompleto.'.webm');
    			
    			//Tranformo o arquivo em um WEBM com audio
    			$cmd = ' -i ' . $caminhoCompleto.'.wav' . ' -i ' . $caminhoCompleto.'.webm' . ' -vcodec copy ' . $caminhoCompleto.'-m.webm';
    			
    			exec ( Yii::getAlias('@ffmpeg') .' '. $cmd . ' 2>&1', $out, $ret );
    			
    			$arquivoVideo = new Multimidia();
    			$arquivoVideo->dsc_arquivo = $nomeArquivo.'-m.webm';
    			$arquivoVideo->id_audiencia = $parametros['idAudiencia'];
    			$arquivoVideo->dsc_caminho_completo = $caminhoCompleto.'-m.webm';
    			$arquivoVideo->save();
    			
    			$filaEnvio = new FilaEnvio();
    			$filaEnvio->id_registro = $arquivoVideo->id;
    			$filaEnvio->tabela = $arquivoVideo->tableName();
    			$filaEnvio->save();
    			
    			
    			//Efetuo a exclusao dos demais arquivos separados
    			unlink($caminhoCompleto.'.wav');
    			unlink($caminhoCompleto.'.webm');
    		}
    	}
    }
    
    /**
     * Creates a new Audiencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Audiencia();
		$pessoa = new Pessoa();
		$tema = new Tema();
		
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['configurar','id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Audiencia model.
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
     * Deletes an existing Audiencia model.
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
     * Finds the Audiencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Audiencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Audiencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}