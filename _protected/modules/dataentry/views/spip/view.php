<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lspips */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lspips', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lspips-view">

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
            'no_perkada',
            'tanggal_perkada',
            'pihak_bantu',
            'ket',
            'no_sk_satgas',
            'tanggal_sk',
            'user_id',
            'created',
            'updated',
        ],
    ]) ?>

</div>
