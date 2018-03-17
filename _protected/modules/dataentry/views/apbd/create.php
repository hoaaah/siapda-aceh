<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lapbds */

$this->title = 'Create Lapbds';
$this->params['breadcrumbs'][] = ['label' => 'Lapbds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lapbds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
