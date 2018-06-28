<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Album */

$this->title = 'Create Album';
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
