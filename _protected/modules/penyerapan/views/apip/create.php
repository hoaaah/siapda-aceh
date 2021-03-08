<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanRekening */

$this->title = 'Create Penyerapan Rekening';
$this->params['breadcrumbs'][] = ['label' => 'Penyerapan Rekenings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penyerapan-rekening-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
