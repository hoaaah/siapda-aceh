<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RefPermasalahanPenyerapan */
?>
<div class="ref-permasalahan-penyerapan-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nm_permasalahan',
        ],
    ]) ?>

</div>
