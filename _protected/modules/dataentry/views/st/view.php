<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lkegiatans */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lkegiatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lkegiatans-view">

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
            'kategori_id',
            'kelompok_id',
            'kegiatan_id',
            'nama_kegiatan',
            'no_st',
            'tanggal_st',
            'no_laporan',
            'ket',
            'user_id',
            'created',
            'updated',
            'tanggal_lap',
            'perpanjangan',
        ],
    ]) ?>

</div>
