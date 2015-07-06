<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\TipoPessoa;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use app\models\Tema;
use yii\bootstrap\Modal;
use yii\base\DynamicModel;


$urlPessoaCreate = Url::to(['pessoa/add']);
$urlPessoaRemove = Url::to(['pessoa/delete']);
$urlTemaAdd = Url::to(['audiencia/addtema']);
$urlTemaRemove = Url::to(['audiencia/removetema']);

?>

<script>
	function addPessoa(form)
	{
		var url = "<?php echo $urlPessoaCreate; ?>";
		$.post(url ,form.serialize()).done(function(data)
		{
			$("#listaPessoa").prepend(data);
			$("#pessoa-id_tipo_pessoa").select2("val","");
			form[0].reset();
		});
	}

	function addTema(idTema,idAudiencia)
	{
		var url = "<?php echo $urlTemaAdd; ?>";
		$.post(url ,{id_tema:idTema,id_audiencia:idAudiencia}).done(function(data)
		{
			$("#listaTema").prepend(data);
		});
	}

	function removeTema(id_tema)
	{
		var id_audiencia =  <?php echo $model->id; ?>;
		var url = "<?php echo $urlTemaRemove; ?>";
		$.post(url ,{id_tema:id_tema,id_audiencia:id_audiencia}).done(function(data)
		{
			$("#liTema"+data).remove();
		});
	}
	
	function removePessoa(idPessoa)
	{
		var url = "<?php echo $urlPessoaRemove; ?>";
		$.post(url ,{'id':idPessoa}).done(function(data)
		{
			$("#liItemPessoa"+data.id).remove();
		});
	}

	function toggleBtAdicionar()
	{
		if($("#pessoa-dsc_pessoa").val().length > 0 && $("#pessoa-id_tipo_pessoa").val().length > 0)
		{
			$("#btnAddPessoa").removeAttr('disabled');
		}
	}
	
</script>


<div class="audiencia-config">
	<?php $form = ActiveForm::begin(['id' => 'audienciaConfigForm']); ?>
		<div class="col-lg-12">
			<?php echo Html::a('Finalizar Configurações',['audiencia/gravar','id' => $model->id],['class' => 'btn btn-default']); ?><br><br>
			<div id="tema" class="panel panel-default">
				<div class="panel-heading">
		        	<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> Temas</h3>
		        </div>
		        <div class="panel-body">
					<div class="col-lg-8">
						<?php
							echo $form->field($model, 'arrTema')->widget(Select2::classname(), [
								'language' => 'pt',
							    'pluginOptions' => [
							    	
							        'allowClear' => false,
							        'minimumInputLength' => 3,
							        'ajax' => [
							            'url' => Url::to(['tema/lista']),
							            'dataType' => 'json',
							            'data' => new JsExpression('function(params) { return {q:params.term,id:'.$model->id.'}; }')
							        ],
							        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
							        'templateResult' => new JsExpression('function(tema) { return tema.dsc_tema; }'),
							        'templateSelection' => new JsExpression('function (tema) { return tema.dsc_tema; }'),
							    ],
								'pluginEvents' => ["select2:select" => "function() { addTema(this.value,".$model->id."); $(this).select2(\"val\", \"\");  }"]
							]);
							echo Html::a('Adicionar Tema','#',['data-toggle' => 'modal','data-target'=>'#modalTema']);
						?>
					</div>
					<div class="listaConfig" id="listaTema" class="col-lg-4">
						 <ul class="list-group">
						 	<?php 
						 	foreach($arrTema as $tema)
						 	{
						 		echo $tema->getLi();
						 	}
						 	?>
						 </ul>
					</div>
				</div>
			</div>
		</div>
	<?php ActiveForm::end(); ?>
	<?php 
	    Modal::begin([
	     'header' => '<h2>Novo Tema</h2>',
	     'toggleButton' => false,
	     'options' => ['id' => 'modalTema']
	    ]);
	    	echo $this->render('@app/views/tema/create',['model' => new Tema()]);
	    Modal::end();
    ?>
    
    
    <div class="col-lg-12">
		<div id="pessoa" class="panel panel-default">
			<div class="panel-heading">
	        	<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Pessoa</h3>
	        </div>
	        <div class="panel-body">
				<div id="pessoa" class="col-lg-8">
					<?php $form = ActiveForm::begin(['id' => 'formPessoa']); ?>
						<?php 
								echo $form->field($model, 'id')->label(false)->hiddenInput();
						?>
						<div class="col-lg-3">
							<?php 
								echo $form->field($pessoa, 'dsc_pessoa')->textInput(['onkeyup' => 'toggleBtAdicionar()']);
							?>
						</div>
						
						<div class="col-lg-3">
							<?php 
								//echo $form->field($pessoa, 'id_tipo_pessoa')->dropDownList(ArrayHelper::map(TipoPessoa::find()->all(), 'id', 'dsc_tipo_pessoa'));
							?>
							<?php
								echo $form->field($pessoa, 'id_tipo_pessoa')->widget(Select2::classname(), [
									'language' => 'pt',
									'options' => ['onchange' => 'toggleBtAdicionar()','placeholder' => 'selecione um tipo de pessoa'], 
								    'data' => ArrayHelper::map(TipoPessoa::find()->all(), 'id', 'dsc_tipo_pessoa'),
								]);
								echo Html::a('Adicionar Tipo de Pessoa','#',['data-toggle' => 'modal','data-target'=>'#tipoPessoa']);
							?>
						</div>
						
						<div class="col-lg-2">
							<?php 
								echo Html::button('adicionar pessoa',['class' => 'btn btn-alert',
																	  'style'=>'margin-top:25px',
																	  'disabled' => true,
																	  'id' => 'btnAddPessoa',
																	  'onclick' => 'addPessoa($("#formPessoa"))',
								]);
							?>
						</div>
					<?php ActiveForm::end(); ?>		
				</div>
				
				<div class="listaConfig" id="listaPessoa" class="col-lg-4">
					 <ul class="list-group">
					 	<?php 
					 	foreach($arrPessoa as $pessoa)
					 	{
					 		echo $pessoa->getLi();
					 	}
					 	?>
					 </ul>
				</div>
				
				
				<?php 
				    Modal::begin([
				     'header' => '<h2>Novo Tipo Pessoa</h2>',
				     'toggleButton' => false,
				     'options' => ['id' => 'tipoPessoa']
				    ]);
				    	echo $this->render('@app/views/tipo-pessoa/create',['model' => new TipoPessoa()]);
				    Modal::end();
			    ?>
    
    
			</div>
		</div>
	</div>
</div>
 
	
