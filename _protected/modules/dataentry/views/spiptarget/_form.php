<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\LspipTarget */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lspip-target-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'pemda_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($pemda, 'id', 'name'),
        'options' => ['placeholder' => 'Pemda ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>        

    <?= $form->field($model, 'kat_spip')->widget(Select2::classname(), [
        'data' => $model->categorySpip(),//ArrayHelper::map($refBantuan, 'id', 'name'),
        'options' => ['placeholder' => 'Level Target ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

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
                    $.pjax.reload({container:'#lspip-target-pjax'});
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