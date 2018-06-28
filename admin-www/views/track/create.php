<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Track */

$this->title = 'Create Track';
$this->params['breadcrumbs'][] = ['label' => 'Tracks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
