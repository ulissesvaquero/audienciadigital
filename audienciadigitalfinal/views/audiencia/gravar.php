<?php 
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$urlUploadVideo = Url::to(array('upload'));

?>
<script>
//Suporte para todos os navegadores
navigator.getUserMedia = navigator.getUserMedia
		|| navigator.webkitGetUserMedia || navigator.mozGetUserMedia
		|| navigator.msGetUserMedia;

window.animationFrame = (function() {
	return window.requestAnimationFrame
			|| window.webkitRequestAnimationFrame
			|| window.mozRequestAnimationFrame
			|| window.oRequestAnimationFrame
			|| window.msRequestAnimationFrame
			|| function(/* function */callback, /* DOMElement */element) {
				window.setTimeout(callback, 1000 / 60);
			};
})();


var resolution_x = 320;
var resolution_y = 240;

var currentTime = 0;
var multiStreamRecorder;

var recorder;

var timeInterval = 5 * 1000;

var gravando = false; 
var mediaConstraints = { 
    audio: true, 
    video: {
        mandatory: {
            maxWidth: resolution_x,
            maxHeight: resolution_y,
            //minFrameRate: 3,
            //maxFrameRate: 64,
            //minAspectRatio: 1.77
        }
    }
};

function fazerUploadBlobs(blobs)
{
	var url = "<?php echo $urlUploadVideo;?>";
	var formData = new FormData();
	formData.append('audio', blobs[1]);
	formData.append('video', blobs[0]);
	formData.append('idAudiencia', <?php echo $model->id;?>);
	$.ajax({
		   processData: false,
	   	   contentType: false,
		   method : 'POST',
	       url: url,
	       data: formData,
	       success: function(data) {
	       }
	    });
}

function getUserMedia()
{
	if(gravando)
	{
		 var video = document.getElementById('audienciaPlayer'); 
		 multiStreamRecorder.start(timeInterval);
		 video.play();
	}else
	{
		navigator.getUserMedia(mediaConstraints, onMediaSuccess, onMediaError);
	}
	
}

function onMediaError(e)
{
	console.error('media error', e);
	alert('ocorreu um erro');
}

function onMediaSuccess(stream)
{
	gravando = true;
	
	var audioContext = new AudioContext;
	var inputAudio = audioContext.createMediaStreamSource(stream);
	recorder = new Recorder(inputAudio);
	recorder.record();
	
	var video = document.getElementById('audienciaPlayer'); 
	
	video = mergeProps(video, {
        controls: true,
        src: URL.createObjectURL(stream)
    });

	video.style.display = 'block';
	video.style.width = resolution_x+'px';
	video.style.height = resolution_y+'px';
    
	var blobs = [];
    
    video.addEventListener('loadedmetadata', function() {
        multiStreamRecorder = new MultiStreamRecorder(stream);
        multiStreamRecorder.video = video;
        multiStreamRecorder.audioChannels = 1;
        multiStreamRecorder.ondataavailable = function(blob) {
        	blobs.push(blob.video);
        	recorder.stop();
	    	recorder.exportWAV(function(blobAudio){
	    		blobs.push(blobAudio);
	    		fazerUploadBlobs(blobs);
	    		blobs = [];
	    	});
	    	recorder.clear();
	    	recorder.record();
        };
        multiStreamRecorder.start(timeInterval);
        
    }, false);
    
    video.play();
}

function pararGravacao()
{
	var video = document.getElementById('audienciaPlayer'); 
	video.pause();
	multiStreamRecorder.stop();
}

function concluirGravacao()
{
	var video = document.getElementById('audienciaPlayer'); 
	video.pause();
	multiStreamRecorder.stop();
	if(multiStreamRecorder.stream) {
		console.log('enteri');
        multiStreamRecorder.stream.stop();
    }
}

function ativaBtSalvar(textoAnotacao)
{
	if(textoAnotacao.value.length > 0) 
	{
		if($("#tempoAnotacaoBadge").html() == "")
		{
			window.currenTime = Math.round(document.getElementById("audienciaPlayer").currentTime);
			$("#tempoAnotacaoBadge").html(window.currenTime+'s');
		}
		$("#btSalvarAnotacao").attr('disabled',false);
	}else
	{
		$("#tempoAnotacaoBadge").html("");
		$("#btSalvarAnotacao").attr('disabled',true);
	}
}


function salvarAnotacao(url)
{
		$.ajax({
		   method : 'POST',
	       url: url,
	       data: {texto: $('#textoAnotacao').val(), 'tempo': $("#tempoAnotacaoBadge").html(),'idAudiencia' : <?php echo $model->id;?>},
	       success: function(data) {
	       		$('#listaAnotacao').prepend(data);
	       		$("#tempoAnotacaoBadge").html("");
	       		$("#btSalvarAnotacao").attr('disabled',true);
	       		$("#textoAnotacao").val("");
	       }
	    });
}


/*
 * Método responsável por carregar uma modal de confirmação de dados.
 */
function verificaTag()
{
	/*var currentTime = Math.round(document.getElementById("audienciaPlayer").currentTime);*/
	var url = "<?php echo Url::to(['addmarcacao']);?>";
	if($('#id_tema').val() != "" && $('#id_pessoa').val() != "")
	{
		window.currentTime = document.getElementById("audienciaPlayer").currentTime;
		$.get(url,{
					id_tema:$('#id_tema').val(),
				  	id_pessoa:$('#id_pessoa').val(),
				  	tempo : currentTime
				  }).done(function(data){
			$('#listaTema').html(data.ultema);
			$('#listaPessoa').html(data.ulpessoa);
			$('#listaTempo').html(data.ultempo);
			$('#modalAssociacao').modal('show');
		});
	}
}

function fechaModalAssoc()
{
	$('#modalAssociacao').modal('hide');
}

/**
 * Faço uma requisição post e salvo na base a anotação
 */
function addMarcacao()
{
	var idTema = $('#id_tema').val();
	var idPessoa = $('#id_pessoa').val();
	var url = "<?php echo Url::to(['addmarcacao']);?>";

	$.post(url,{
				id_tema:$('#id_tema').val(),
				id_pessoa:$('#id_pessoa').val(),
				tempo:window.currentTime,
				id_audiencia:<?php echo $model->id;?>
				}).done(function(data){
		$("#id_tema").select2("val","");
		$("#id_pessoa").select2("val","");
		$('#listaAnotacao').append(data.li);
		$('#modalAssociacao').modal('hide');
	});
	
}


</script>
<?php
/* @var $this yii\web\View */
$this->title = 'Gravação da audiência '.$model->dsc_audiencia;
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-6">
            	<div id="panelVideo" class="panel panel-default">
  					<div class="panel-heading">
    					<h3 class="panel-title">Gravação</h3>
  					</div>
	  				<div class="panel-body">
	    				<div id="player">
	    					<video id="audienciaPlayer" style="display: none"></video>
	    				</div><br>
	    				<div id="opcaoGravacao">
	    					<button class="btn btn-lg btn-success" onclick="getUserMedia(true,true)"><i class="glyphicon glyphicon-play"></i> Iniciar</button>
	    					<button class="btn btn-lg btn-info" onclick="pararGravacao()"><i class="glyphicon glyphicon-pause"></i> Pausar</button>
							<button class="btn btn-lg btn-danger" onclick="concluirGravacao()"><i class="glyphicon glyphicon-stop"></i> Concluir Gravação</button>
	    				</div>
	  				</div>
				</div>	
            </div>
            
            <div class="col-lg-6">
            	<div id="panelComentario" class="panel panel-default">
            		<div class="panel-heading">
            			<h3 class="panel-title">Registrar Anotação</h3>
            		</div>
            		<div class="panel-body">
            			<div id="campoAnotacao">
            				<div class="form-group">
            					<span id="tempoAnotacaoBadge" class="badge" style="margin-bottom:10px"></span>
            					
            					<?php 
	            					echo '<label class="control-label"><i class="glyphicon glyphicon-list"></i> Tema</label>';
									echo Select2::widget([
									    'name' => 'id_tema',
									    'data' => ArrayHelper::map($arrTema, 'id', 'dsc_tema'),
									    'options' => [
									        'placeholder' => 'Escolha um tema',
									        //'multiple' => true,
									    	'id' => 'id_tema'
									    ],
										'pluginEvents' => [
															"select2:select" => "function() {verificaTag();}",
										]
									]);
            					?>
            					<br><br><br>
            					<?php 
	            					echo '<label class="control-label"><i class="glyphicon glyphicon-user"></i> Pessoa</label>';
									echo Select2::widget([
									    'name' => 'id_pessoa',
									    'data' => ArrayHelper::map($arrPessoa, 'id', 'dsc_pessoa'),
									    'options' => [
									        'placeholder' => 'Escolha uma pessoa',
									        //'multiple' => true,
									    	'id' => 'id_pessoa',
									    ],
										'pluginEvents' => [
															"select2:select" => "function() {verificaTag();}",
										]
									]);
            					?>
            				</div>
            			</div>
  					</div>
            	</div>
            </div>
            
            <div class="col-lg-6">
            </div>
            
            <div class="col-lg-6">
            	<div id="panelVideo" class="panel panel-default">
  					<div class="panel-heading">
    					<h3 class="panel-title">Anotação</h3>
  					</div>
	  				<div class="panel-body">
	    				<ul id="listaAnotacao" class="list-group"></ul>
	  				</div>
				</div>	
            </div>
        </div>
    </div>
    
    <?php
    		
    
    ?>
    
    
    <?php
    	/**
    	 * Trocar para renderizar essa view através de ajax.
    	 */ 
	    Modal::begin([
	     'header' => '<h2><i class="glyphicon glyphicon-info-sign"></i> Informações sobre a marcação</h2>',
	     'toggleButton' => false,
	     'options' => ['id' => 'modalAssociacao'],
	     'footer' => Html::button('Confirmar',['class' => 'btn btn-success','onclick' =>'addMarcacao()']) . Html::button('Cancelar',['class' => 'btn btn-danger','onclick' => 'fechaModalAssoc()'])
	    ]);
	    	echo $this->render('@app/views/audiencia/vinculartemapessoa',['id_pessoa' => '','id_tema' => '']);
	    Modal::end();
    ?>
</div>
