<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lsimdas */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lsimdas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lsimdas-view">

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
            'use_keu',
            'use_keu_penganggaran',
            'use_keu_penatausahaan',
            'use_keu_pelaporan',
            'use_bmd',
            'use_gaji',
            'use_pendapatan',
            'use_perencanaan',
            'ket',
            'user_id',
            'created',
            'updated',
            'ver_keu',
            'ver_bmd',
            'ver_gaji',
            'ver_pendapatan',
            'ver_perencanaan',
        ],
    ]) ?>

</div>
