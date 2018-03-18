<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Lappds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lappds-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'katc_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(\app\models\Katcs::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Penyampaian Laporan ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label("Penyampaian Laporan") ?>    

    <?= $form->field($model, 'tanggal')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'id',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'Tanggal Penyampaian']
    ]) ?>  

    <?= $form->field($model, 'pihak_bantu')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($refBantuan, 'id', 'name'),
        'options' => ['placeholder' => 'Pihak Bantu Penyusunan ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'stat_id')->widget(Select2::classname(), [
        'data' => [
            0 => 'Tidak Tepat Waktu',
            1 => 'Tepat Waktu',
        ],
        'options' => ['placeholder' => 'Status ...'],
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
                    $.pjax.reload({container:'#lappds-pjax'});
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