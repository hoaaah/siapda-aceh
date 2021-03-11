<?php

use app\models\RefPermasalahanPenyerapan;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanPermasalahan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penyerapan-permasalahan-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'bulan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'perwakilan_id')->textInput() ?>

    <?= $form->field($model, 'province_id')->textInput() ?>

    <?= $form->field($model, 'pemda_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_pelaporan')->textInput() ?>

    <?= $form->field($model, 'permasalahan_id')->textInput() ?>

    <?= $form->field($model, 'permasalahan_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(RefPermasalahanPenyerapan::find()->all(), 'id', 'nama_permasalahan')
    ]) ?>

    <?= $form->field($model, 'uraian_permasalahan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJs(
    <<<JS
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
                    $.pjax.reload({container:'#penyerapan-permasalahan-pjax'});
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