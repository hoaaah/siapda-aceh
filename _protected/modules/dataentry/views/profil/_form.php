<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\LprofilPemda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lprofil-pemda-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'luas_wilayah', ['enableClientValidation' => false])->widget(MaskedInput::classname(), [
            'clientOptions' => [
                'alias' =>  'decimal',
                // 'groupSeparator' => ',',
                'groupSeparator' => '.',
                'radixPoint'=>',',                
                'autoGroup' => true,
                'removeMaskOnSubmit' => true,
            ],
    ]) ?>

    <?= $form->field($model, 'jumlah_penduduk', ['enableClientValidation' => false])->widget(MaskedInput::classname(), [
            'clientOptions' => [
                'alias' =>  'decimal',
                // 'groupSeparator' => ',',
                'groupSeparator' => '.',
                'radixPoint'=>',',                
                'autoGroup' => true,
                'removeMaskOnSubmit' => true,
            ],
    ]) ?>    

    <?= $form->field($model, 'tahun_politik')->widget(SwitchInput::classname(), [
        'id' => 'tahunPolitik-'.$model['id'],
        'pluginOptions' => [
            // 'size' => 'large',
            'onText' => 'Ya',
            'offText' => 'Tidak',
        ]
    ]) ?>

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
                    $.pjax.reload({container:'#profil-pjax'});
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