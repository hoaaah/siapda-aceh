<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dataentry\models\LspipEvaluasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lspip-evaluasi-search">

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

    <?php // echo $form->field($model, 'tahun') ?>

    <?php // echo $form->field($model, 'no_laporan') ?>

    <?php // echo $form->field($model, 'tgl_laporan') ?>

    <?php // echo $form->field($model, 'nilai_spip') ?>

    <?php // echo $form->field($model, 'kat_spip') ?>

    <?php // echo $form->field($model, 'f1') ?>

    <?php // echo $form->field($model, 'f2') ?>

    <?php // echo $form->field($model, 'f3') ?>

    <?php // echo $form->field($model, 'f4') ?>

    <?php // echo $form->field($model, 'f5') ?>

    <?php // echo $form->field($model, 'f6') ?>

    <?php // echo $form->field($model, 'f7') ?>

    <?php // echo $form->field($model, 'f8') ?>

    <?php // echo $form->field($model, 'f9') ?>

    <?php // echo $form->field($model, 'f10') ?>

    <?php // echo $form->field($model, 'f11') ?>

    <?php // echo $form->field($model, 'f12') ?>

    <?php // echo $form->field($model, 'f13') ?>

    <?php // echo $form->field($model, 'f14') ?>

    <?php // echo $form->field($model, 'f15') ?>

    <?php // echo $form->field($model, 'f16') ?>

    <?php // echo $form->field($model, 'f17') ?>

    <?php // echo $form->field($model, 'f18') ?>

    <?php // echo $form->field($model, 'f19') ?>

    <?php // echo $form->field($model, 'f20') ?>

    <?php // echo $form->field($model, 'f21') ?>

    <?php // echo $form->field($model, 'f22') ?>

    <?php // echo $form->field($model, 'f23') ?>

    <?php // echo $form->field($model, 'f24') ?>

    <?php // echo $form->field($model, 'f25') ?>

    <?php // echo $form->field($model, 'ket') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
