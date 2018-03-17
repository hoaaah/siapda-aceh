<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Llkpd */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Llkpds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llkpd-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'bulan',
            'perwakilan_id',
            'province_id',
            'pemda_id',
            'stat_lk',
            'tanggal',
            'pihak_bantu_susun',
            'pihak_bantu_reviu',
            'opini_id',
            'ket',
            'user_id',
            'created',
            'updated',
        ],
    ]) ?>

</div>
