<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lkada */

$this->title = 'Create Lkada';
$this->params['breadcrumbs'][] = ['label' => 'Lkadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lkada-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
