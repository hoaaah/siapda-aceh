<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LprofilPemda */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lprofil Pemdas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lprofil-pemda-view">

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
            'created',
            'updated',
            'luas_wilayah',
            'jumlah_penduduk',
            'tahun_politik',
        ],
    ]) ?>

</div>
