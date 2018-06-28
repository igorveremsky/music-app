<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Artists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artist-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Artist', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
	            'avatar_img_src:image',
	            [
		            'attribute' => 'type',
		            'value' => function ($model) {
                        $labels = $model::getTypeLabelOptions();
			            return \yii\helpers\ArrayHelper::getValue($labels, $model->type);
		            },
	            ],

	            [
		            'class' => \app\components\FavoriteColumn::class
	            ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
