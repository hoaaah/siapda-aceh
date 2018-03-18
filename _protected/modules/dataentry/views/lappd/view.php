<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lappds */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lappds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lappds-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'bulan',
            'perwakilan_id',
            'province_id',
            'pemda_id',
            'katc_id',
            'tanggal',
            'pihak_bantu',
            'stat_id',
            'ket',
            'user_id',
            'created',
            'updated',
        ],
    ]) ?>

</div>
