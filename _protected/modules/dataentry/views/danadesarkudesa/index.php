<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\dataentry\models\LdanadesaPenyaluranRkudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/

$this->title = 'Penyaluran Dana Desa ke RKUDesa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ldanadesa-penyaluran-rkudesa-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= $this->render('_form', [
        'model' => $model,
        'pemda_id' => $pemda_id,
        'pendapatan_desa_id' => $pendapatan_desa_id,
    ]) ?>

    <?= GridView::widget([
        'id' => 'ldanadesa-penyaluran-rkudesa',    
        'dataProvider' => $dataProvider,
        'export' => false, 
        'responsive'=>true,
        'hover'=>true,     
        'resizableColumns'=>true,
        'panel'=>['type'=>'primary', 'heading'=>$this->title],
        'responsiveWrap' => false,        
        'toolbar' => [
            [
                '{toggleData}',
                '{export}',
                // 'content' => $this->render('_search', ['model' => $searchModel, 'Tahun' => $Tahun]),
            ],
        ],       
        'pager' => [
            'firstPageLabel' => 'Awal',
            'lastPageLabel'  => 'Akhir'
        ],
        'pjax'=>true,
        'pjaxSettings'=>[
            'options' => ['id' => 'ldanadesa-penyaluran-rkudesa-pjax', 'timeout' => 5000],
        ],        
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            // 'pemda_id',
            // 'pendapatan_desa_id',
            'jumlah_desa',
            'nilai:decimal',
            // 'user_id',
            // 'created',
            // 'updated',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'noWrap' => true,
                'vAlign'=>'top',
                'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,
                            [
                                'id' => 'update-rkud-'.$model->id,
                                'title' => Yii::t('yii', 'ubah'),
                                'data-pjax' => 0
                                // 'data-toggle'=>"modal",
                                // 'data-target'=>"#myModal",
                                // 'data-title'=> "Ubah",
                            ]);
                        },
                        'view' => function ($url, $model) {
                          return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url,
                              [  
                                 'title' => Yii::t('yii', 'lihat'),
                                 'data-toggle'=>"modal",
                                 'data-target'=>"#myModal",
                                 'data-title'=> "Lihat",
                              ]);
                        },                        
                ]
            ],
        ],
    ]); ?>
</div>
<?php Modal::begin([
    'id' => 'myModal',
    'header' => '<h4 class="modal-title">Lihat lebih...</h4>',
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ], 
]);
 
echo '...';
 
Modal::end();

$this->registerJs(<<<JS
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
        var title = button.data('title') 
        var href = button.attr('href') 
        modal.find('.modal-title').html(title)
        modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
        $.post(href)
        .done(function( data ) {
            modal.find('.modal-body').html(data)
        });
    })

    // $("a[id^='update-rkud-']").on( "click", function(event){
    $('#myModal').on("click", "a[id^='update-rkud-']", function(event){
        event.preventDefault();
        var href = $(this).attr('href');
        $.get(href).done(function( data ) {
            $('#penyaluran-rkud-form').attr('action', href);
            $('#ldanadesapenyaluranrkud-nilai').val(data.nilai);
            $('#ldanadesapenyaluranrkud-jumlah_desa').val(data.jumlah_desa);
            $('#salur-rkud-submit-button').removeClass('btn-success');
            $('#salur-rkud-submit-button').addClass('btn-primary');
            $('#salur-rkud-submit-button').html('Update');
        });
    } );
    $('#myModal').on("click", "a[title^='Hapus']", function(event){
        event.preventDefault();
        console.log("Hapus G Nih?")
    });
JS
);
?>