<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\TipoPessoa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-pessoa-form">

    <?php $form = ActiveForm::begin(['id' => 'tipo-pessoa-form','enableClientValidation'=> true,'action' => Url::to(['tipo-pessoa/create'])]); ?>

    <?= $form->field($model, 'dsc_tipo_pessoa')->textInput(['maxlength' => true]) ?>
	
	<div class="form-group">
	        <?= Html::button('Cadastrar',['class' => 'btn btn-primary','onclick' => 'submitAjaxForm($(\'#tipo-pessoa-form\'),"tipoPessoa")']) ?>
	</div>

    <?php ActiveForm::end(); ?>

</div>
