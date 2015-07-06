<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Audiencia */

$this->title = 'Configurar Audiência '.$model->dsc_audiencia;
$this->params['breadcrumbs'][] = ['label' => 'Audiências', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audiencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formConfig', [
        'model' => $model,
    	'pessoa' => $pessoa,
    	'tema' => $tema,
    	'arrPessoa' => $arrPessoa,
    	'arrTema' => $arrTema
    ]) ?>
    
</div>
