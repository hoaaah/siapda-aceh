<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanRekening */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Penyerapan Rekenings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penyerapan-rekening-view">

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
            'tanggal_pelaporan',
            'kd_rek_1',
            'kd_rek_2',
            'kd_rek_3',
            'kd_rek_4',
            'kd_rek_5',
            'kd_rek_6',
            'anggaran',
            'realisasi',
        ],
    ]) ?>

</div>
