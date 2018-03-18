<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lappds */

$this->title = 'Create Lappds';
$this->params['breadcrumbs'][] = ['label' => 'Lappds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lappds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
