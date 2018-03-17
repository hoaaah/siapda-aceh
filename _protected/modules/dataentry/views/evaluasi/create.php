<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Levals */

$this->title = 'Create Levals';
$this->params['breadcrumbs'][] = ['label' => 'Levals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="levals-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
