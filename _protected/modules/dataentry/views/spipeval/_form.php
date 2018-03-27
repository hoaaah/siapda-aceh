<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LspipEvaluasi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lspip-evaluasi-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'bulan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'perwakilan_id')->textInput() ?>

    <?= $form->field($model, 'province_id')->textInput() ?>

    <?= $form->field($model, 'pemda_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_laporan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_laporan')->textInput() ?>

    <?= $form->field($model, 'nilai_spip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kat_spip')->textInput() ?>

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

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'updated')->textInput() ?>

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