<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\LspipEvaluasi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lspip-evaluasi-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
    
    <?= $form->field($model, 'pemda_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($pemda, 'id', 'name'),
        'options' => ['placeholder' => 'Pemda ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>    

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_laporan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_laporan')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'id',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'Tanggal Laporan']
    ]) ?>    

    <?php // $form->field($model, 'nilai_spip')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'kat_spip')->textInput() ?>

    <?= $form->field($model, 'f1')->textInput() ?>

    <?= $form->field($model, 'f2')->textInput() ?>

    <?= $form->field($model, 'f3')->textInput() ?>

    <?= $form->field($model, 'f4')->textInput() ?>

    <?= $form->field($model, 'f5')->textInput() ?>

    <?= $form->field($model, 'f6')->textInput() ?>

    <?= $form->field($model, 'f7')->textInput() ?>

    <?= $form->field($model, 'f8')->textInput() ?>

    <?= $form->field($model, 'f9')->textInput() ?>

    <?= $form->field($model, 'f10')->textInput() ?>

    <?= $form->field($model, 'f11')->textInput() ?>

    <?= $form->field($model, 'f12')->textInput() ?>

    <?= $form->field($model, 'f13')->textInput() ?>

    <?= $form->field($model, 'f14')->textInput() ?>

    <?= $form->field($model, 'f15')->textInput() ?>

    <?= $form->field($model, 'f16')->textInput() ?>

    <?= $form->field($model, 'f17')->textInput() ?>

    <?= $form->field($model, 'f18')->textInput() ?>

    <?= $form->field($model, 'f19')->textInput() ?>

    <?= $form->field($model, 'f20')->textInput() ?>

    <?= $form->field($model, 'f21')->textInput() ?>

    <?= $form->field($model, 'f22')->textInput() ?>

    <?= $form->field($model, 'f23')->textInput() ?>

    <?= $form->field($model, 'f24')->textInput() ?>

    <?= $form->field($model, 'f25')->textInput() ?>

    <?= $form->field($model, 'ket')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJs(<<<JS
    $('form#{$model->formName()}').on('beforeSubmit',function(e)
    {
        var \$form = $(this);
        $.post(
            \$form.attr("action"), //serialize Yii2 form 
            \$form.serialize()
        )
            .done(function(result){
                if(result == 1)
                {
                    $("#myModal").modal('hide'); //hide modal after submit
                    $.pjax.reload({container:'#lspip-evaluasi-pjax'});
                }else
                {
                    $("#message").html(result);
                }
            }).fail(function(){
                console.log("server error");
            });
        return false;
    });
JS
);
?>