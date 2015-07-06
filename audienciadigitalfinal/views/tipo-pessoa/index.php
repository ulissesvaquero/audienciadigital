<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\assets\AppAsset;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Pessoas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-pessoa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tipo Pessoa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?php Pjax::begin(); ?>
	    <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'columns' => [
	            ['class' => 'yii\grid\SerialColumn'],
	
	            'id',
	            'dsc_tipo_pessoa',
	
	            ['class' => 'yii\grid\ActionColumn'],
	        ],
	    ]); ?>
	<?php Pjax::end(); ?>
</div>
