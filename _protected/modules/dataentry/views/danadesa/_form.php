<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\LdanadesaAlokasi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ldanadesa-alokasi-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'pemda_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($pemda, 'id', 'name'),
        'options' => ['placeholder' => 'Pemda ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>    

    <?= $form->field($model, 'pendapatan_desa_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($pendapatanDesa, 'id', 'name'),
        'options' => ['placeholder' => 'Kategori Pendapatan Desa ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>    

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
                    $.pjax.reload({container:'#ldanadesa-alokasi-pjax'});
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