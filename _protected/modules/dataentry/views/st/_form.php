<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Lkegiatans */
/* @var $form yii\widgets\ActiveForm */

if (!$model->isNewRecord) {
    $model->kode_pemda_gabung = $model->province_id . '~' . $model->pemda_id;
    $model->kode_kegiatan_gabung = $model->kategori_id . '.' . $model->kelompok_id . '.' . $model->kegiatan_id;
}
?>

<div class="lkegiatans-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

    <?= $form->field($model, 'kode_pemda_gabung')->widget(Select2::class, [
        'data' => $model->pemdaArrayList($model->perwakilan_id),
        'options' => ['placeholder' => 'Pemda ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'kode_kegiatan_gabung')->widget(Select2::class, [
        'data' => $model->kegiatanArrayList(),
        'options' => ['placeholder' => 'Kegiatan ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'nama_kegiatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_st')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_st')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'id',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'Tanggal ST']
    ]) ?>


    <?= $form->field($model, 'no_laporan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ket')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_lap')->widget(\yii\jui\DatePicker::classname(), [
        'language' => 'id',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control', 'placeholder' => 'Tanggal Laporan']
    ]) ?>

    <?= $form->field($model, 'perpanjangan')->checkbox() ?>

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
                    $.pjax.reload({container:'#lkegiatans-pjax'});
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