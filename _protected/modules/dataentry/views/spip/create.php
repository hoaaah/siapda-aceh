<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lspips */

$this->title = 'Create Lspips';
$this->params['breadcrumbs'][] = ['label' => 'Lspips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lspips-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
