<?php

use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanRekening */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penyerapan-rekening-form">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'anggaran:decimal',
            'realisasi:decimal'
        ],
    ]) ?>

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, "anggaran_tw1", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, "realisasi_tw1", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, "anggaran_tw2", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, "realisasi_tw2", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, "anggaran_tw3", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, "realisasi_tw3", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, "anggaran_tw4", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, "realisasi_tw4", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'clientOptions' => [
                    'alias' =>  'numeric',
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ]) ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-plus"></i>  Simpan', ['id' => 'submit-button', 'class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJs(
    <<<JS
    $('form#{$model->formName()}').on('beforeSubmit',function(e)
    {
        $("#submit-button").attr("disabled", "disabled");
        $("#submit-button").html('<i class="fa fa-spinner fa-spin"></i> Simpan');
        var \$form = $(this);
        $.post(
            \$form.attr("action"), //serialize Yii2 form 
            \$form.serialize()
        )
            .done(function(result){
                if(result == 1)
                {
                    $("#myModal").modal('hide'); //hide modal after submit
                    $.pjax.reload({container:'#penyerapan-triwulan-pjax'});
                }else
                {
                    $("#submit-button").removeAttr("disabled");
                    $("#submit-button").html('<i class="fa fa-plus"></i> Simpan');
                    $("#message").html(result);
                    $.notify({message: result}, {type: 'danger', z_index: 10031})
                }
            }).fail(function(){
                $("#submit-button").removeAttr("disabled");
                $("#submit-button").html('<i class="fa fa-plus"></i> Simpan');
                $.notify({message: "Server Error, refresh and try again."}, {type: 'danger', z_index: 10031})
            });
        return false;
    });
JS
);
?>