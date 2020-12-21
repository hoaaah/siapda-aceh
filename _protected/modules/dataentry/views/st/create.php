<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lkegiatans */

$this->title = 'Create Lkegiatans';
$this->params['breadcrumbs'][] = ['label' => 'Lkegiatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lkegiatans-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
