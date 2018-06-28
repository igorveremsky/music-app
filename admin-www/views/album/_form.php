<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Album */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="album-form box box-primary">
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="box-body table-responsive">

	    <?= $form->field($model, 'name') ?>

        <?php
        $genres = ArrayHelper::map(\app\models\Genre::find()->all(), 'id', 'name');
        ?>
		<?= $form->field($model, 'genre_id')->dropDownList($genres, [
			'prompt' => 'Choose genre'
		]) ?>

	    <?= $form->field($model, 'cover_img_src')->fileInput() ?>

        <?= $form->field($model, 'year') ?>

		<?= $form->field($model, 'records_name') ?>

    </div>
    <div class="box-footer">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
	<?php ActiveForm::end(); ?>
</div>
