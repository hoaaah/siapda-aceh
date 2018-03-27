<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LspipEvaluasi */

$this->title = 'Create Lspip Evaluasi';
$this->params['breadcrumbs'][] = ['label' => 'Lspip Evaluasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lspip-evaluasi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
