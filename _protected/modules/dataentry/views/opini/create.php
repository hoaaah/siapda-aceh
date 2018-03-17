<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Llkpd */

$this->title = 'Create Llkpd';
$this->params['breadcrumbs'][] = ['label' => 'Llkpds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llkpd-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
