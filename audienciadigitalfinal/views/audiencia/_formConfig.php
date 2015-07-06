<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipoPessoa;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Audiencia */
/* @var $form yii\widgets\ActiveForm */


$urlPessoaCreate = Url::to(['pessoa/create']);
$urlPessoaRemove = Url::to(['pessoa/remove']);
$urlTemaCreate = Url::to(['tema/create']);
$urlTemaRemove = Url::to(['tema/remove']);
$urlGravar = Url::to(['gravar','id'=>$model->id]);
?>

<script>
	function addPessoa(bt)
	{
		var form = $(bt).parent().parent();
		var url = "<?php echo $urlPessoaCreate; ?>";
		$.post(url ,form.serialize()).done(function(data)
		{
			$("#listaPessoa").prepend(data);
			form[0].reset();
		});
	}

	function addPessoa(bt)
	{
		var form = $(bt).parent().parent();
		var url = "<?php echo $urlPessoaCreate; ?>";
		$.post(url ,form.serialize()).done(function(data)
		{
			$("#listaPessoa").prepend(data);
			form[0].reset();
		});
	}

	function removePessoa(idPessoa)
	{
		var url = "<?php echo $urlPessoaRemove; ?>";
		$.post(url ,{'id':idPessoa}).done(function(data)
		{
			$("#pessoa"+data).remove();
		});
	}

	function addTema(bt)
	{
		var form = $(bt).parent().parent();
		var url = "<?php echo $urlTemaCreate; ?>";
		$.post(url ,form.serialize()).done(function(data)
		{
			$("#listaTema").prepend(data);
			form[0].reset();
		});
	}

	function removeTema(idTema)
	{
		var url = "<?php echo $urlTemaRemove; ?>";
		$.post(url ,{'id':idTema}).done(function(data)
		{
			$("#tema"+data).remove();
		});
	}

	function carregaGravar()
	{
		var url = "<?php echo $urlGravar; ?>";
		location.href = url;
	}

</script>
<div class="audiencia-form">
	
	<div class="col-lg-12">
		<div id="pessoa" class="panel panel-default">
			<div class="panel-heading">
	        	<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Pessoa</h3>
	        </div>
	        <div class="panel-body">
				<div id="formPessoa" class="col-lg-8">
					<?php $form = ActiveForm::begin(); ?>
						<?php 
								echo $form->field($model, 'id')->label(false)->hiddenInput();
						?>
						<div class="col-lg-3">
							<?php 
								echo $form->field($pessoa, 'dsc_pessoa')->textInput();
							?>
						</div>
						
						<div class="col-lg-3">
							<?php 
								echo $form->field($pessoa, 'id_tipo_pessoa')->dropDownList(ArrayHelper::map(TipoPessoa::find()->all(), 'id', 'dsc_tipo_pessoa'));
							?>
						</div>
						
						<div class="col-lg-2">
							<?php 
								echo Html::button('adicionar pessoa',['class' => 'btn btn-alert',
																	  'style'=>'margin-top:25px',
																	  'onclick' => 'addPessoa(this)',
								]);
							?>
						</div>
					<?php ActiveForm::end(); ?>		
				</div>
				
				<div id="listaPessoa" class="col-lg-4">
					 <ul class="list-group">
					 	<?php 
					 	foreach($arrPessoa as $pessoa)
					 	{
					 		echo $pessoa->getLi();
					 	}
					 	?>
					 </ul>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="col-lg-12">
		<div id="tema" class="panel panel-default">
			<div class="panel-heading">
	        	<h3 class="panel-title"><i class="glyphicon glyphicon-tags"></i> Temas</h3>
	        </div>
	        <div class="panel-body">
				<div id="formTema" class="col-lg-8">
					<?php $form = ActiveForm::begin(); ?>
						<?php 
								echo $form->field($model, 'id')->label(false)->hiddenInput();
						?>
						<div class="col-lg-5">
							<?php 
								echo $form->field($tema, 'dsc_tema')->textarea(['rows' => 2]);
							?>
						</div>
						
						<div class="col-lg-3">
							<?php 
								echo Html::button('adicionar tema',['class' => 'btn btn-alert',
																	  'style'=>'margin-top:25px',
																	  'onclick' => 'addTema(this)',
								]);
							?>
						</div>	
					<?php ActiveForm::end(); ?>		
				</div>
				
				<div id="listaTema" class="col-lg-4">
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
	<?php 
		echo Html::button('Finalizar',['class' => 'btn btn-success',
				'onclick' => 'carregaGravar()',
		]);
	?>
</div>
