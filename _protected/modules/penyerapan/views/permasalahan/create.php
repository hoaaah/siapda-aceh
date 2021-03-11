<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanPermasalahan */

$this->title = 'Create Penyerapan Permasalahan';
$this->params['breadcrumbs'][] = ['label' => 'Penyerapan Permasalahans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penyerapan-permasalahan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
