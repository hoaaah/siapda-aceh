<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dataentry\models\LevalsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="levals-search">

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

    <?php // echo $form->field($model, 'nilai_spip') ?>

    <?php // echo $form->field($model, 'kat_spip') ?>

    <?php // echo $form->field($model, 'nilai_sakip') ?>

    <?php // echo $form->field($model, 'kat_sakip') ?>

    <?php // echo $form->field($model, 'nilai_lppd') ?>

    <?php // echo $form->field($model, 'kat_lppd') ?>

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
