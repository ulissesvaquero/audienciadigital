<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Juiz */

$this->title = 'Update Juiz: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Juizs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="juiz-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
