<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanPermasalahan */

$this->title = 'Update Penyerapan Permasalahan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Penyerapan Permasalahans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="penyerapan-permasalahan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
