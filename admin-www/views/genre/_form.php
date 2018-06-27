<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Genre */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="genre-form box box-primary">
	<?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

		<?= $form->field($model, 'name') ?>

    </div>
    <div class="box-footer">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
	<?php ActiveForm::end(); ?>
</div>