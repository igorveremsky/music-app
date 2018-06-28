<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Track */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="track-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name') ?>

        <?= $form->field($model, 'audio_file_src')->fileInput() ?>

        <?php
        $values = \yii\helpers\ArrayHelper::map(\app\models\Artist::find()->all(), 'id', 'name');
        ?>
        <?= $form->field($model, 'artist_ids')->checkboxList($values, [
	        'prompt' => 'Choose Artists'
        ]) ?>

        <?php
        $values = \yii\helpers\ArrayHelper::map(\app\models\Album::find()->all(), 'id', 'name');
        ?>
        <?= $form->field($model, 'album_id')->dropDownList($values, [
	        'prompt' => 'Choose Album'
        ]) ?>

        <?= $form->field($model, 'album_number') ?>

        <?= $form->field($model, 'is_explicit')->checkbox() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
