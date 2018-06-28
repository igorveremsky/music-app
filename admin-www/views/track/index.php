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

				'name',
				[
					'attribute' => 'audio_file_src',
					'format' => 'raw',
					'value' => function ($model) {
						return Html::tag('audio', Html::tag('source', '', [
							'src' => $model->audio_file_src
						]), [
							'controls' => 'controls'
						]);
					},
				],
				[
					'attribute' => 'artists',
					'value' => function ($model) {
						return implode(', ', \yii\helpers\ArrayHelper::getColumn($model->artists, 'name'));
					},
				],
				'album.name',
				'album_number',
				'is_explicit:boolean',
				[
					'class' => \app\components\FavoriteColumn::class
				],
				[
					'class' => 'yii\grid\ActionColumn',
				],
			],
		]); ?>
    </div>
</div>
