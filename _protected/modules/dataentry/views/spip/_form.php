<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Lspips */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lspips-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'pemda_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($pemda, 'id', 'name'),
        'options' => ['placeholder' => 'Pemda ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>    

    <?= $form->field($model, 'no_perkada')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_perkada')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'id',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'Tanggal Perkada']
    ]) ?>      

    <?= $form->field($model, 'pihak_bantu')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refBantuan, 'id', 'name'),
        'options' => ['placeholder' => 'Pihak Bantu Penyusunan ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'no_sk_satgas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_sk')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'id',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'Tanggal SK Satgas']
    ]) ?>    

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
                    $.pjax.reload({container:'#lspips-pjax'});
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