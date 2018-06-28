<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tracks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-index box box-primary">
    <div class="box-header with-border">
		<?= Html::a('Create Track', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'layout' => "{items}\n{summary}\n{pager}",
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],

				[
					'attribute' => 'model_type',
					'value' => function ($model) {
						$labels = $model::getModelTypeLabelOptions();
						return \yii\helpers\ArrayHelper::getValue($labels, $model->model_type);
					},
				],
				'model.name',

				[
					'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}'
				],
			],
		]); ?>
    </div>
</div>
