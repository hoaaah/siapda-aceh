<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Llkpd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="llkpd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal')->widget(DatePicker::classname(), [
        'type' => DatePicker::TYPE_INPUT,
        'options' => ['placeholder' => 'Tanggal LKPD'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
        ]
    ]) ?>

    <?= $form->field($model, 'pihak_bantu_susun')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refBantuan, 'id', 'name'),
        'options' => ['placeholder' => 'Pihak Bantu Penyusunan ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'pihak_bantu_reviu')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refBantuan, 'id', 'name'),
        'options' => ['placeholder' => 'Pihak Bantu Reviu ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'opini_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refOpini, 'id', 'name'),
        'options' => ['placeholder' => 'Opini LKPD ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ket')->textInput(['maxlength' => true]) ?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
