<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Levals */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Levals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="levals-view">

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
            'nilai_spip',
            'kat_spip',
            'nilai_sakip',
            'kat_sakip',
            'nilai_lppd',
            'kat_lppd',
            'ket',
            'user_id',
            'created',
            'updated',
        ],
    ]) ?>

</div>
