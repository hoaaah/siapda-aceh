<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\penyerapan\models\PenyerapanTriwulanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penyerapan-triwulan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id')->textInput(
                ['class' => 'form-control input-sm pull-right','placeholder' => Yii::t('app', 'id')])->label(false) ?>

    <?= $form->field($model, 'bulan')->textInput(
                ['class' => 'form-control input-sm pull-right','placeholder' => Yii::t('app', 'bulan')])->label(false) ?>

    <?= $form->field($model, 'perwakilan_id')->textInput(
                ['class' => 'form-control input-sm pull-right','placeholder' => Yii::t('app', 'perwakilan_id')])->label(false) ?>

    <?= $form->field($model, 'province_id')->textInput(
                ['class' => 'form-control input-sm pull-right','placeholder' => Yii::t('app', 'province_id')])->label(false) ?>

    <?= $form->field($model, 'pemda_id')->textInput(
                ['class' => 'form-control input-sm pull-right','placeholder' => Yii::t('app', 'pemda_id')])->label(false) ?>

    <?php // echo $form->field($model, 'tanggal_pelaporan') ?>

    <?php // echo $form->field($model, 'kd_rek_1') ?>

    <?php // echo $form->field($model, 'kd_rek_2') ?>

    <?php // echo $form->field($model, 'kd_rek_3') ?>

    <?php // echo $form->field($model, 'kd_rek_4') ?>

    <?php // echo $form->field($model, 'kd_rek_5') ?>

    <?php // echo $form->field($model, 'kd_rek_6') ?>

    <?php // echo $form->field($model, 'anggaran_tw1') ?>

    <?php // echo $form->field($model, 'anggaran_tw2') ?>

    <?php // echo $form->field($model, 'anggaran_tw3') ?>

    <?php // echo $form->field($model, 'anggaran_tw4') ?>

    <?php // echo $form->field($model, 'realisasi_tw1') ?>

    <?php // echo $form->field($model, 'realisasi_tw2') ?>

    <?php // echo $form->field($model, 'realisasi_tw3') ?>

    <?php // echo $form->field($model, 'realisasi_tw4') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
