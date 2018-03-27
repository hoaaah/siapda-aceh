<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LspipEvaluasi */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lspip Evaluasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lspip-evaluasi-view">

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
            'tahun',
            'no_laporan',
            'tgl_laporan',
            'nilai_spip',
            'kat_spip',
            'f1',
            'f2',
            'f3',
            'f4',
            'f5',
            'f6',
            'f7',
            'f8',
            'f9',
            'f10',
            'f11',
            'f12',
            'f13',
            'f14',
            'f15',
            'f16',
            'f17',
            'f18',
            'f19',
            'f20',
            'f21',
            'f22',
            'f23',
            'f24',
            'f25',
            'ket',
            'user_id',
            'created',
            'updated',
        ],
    ]) ?>

</div>
