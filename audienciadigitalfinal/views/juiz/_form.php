<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Juiz */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="juiz-form">

	    <?php $form = ActiveForm::begin(['id' => 'juiz-form','enableClientValidation'=> true,'action' => Url::to(['juiz/create'])]); ?>
	
	    <?= $form->field($model, 'dsc_nome')->textInput(['maxlength' => true]) ?>
	
	    <?= $form->field($model, 'num_cpf')->textInput(['maxlength' => true]) ?>
	
	    <div class="form-group">
	        <?= Html::button('Cadastrar',['class' => 'btn btn-primary','onclick' => 'submitAjaxForm($(\'#juiz-form\'),"modalJuiz")']) ?>
	    </div>
	    
	    <?php ActiveForm::end(); ?>
</div>
