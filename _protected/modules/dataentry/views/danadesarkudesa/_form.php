<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\LdanadesaPenyaluranRkud */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ldanadesa-penyaluran-rkudesa-form">

    <?php $form = ActiveForm::begin(['id' => 'penyaluran-rkudesa-form', 'action' => ['create', 'pemda_id' => $pemda_id, 'pendapatan_desa_id' => $pendapatan_desa_id]]); ?>

    <?= $form->field($model, 'jumlah_desa')->textInput() ?>

    <?= $form->field($model, 'nilai', ['enableClientValidation' => false])->widget(MaskedInput::classname(), [
        'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => '.',
            'radixPoint'=>',',                
            'autoGroup' => true,
            'removeMaskOnSubmit' => true,
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id' => 'salur-rkudesa-submit-button', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$currentUrl = Yii::$app->request->url;
$this->registerJs(<<<JS
    $('#penyaluran-rkudesa-form').on('beforeSubmit',function(e)
    {
        var \$form = $(this);
        $.post(
            \$form.attr("action"), //serialize Yii2 form 
            \$form.serialize()
        )
            .done(function(result){
                if(result == 1)
                {
                    // $("#myModal").modal('hide'); //hide modal after submit
                    $('#penyaluran-rkudesa-form')[0].reset();
                    $.pjax.reload({container:'#ldanadesa-penyaluran-rkudesa-pjax', url: '$currentUrl'});
                    
                    $('#penyaluran-rkudesa-form').attr('action', '$currentUrl');
                    $('#salur-rkudesa-submit-button').removeClass('btn-primary');
                    $('#salur-rkudesa-submit-button').addClass('btn-success');
                    $('#salur-rkudesa-submit-button').html('Create');
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