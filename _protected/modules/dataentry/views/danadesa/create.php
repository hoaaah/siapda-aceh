<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LdanadesaAlokasi */

$this->title = 'Create Ldanadesa Alokasi';
$this->params['breadcrumbs'][] = ['label' => 'Ldanadesa Alokasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ldanadesa-alokasi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
