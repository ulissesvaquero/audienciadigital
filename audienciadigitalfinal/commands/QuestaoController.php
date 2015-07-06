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
class QuestaoController extends Controller
{
	function getPage($url,$timeout=false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if($timeout)
		{
			curl_setopt($ch, CURLOPT_TIMEOUT , 1);
		}
		curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
	
		return $data;
	}	
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
    	$limit = 70;
    	$paginas = ceil(76829 / $limit);
    	
    	$arrData = [];
    	for($i=1;$i<$paginas;$i++)
    	{
    		$data = [];
    		if($i==1)
    		{
	    		$data = $this->getPage('http://localhost:3000/articles/'.$i.'/'.$limit);
    		}else {
    			$data = $this->getPage('http://localhost:3000/articles/'.$i*$limit.'/'.$limit);
    		}
    		
    		echo $data;
    		
    		$arrData[] = $data;
    		
    		if($i % 20 == false)
    		{
    			//print_r($arrData);
    		}
    	}
    	
    	
        echo $paginas . "\n";
    }
}
