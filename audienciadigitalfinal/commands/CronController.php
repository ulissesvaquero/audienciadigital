<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\FilaEnvio;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{
	
	
	public function getCurlValue($filename, $contentType, $postname)
	{
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
	
	public function sendMultimidia($multimidia)
	{
		//Caminho no diretório
		$cfile = $this->getCurlValue($multimidia->dsc_caminho_completo,'video/webm',$multimidia->dsc_arquivo);
		
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
		
		if($body)
		{
			$result = json_decode($body);
			if($result->status)
			{
				return true;
			}
		}
		return false;
	}
	
	/*
	 * Descobre a model que foi salva na fila e recupera da base a linha com 
	 * o id adicionado.
	 */
	public function getObj($filaEnvio)
	{
		$className = '\\app\\models\\'.ucfirst($filaEnvio->tabela);
		$obj = $className::findOne($filaEnvio->id_registro);
		return $obj;
	}
	
	
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
    	//Primeiro verifico se existe algum processo em envio
    	//Se tem algo a ser enviado
    	//if(!FilaEnvio::find()->where(['flg_enviado' => 3])->count())
    	if(FilaEnvio::find()->where(['flg_enviado' => 0])->limit(10)->count())
    	{
    		$arrFilaEnvio = FilaEnvio::find()->where(['flg_enviado' => 0])->limit(10)->all();
    		foreach ($arrFilaEnvio as $filaEnvio)
    		{
    			switch ($filaEnvio->tabela)
    			{
    				case 'multimidia':
    					$multimidia = $this->getObj($filaEnvio);
    					if($this->sendMultimidia($multimidia))
    					{
    						$filaEnvio->flg_enviado = 1;
    						$filaEnvio->save();
    					}
    				break;
    			}
    		}
    	}
    	
        echo $message . "\n";
    }
}
