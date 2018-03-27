<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LspipEvaluasi */

$this->title = 'Update Lspip Evaluasi: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lspip Evaluasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lspip-evaluasi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
