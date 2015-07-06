<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="audiencia-index">

	<?php 
	
		echo GridView::widget(['dataProvider' => $dataProvider,
							  'columns' => [
							  				['class' => 'yii\grid\SerialColumn'],
							  				'dsc_audiencia',
							  				[
							  					'attribute' => '',
											    'format' => 'raw',
											    'value' => function ($model) 
											    {
											    		$icon = Html::tag('i','',['class' => 'glyphicon glyphicon-eye-open']);
											    		$url = Url::to(['ver','id' => $model->id]); 
											    		return $a = Html::tag('a',$icon.' Ver Audiência',['href' => $url]);                 
											    },	
							  				],
							  ],
		]);
	
	?>
</div>
