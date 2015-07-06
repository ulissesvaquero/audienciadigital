<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Tema */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tema-form">

    <?php $form = ActiveForm::begin(['id' => 'tema-form','enableClientValidation'=> true,'action' => Url::to(['tema/create'])]); ?>

    <?= $form->field($model, 'dsc_tema')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
    	<?= Html::button('Cadastrar',['class' => 'btn btn-primary','onclick' => 'submitAjaxForm($(\'#tema-form\'),"modalTema")']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
