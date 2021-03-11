<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use kidzen\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\PenyerapanRekening */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penyerapan-rekening-form">


    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="well text-danger">
        <?= $form->field($model, 'tanggal_pelaporan')->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'id',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => ['class' => 'form-control', 'readonly' => true]
        ]) ?>
        <p>Isi informasi tanggal cut-off dengan tanggal periode pelaporan dari LRA yang ada.</p>
    </div>




    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><i class="glyphicon glyphicon-envelope"></i> Jenis Pendapatan/Belanja/Pembiayaan</h4>
        </div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 34, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelRekening['4.1.1'],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'rek3_gabung',
                    'anggaran_tw1',
                    'anggaran_tw2',
                    'anggaran_tw3',
                    'anggaran_tw4',
                    'realisasi_tw1',
                    'realisasi_tw2',
                    'realisasi_tw3',
                    'realisasi_tw4',
                ],
            ]); ?>

            <div class="container-items">
                <!-- widgetContainer -->
                <?php foreach ($modelRekening as $i => $rincianRekening) : ?>
                    <div class="item card panel-default">
                        <!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left"> <?= $rekening3ArrayList[$rincianRekening->rek3_gabung] ?></h3>
                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $rincianRekening,
                                'attributes' => [
                                    'anggaran:decimal',
                                    'realisasi:decimal'
                                ],
                            ]) ?>
                            <?php
                            // necessary for update action.
                            if (!$rincianRekening->isNewRecord) {
                                echo Html::activeHiddenInput($rincianRekening, "[{$i}]id");
                            }
                            ?>
                            <?= $form->field($rincianRekening, "[{$i}]rek3_gabung")->widget(Select2::class, [
                                'data' => $rekening3ArrayList,
                                'disabled' => true,
                            ]) ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]anggaran_tw1", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
                                            'autoGroup' => true,
                                            'removeMaskOnSubmit' => true,
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]realisasi_tw1", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
                                            'autoGroup' => true,
                                            'removeMaskOnSubmit' => true,
                                        ],
                                    ]) ?>
                                </div>
                            </div><!-- .row -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]anggaran_tw2", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
                                            'autoGroup' => true,
                                            'removeMaskOnSubmit' => true,
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]realisasi_tw2", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
                                            'autoGroup' => true,
                                            'removeMaskOnSubmit' => true,
                                        ],
                                    ]) ?>
                                </div>
                            </div><!-- .row -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]anggaran_tw3", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
                                            'autoGroup' => true,
                                            'removeMaskOnSubmit' => true,
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]realisasi_tw3", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
                                            'autoGroup' => true,
                                            'removeMaskOnSubmit' => true,
                                        ],
                                    ]) ?>
                                </div>
                            </div><!-- .row -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]anggaran_tw4", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
                                            'autoGroup' => true,
                                            'removeMaskOnSubmit' => true,
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($rincianRekening, "[{$i}]realisasi_tw4", ['enableClientValidation' => false])->widget(MaskedInput::class, [
                                        'clientOptions' => [
                                            'alias' =>  'numeric',
                                            'groupSeparator' => '.',
                                            'radixPoint' => ',',
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
        <?= Html::submitButton('<i class="fa fa-plus"></i>  Simpan', ['id' => 'submit-button', 'class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(
    <<<JS
    $('form#dynamic-form').on('beforeSubmit',function(e)
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