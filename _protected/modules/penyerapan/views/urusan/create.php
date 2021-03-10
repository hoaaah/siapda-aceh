<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanUrusan */

$this->title = 'Create Penyerapan Urusan';
$this->params['breadcrumbs'][] = ['label' => 'Penyerapan Urusans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penyerapan-urusan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
