<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Llkpd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="llkpd-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'tanggal')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'id',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'Tanggal LKPD']
    ]) ?>      

    <?= $form->field($model, 'pihak_bantu_susun')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refBantuan, 'id', 'name'),
        'options' => ['placeholder' => 'Pihak Bantu Penyusunan ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'pihak_bantu_reviu')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refBantuan, 'id', 'name'),
        'options' => ['placeholder' => 'Pihak Bantu Reviu ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'opini_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refOpini, 'id', 'name'),
        'options' => ['placeholder' => 'Opini LKPD ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ket')->textInput(['maxlength' => true]) ?>
  
    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>

<?php $script = <<<JS
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
                $.pjax.reload({container:'#opini-pjax'});
            }else
            {
                $("#message").html(result);
            }
        }).fail(function(){
            console.log("server error");
        });
    return false;
});

JS;
$this->registerJs($script);
?>