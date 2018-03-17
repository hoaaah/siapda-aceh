<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LprofilPemda */

$this->title = 'Create Lprofil Pemda';
$this->params['breadcrumbs'][] = ['label' => 'Lprofil Pemdas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lprofil-pemda-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
