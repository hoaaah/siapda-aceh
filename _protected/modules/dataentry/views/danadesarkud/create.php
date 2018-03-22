<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LdanadesaPenyaluranRkud */

$this->title = 'Create Ldanadesa Penyaluran Rkud';
$this->params['breadcrumbs'][] = ['label' => 'Ldanadesa Penyaluran Rkuds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ldanadesa-penyaluran-rkud-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
