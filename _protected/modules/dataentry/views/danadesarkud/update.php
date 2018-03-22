<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LdanadesaPenyaluranRkud */

$this->title = 'Update Ldanadesa Penyaluran Rkud: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ldanadesa Penyaluran Rkuds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ldanadesa-penyaluran-rkud-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
