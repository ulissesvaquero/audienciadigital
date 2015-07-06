<?php 
use yii\helpers\Url;

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
        	
        	if(blobs.length == 2)
			{
        		fazerUploadBlobs(blobs);
				blobs = [];
			}
        	blobs.push(blob.video);
        	recorder.stop();
	    	recorder.exportWAV(function(blob){
	    		blobs.push(blob);
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
							<button class="btn btn-lg btn-danger" onclick="pararGravacao()"><i class="glyphicon glyphicon-stop"></i> Concluir Gravação</button>
	    				</div>
	  				</div>
				</div>	
            </div>
            
            <div class="col-lg-6">
            	<div id="panelComentario" class="panel panel-default">
            		<div class="panel-heading">
            			<h3 class="panel-title">Anotações</h3>
            		</div>
            		<div class="panel-body">
            			<div id="campoAnotacao">
            				<div class="form-group">
            					<span id="tempoAnotacaoBadge" class="badge" style="margin-bottom:10px"></span>
		            			<?php echo yii\helpers\Html::textarea('anotacao','',array('id' => 'textoAnotacao',
		            																	  'rows' => 5,'cols' =>71,
		            																	  'class' =>'form-control',
		            																	  'onkeyup' => 'ativaBtSalvar(this)'
		            																	  ));?>
	            				<br>
	            				<?php 
	            				$url = Url::to(array('anotacao/salvar'));
	            				echo yii\helpers\Html::buttonInput('Salvar',array('id' => 'btSalvarAnotacao',
	            																	    'class' =>'btn btn-default',
	            																		'disabled' => true,
	            																		'onclick' => 'salvarAnotacao("'.$url.'")'));?>
            				</div>
            			</div>
    					<ul id="listaAnotacao" class="list-group"></ul>
  					</div>
            	</div>
            </div>
            
        </div>
    </div>
</div>
