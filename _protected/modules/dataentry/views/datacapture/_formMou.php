<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use dosamigos\ckeditor\CKEditor;
use dosamigos\ckeditor\CKEditorInline;

/* @var $this yii\web\View */
/* @var $model app\models\RefPemda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-pemda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'no_mou')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_mou_pemda')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_mou')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'ruang_lingkup')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic', //basic, standard, full
        'clientOptions' => [
            // 'filebrowserUploadUrl' => 'upload'
        ]
    ])->label(false);
    ?>      

    <?= $form->field($model, 'tanggal_berlaku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ket')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
