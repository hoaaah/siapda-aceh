<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;

$tahunTerpilih = (int) substr($model->bulan, 0, 4);
$tahunArray = [$tahunTerpilih => $tahunTerpilih, ($tahunTerpilih-1) => ($tahunTerpilih-1)];
?>

<div class="levals-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'tahun')->widget(Select2::classname(), [
        'data' => $tahunArray,
        'options' => ['placeholder' => 'Evaluasi atas Tahun ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>    

    <?= $form->field($model, 'nilai_spip', ['enableClientValidation' => false])->widget(MaskedInput::classname(), [
            'clientOptions' => [
                'alias' =>  'decimal',
                // 'groupSeparator' => ',',
                'groupSeparator' => '.',
                'radixPoint'=>',',                
                'autoGroup' => true,
                'removeMaskOnSubmit' => true,
            ],
    ]) ?>
    
    <?= $form->field($model, 'nilai_sakip', ['enableClientValidation' => false])->widget(MaskedInput::classname(), [
            'clientOptions' => [
                'alias' =>  'decimal',
                // 'groupSeparator' => ',',
                'groupSeparator' => '.',
                'radixPoint'=>',',                
                'autoGroup' => true,
                'removeMaskOnSubmit' => true,
            ],
    ]) ?>

    <?= $form->field($model, 'nilai_lppd', ['enableClientValidation' => false])->widget(MaskedInput::classname(), [
            'clientOptions' => [
                'alias' =>  'decimal',
                // 'groupSeparator' => ',',
                'groupSeparator' => '.',
                'radixPoint'=>',',                
                'autoGroup' => true,
                'removeMaskOnSubmit' => true,
            ],
    ]) ?>            

    <?php // $form->field($model, 'kat_spip')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'kat_sakip')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'kat_lppd')->textInput(['maxlength' => true]) ?>

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
                    $.pjax.reload({container:'#evaluasi-pjax'});
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