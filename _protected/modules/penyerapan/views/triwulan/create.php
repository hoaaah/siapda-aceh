<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanTriwulan */

$this->title = 'Create Penyerapan Triwulan';
$this->params['breadcrumbs'][] = ['label' => 'Penyerapan Triwulans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penyerapan-triwulan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
