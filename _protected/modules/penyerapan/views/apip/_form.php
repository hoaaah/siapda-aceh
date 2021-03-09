<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use kidzen\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanRekening */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penyerapan-rekening-form">

    
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'bulan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'perwakilan_id')->textInput() ?>

    <?= $form->field($model, 'province_id')->textInput() ?>

    <?= $form->field($model, 'pemda_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_pelaporan')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>


    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i> Jenis Pendapatan/Belanja/Pembiayaan</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 34, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelRekening[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'rek3_gabung',
                    'anggaran',
                    'realisasi',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($modelRekening as $i => $rincianRekening): ?>
                <div class="item card panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left"> Jenis</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $rincianRekening->isNewRecord) {
                                echo Html::activeHiddenInput($rincianRekening, "[{$i}]id");
                            }
                        ?>
                        <?= $form->field($rincianRekening, "[{$i}]rek3_gabung")->widget(Select2::class, [
                            'data' => $rekening3ArrayList
                        ]) ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($rincianRekening, "[{$i}]anggaran", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                    'clientOptions' => [
                                        'alias' =>  'numeric',      
                                        'groupSeparator' => '.',
                                        'radixPoint'=>',',                
                                        'autoGroup' => true,
                                        'removeMaskOnSubmit' => true,
                                    ],      
                                ]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($rincianRekening, "[{$i}]realisasi", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                    'clientOptions' => [
                                        'alias' =>  'numeric',      
                                        'groupSeparator' => '.',
                                        'radixPoint'=>',',                
                                        'autoGroup' => true,
                                        'removeMaskOnSubmit' => true,
                                    ],      
                                ]) ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJs(<<<JS
    $('form#dynamic-form').on('beforeSubmit',function(e)
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
                    $.pjax.reload({container:'#penyerapan-rekening-pjax'});
                }else
                {
                    $("#message").html(result);
                }
            }).fail(function(){
                console.log("server error");
            });
        return false;
    });

    $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
        console.log("beforeInsert");
    });

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        console.log("afterInsert");
    });

    $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
        if (! confirm("Are you sure you want to delete this item?")) {
            return false;
        }
        return true;
    });

    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        console.log("Deleted item!");
    });

    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("Limit reached");
    });    
JS
);
?>