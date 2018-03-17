<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Llkpd */
?>
<div class="llkpd-view">
 
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
