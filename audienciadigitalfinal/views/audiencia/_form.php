<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TipoPessoa;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\base\View;
use yii\bootstrap\Modal;
use app\models\Juiz;
use app\models\TipoAudiencia;

/* @var $this yii\web\View */
/* @var $model app\models\Audiencia */
/* @var $form yii\widgets\ActiveForm */

/*
$this->registerJs("function formataCampo(ss)
	{
		var markup =
			'<div class=\"row\">' + 
    			'<div class=\"col-sm-5\">' +
        			'<img src=\"' + ss.dsc_img + '\" class=\"img-rounded\" style=\"width:80px\" />' +
    			'</div>' +
    			'<div class=\"col-sm-3\"><i class=\"glyphicon glyphicon-user\"></i> ' + ss.dsc_nome + '</div>' +
    			'<div class=\"col-sm-3\"><i class=\"glyphicon glyphicon-eye-open\"></i> ' + ss.num_cpf + '</div>' +
			'</div>';
		return markup;
	}", \yii\web\View::POS_HEAD);
?>
*/

$this->registerJs("function formataCampo(ss)
	{
		var markup =
			'<div class=\"row\">' + 
    			'<div class=\"col-sm-5\">' +
        			'<img src=\"' + ss.dsc_img + '\" class=\"img-rounded\" style=\"width:80px\" />' +
    			'</div>' +
    			'<div class=\"col-sm-3\"><i class=\"glyphicon glyphicon-user\"></i> ' + ss.dsc_nome + '</div>' +
    			'<div class=\"col-sm-3\"><i class=\"glyphicon glyphicon-eye-open\"></i> ' + ss.num_cpf + '</div>' +
			'</div>';
		return markup;
	}", \yii\web\View::POS_HEAD);
?>


<script>
	function toggleCampoAudiencia(tipoAudiencia,url)
	{
		$.get(url, { id_tipo_audiencia: tipoAudiencia.value } )
		  .done(function( data ) {
		    if(data.flg_num_processo)
		    {
			    $("#dscAudiencia").hide();
			    $("#nmProcesso").show();
		    }else
		    {
		    	 $("#dscAudiencia").show();
				 $("#nmProcesso").hide();
		    }
		});
	}
</script>
<div class="audiencia-form">

	<div id="audiencia" class="col-lg-12" ng-controller="audienciaController" ng-init="init()">
    	<?php $form = ActiveForm::begin(['id' => 'audienciaForm']); ?>
    	
    	<div class="col-lg-4">
	    	<?= $form->field($model, 'id_tipo_audiencia')->label('')->widget(Select2::className(),[
	    												 'data'=>ArrayHelper::map(TipoAudiencia::find()->all(), 
	    												   'id', 
	    												   'dsc_tipo_audiencia'),
	    												 'options' =>
	    												  [
	    												  'onChange' => 'toggleCampoAudiencia(this,"'.Url::to(['tipo-audiencia/getflag']).'")',
	    												  'placeholder' => 'Selecione o tipo da audiência',
	    	]]);?>
    	</div>
    	
    	<div id="dscAudiencia" class="col-lg-4" style="display:none">
	    	<?= $form->field($model, 'dsc_audiencia')->textInput([
	    		'maxlength' => true,
	    	]);?>
    	</div>
    	
    	<div id="nmProcesso" class="col-lg-4" style="display:none">
	    	<?= $form->field($model, 'nm_processo')->textInput([
	    		'maxlength' => true,
	    	]);?>
    	</div>
    	
		
		<div class="col-lg-4">
			<?php
				echo $form->field($model, 'id_juiz')->label('Informe o nome ou CPF do juiz')->widget(Select2::classname(), [
						'language' => 'pt',
						'pluginOptions' => [
								'allowClear' => false,
								'size' => Select2::LARGE,
								'minimumInputLength' => 3,
								'ajax' => [
										'url' => Url::to(['juiz/lista']),
										'dataType' => 'json',
										'data' => new JsExpression('function(params) { return {q:params.term}; }'),
								],
								'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
								'templateResult' => new JsExpression('function(juiz) { return juiz.num_cpf != \'\' ? juiz.dsc_nome +" - "+juiz.num_cpf : juiz.dsc_nome }'),
								'templateResult' => new JsExpression('formataCampo'),
								'templateSelection' => new JsExpression('function (juiz) { return juiz.dsc_nome; }'),
								'select2:selecting' => new JsExpression("function() { console.log('selecting'); }")
						],
						//Quando selecionar o produto
						/*'pluginEvents' =>
						[
								"select2:select" => "function() { console.log(this.value); }",
						]*/
				]);
				
				echo Html::a('Adicionar Juiz','#',['data-toggle' => 'modal','data-target'=>'#modalJuiz']);
			?>
		</div>    	
    	
    	
    	
    	<div class="col-lg-4">
		    <div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>
	    </div>
	    <?php ActiveForm::end(); ?>
    </div>
    
    <?php 
    //Modal Juiz
    Modal::begin([
     'header' => '<h2>Novo Juiz</h2>',
     'toggleButton' => false,
     'options' => ['id' => 'modalJuiz']
    ]);
    
    	echo $this->render('@app/views/juiz/create',['model' => new Juiz()]);
    
    Modal::end();
    
    ?>
    
	
</div>
