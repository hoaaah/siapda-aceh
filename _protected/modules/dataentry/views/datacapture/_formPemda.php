<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use yii\helpers\Url;

?>

<div class="lkada-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'options'=>['enctype'=>'multipart/form-data'] // important
    ]); ?>

    <?= $form->field($model, 'ibukota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=>[
            'maxFileCount' => 1,
            'allowedFileExtensions' => ['jpg','gif','png'],
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
            // this line for image preview
            'initialPreview'=>[
                $model->saved_image ? $model->imageUrl : null,
            ],
            'initialPreviewAsData'=>true,
            'initialCaption'=> $model->saved_image ? $model->image_name : null,
            'initialPreviewConfig' => [
                $model->saved_image ? [
                    'caption' => $model->image_name, 
                    'size' => filesize($model->getImage()), 
                    'url' => Url::to(['delete-image', 'id' => $model->id, 'file' => $model->saved_image])
                ] : null,
            ],
            'overwriteInitial'=> false,
    ]]) ?>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
/*
$this->registerJs(<<<JS
    $('form#{$model->formName()}').on('beforeSubmit',function(e)
    {
        // var \$form = $(this);
        // $.post(
        //     \$form.attr("action"), //serialize Yii2 form 
        //     \$form.serialize()
        // )
        //     .done(function(result){
        //         if(result == 1)
        //         {
        //             $("#myModal").modal('hide'); //hide modal after submit
        //             $.pjax.reload({container:'#lkada-pjax'});
        //         }else
        //         {
        //             $("#message").html(result);
        //         }
        //     }).fail(function(){
        //         console.log("server error");
        //     });
        // return false;

        $.ajax({
            // Your server script to process the upload
            url: $(this).attr("action"),
            type: 'POST',

            // Form data
            data: new FormData($('form')[0]),

            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,

            // Custom XMLHttpRequest
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    // For handling the progress of the upload
                    myXhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            $('progress').attr({
                                value: e.loaded,
                                max: e.total,
                            });
                        }
                    } , false);
                }
                return myXhr;
            },
        })
        .done(function(result){
            if(result == 1)
            {
                $("#myModal").modal('hide'); //hide modal after submit
                $.pjax.reload({container:'#lkada-pjax'});
            }else
            {
                $("#message").html(result);
            }
        }).fail(function(){
            console.log("server error");
        });        
    });
JS
);
*/
?>