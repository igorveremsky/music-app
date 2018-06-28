<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Artist */

$this->title = 'Create Artist';
$this->params['breadcrumbs'][] = ['label' => 'Artists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artist-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
