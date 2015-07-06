<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Gravação da audiência '.$audiencia->dsc_audiencia;
?>
<script>
	function goTo(segundo)
	{
		var audienciaPlayer = document.getElementById('audienciaPlayer');
		audienciaPlayer.currentTime = segundo;
	}

	function ativaBtSalvar(textoAnotacao)
	{
		if(textoAnotacao.value.length > 0) 
		{
			if($("#tempoMinhaAnotacaoBadge").html() == "")
			{
				window.currenTime = Math.round(document.getElementById("audienciaPlayer").currentTime);
				$("#tempoMinhaAnotacaoBadge").html(window.currenTime+'s');
				document.getElementById("audienciaPlayer").pause();
			}
			$("#btSalvarMinhaAnotacao").attr('disabled',false);
		}else
		{
			$("#tempoMinhaAnotacaoBadge").html("");
			$("#btSalvarMinhaAnotacao").attr('disabled',true);
			if(Math.round(document.getElementById("audienciaPlayer").currentTime) > 0)
			{
				document.getElementById("audienciaPlayer").play();
			}
		}
	}

	function salvarAnotacao(url)
	{
			$.ajax({
				   method : 'POST',
			       url: url,
			       data: {
				       		texto : $('#textoMinhaAnotacao').val(), 
				       		tempo : $("#tempoMinhaAnotacaoBadge").html(),
				       		idAudiencia : <?php echo $audiencia->id;?>,
						    idUsuario : <?php echo Yii::$app->user->identity->id;?>,
				   },
			       success: function(data) {

			       		$('#listaMinhaAnotacao').prepend(data);
			       		$("#tempoMinhaAnotacaoBadge").html("");
			       		$("#btSalvarMinhaAnotacao").attr('disabled',true);
			       		$("#textoMinhaAnotacao").val("");
			       		document.getElementById("audienciaPlayer").play();
			       }
			    });
	}
	
</script>
<div class="audiencia-ver">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-5">
            	<div id="panelVideo" class="panel panel-default">
  					<div class="panel-heading">
    					<h3 class="panel-title"><i class="glyphicon glyphicon-facetime-video"></i> Gravação</h3>
  					</div>
	  				<div class="panel-body">
	    				<div id="player">
	    					<?php
	    						$source = Html::tag('source','',['src' => $multimidia->getRealPath(),'type' => 'video/webm']);
	    						echo Html::tag('video',$source,['id' => 'audienciaPlayer',
	    												   'width' => Yii::$app->params['larguraVideo'],
	    												   'heigth' =>Yii::$app->params['alturaVideo'],
	    												   'controls' => ''
	    						]);
	    					?>
	    				</div><br>
	  				</div>
				</div>	
            </div>
            
            <div class="col-lg-7">
            	<div id="panelComentario" class="panel panel-default">
            		<div class="panel-heading">
            			<h3 class="panel-title"><i class="glyphicon glyphicon-tags"></i> Anotações</h3>
            		</div>
            		<div class="panel-body">
    					<div id="listaAnotacao" class="listaConfig">
    						<?php 
    								foreach($arrAnotacao as $anotacao)
    								{
    									echo $anotacao->getLi();
    								}
    						?>
    					</div>
  					</div>
            	</div>
            </div>
            
            <div class="col-lg-5"></div>
            <div class="col-lg-7">
            	<div id="panelMinhaAnotacao" class="panel panel-default">
            		<div class="panel-heading">
            			<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> Minhas Anotações</h3>
            		</div>
            		<div class="panel-body">
            			<div id="campoMinhaAnotacao">
            				<div class="form-group">
            					<span id="tempoMinhaAnotacaoBadge" class="badge" style="margin-bottom:10px"></span>
		            			<?php echo yii\helpers\Html::textarea('anotacao','',array('id' => 'textoMinhaAnotacao',
		            																	  'rows' => 2,'cols' =>71,
		            																	  'class' =>'form-control',
		            																	  'onkeyup' => 'ativaBtSalvar(this)'
		            																	  ));?>
	            				<br>
	            				<?php 
	            				$url = Url::to(array('anotacao/salvar'));
	            				echo yii\helpers\Html::buttonInput('Salvar',array('id' => 'btSalvarMinhaAnotacao',
	            															      'class' =>'btn btn-default',
	            																  'disabled' => true,
	            																  'onclick' => 'salvarAnotacao("'.$url.'")'));?>
            				</div>
            			</div>
    					<ul id="listaMinhaAnotacao" class="list-group">
    						<?php
    								if($arrMinhaAnotacao)
    								{
    									foreach($arrMinhaAnotacao as $minhaAnotacao)
    									{
    										$li = Html::tag('li',
    												$minhaAnotacao->dsc_anotacao.
    												Html::tag('span',$minhaAnotacao->num_segundo.'s',['class' => 'badge']),
    												['class' => 'list-group-item']);
    											
    										echo Html::tag('a',$li,['onclick' => 'goTo('.$minhaAnotacao->num_segundo.')']);
    									}
    								} 
    						?>
    						<a onclick="goTo(36)"><li class="list-group-item">fim gravacao<span class="badge">36s</span></li></a>
    					</ul>
  					</div>
            	</div>
            </div>
            
        </div>
    </div>
</div>
