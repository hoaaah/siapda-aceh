<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanRekening */

$this->title = 'Update Penyerapan Rekening: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Penyerapan Rekenings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="penyerapan-rekening-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
