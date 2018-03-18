<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\Lsimdas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lsimdas-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <div class="col-md-3">

    <?= $form->field($model, 'use_keu')->widget(SwitchInput::classname(), [
        'id' => 'use_keu-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    <div class="col-md-3">

    <?= $form->field($model, 'use_keu_penganggaran')->widget(SwitchInput::classname(), [
        'id' => 'use_keupenganggaran-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    <div class="col-md-3">    

    <?= $form->field($model, 'use_keu_penatausahaan')->widget(SwitchInput::classname(), [
        'id' => 'use_keupenatausahaan-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    <div class="col-md-3">    

    <?= $form->field($model, 'use_keu_pelaporan')->widget(SwitchInput::classname(), [
        'id' => 'use_keupelaporan-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    <div class="col-md-3">    

    <?= $form->field($model, 'use_cms')->widget(SwitchInput::classname(), [
        'id' => 'use_bmd-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    <div class="col-md-3">    

    <?= $form->field($model, 'use_bmd')->widget(SwitchInput::classname(), [
        'id' => 'use_bmd-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    <div class="col-md-3">    

    <?= $form->field($model, 'use_pendapatan')->widget(SwitchInput::classname(), [
        'id' => 'use_pendapatan-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    <div class="col-md-3">    

    <?= $form->field($model, 'use_perencanaan')->widget(SwitchInput::classname(), [
        'id' => 'use_perencanaan-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

    </div>
    
    <?= $form->field($model, 'ket')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ver_keu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ver_bmd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ver_gaji')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ver_pendapatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ver_perencanaan')->textInput(['maxlength' => true]) ?>

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
                    $.pjax.reload({container:'#simda-pjax'});
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