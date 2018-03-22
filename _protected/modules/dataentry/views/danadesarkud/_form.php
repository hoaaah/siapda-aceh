<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LdanadesaPenyaluranRkud */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ldanadesa-penyaluran-rkud-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bulan')->textInput() ?>

    <?= $form->field($model, 'perwakilan_id')->textInput() ?>

    <?= $form->field($model, 'province_id')->textInput() ?>

    <?= $form->field($model, 'pemda_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pendapatan_desa_id')->textInput() ?>

    <?= $form->field($model, 'jumlah_desa')->textInput() ?>

    <?= $form->field($model, 'nilai')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

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
                    $.pjax.reload({container:'#ldanadesa-penyaluran-rkud-pjax'});
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