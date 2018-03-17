<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lkasus */

$this->title = 'Create Lkasus';
$this->params['breadcrumbs'][] = ['label' => 'Lkasuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lkasus-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
