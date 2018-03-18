<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lsimdas */

$this->title = 'Create Lsimdas';
$this->params['breadcrumbs'][] = ['label' => 'Lsimdas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lsimdas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
