<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dataentry\models\LprofilPemdaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lprofil-pemda-search">

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

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'luas_wilayah') ?>

    <?php // echo $form->field($model, 'jumlah_penduduk') ?>

    <?php // echo $form->field($model, 'tahun_politik') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
