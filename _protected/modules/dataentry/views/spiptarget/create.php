<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LspipTarget */

$this->title = 'Create Lspip Target';
$this->params['breadcrumbs'][] = ['label' => 'Lspip Targets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lspip-target-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
