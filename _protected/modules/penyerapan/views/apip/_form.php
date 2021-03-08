<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanRekening */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penyerapan-rekening-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'bulan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'perwakilan_id')->textInput() ?>

    <?= $form->field($model, 'province_id')->textInput() ?>

    <?= $form->field($model, 'pemda_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_pelaporan')->textInput() ?>

    <?= $form->field($model, 'kd_rek_1')->textInput() ?>

    <?= $form->field($model, 'kd_rek_2')->textInput() ?>

    <?= $form->field($model, 'kd_rek_3')->textInput() ?>

    <?= $form->field($model, 'kd_rek_4')->textInput() ?>

    <?= $form->field($model, 'kd_rek_5')->textInput() ?>

    <?= $form->field($model, 'kd_rek_6')->textInput() ?>

    <?= $form->field($model, 'anggaran')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'realisasi')->textInput(['maxlength' => true]) ?>

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
                    $.pjax.reload({container:'#penyerapan-rekening-pjax'});
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