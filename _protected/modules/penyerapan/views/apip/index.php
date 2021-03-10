<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\penyerapan\models\PenyerapanRekeningSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/

$this->title = 'Penyerapan Anggaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penyerapan-rekening-index">

    <?= GridView::widget([
        'id' => 'penyerapan-rekening',
        'dataProvider' => $dataProvider,
        // 'export' => true,
        'responsive' => true,
        'hover' => true,
        // 'resizableColumns' => true,
        'panel' => ['type' => 'primary', 'heading' => 'Anggaran dan Realisasi Per Jenis'],
        'responsiveWrap' => false,
        'toolbar' => [
            [
                'content' =>
                Html::a(
                    '<i class="glyphicon glyphicon-plus"></i> Input Data Penyerapan',
                    ['create'],
                    [
                        'class' => 'btn btn-success',
                        'data-toggle' => "modal",
                        'data-target' => "#myModal",
                        'data-title' => "Input Data Penyerapan",
                    ]
                ) .
                    Html::a(
                        '<i class="glyphicon glyphicon-repeat"></i>',
                        [''],
                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']
                    ) .
                    '{toggleData}' .
                    '{export}'
            ],
        ],
        'pager' => [
            'firstPageLabel' => 'Awal',
            'lastPageLabel'  => 'Akhir'
        ],
        'pjax' => true,
        'pjaxSettings' => [
            'options' => ['id' => 'penyerapan-rekening-pjax', 'timeout' => 5000],
        ],
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            // [
            //     'attribute' => 'bulan',
            //     'group' => true,
            // ],
            [
                'attribute' => 'tanggal_pelaporan',
                'format' => 'date',
                'group' => true,
            ],
            'refRek3.nm_rek_3',
            'anggaran:decimal',
            'realisasi:decimal',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'noWrap' => true,
                'vAlign' => 'top',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'ubah'),
                                'data-toggle' => "modal",
                                'data-target' => "#myModal",
                                'data-title' => "Ubah",
                            ]
                        );
                    },
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'lihat'),
                                'data-toggle' => "modal",
                                'data-target' => "#myModal",
                                'data-title' => "Lihat",
                            ]
                        );
                    },
                ]
            ],
        ],
    ]); ?>



    <?= GridView::widget([
        'id' => 'penyerapan-urusan',
        'dataProvider' => $dataProvider,
        // 'export' => true,
        'responsive' => true,
        'hover' => true,
        // 'resizableColumns' => true,
        'panel' => ['type' => 'primary', 'heading' => 'Anggaran dan Realisasi Per Urusan'],
        'responsiveWrap' => false,
        'toolbar' => [
            [
                'content' =>
                Html::a(
                    '<i class="glyphicon glyphicon-plus"></i> Input Data Urusan',
                    ['create-urusan'],
                    [
                        'class' => 'btn btn-success',
                        'data-toggle' => "modal",
                        'data-target' => "#myModal",
                        'data-title' => "Input Data Urusan",
                    ]
                ) .
                    '{toggleData}' .
                    '{export}'
            ],
        ],
        'pager' => [
            'firstPageLabel' => 'Awal',
            'lastPageLabel'  => 'Akhir'
        ],
        'pjax' => true,
        'pjaxSettings' => [
            'options' => ['id' => 'penyerapan-urusan-pjax', 'timeout' => 5000],
        ],
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            // [
            //     'attribute' => 'bulan',
            //     'group' => true,
            // ],
            [
                'attribute' => 'tanggal_pelaporan',
                'group' => true,
            ],
            'refRek3.nm_rek_3',
            'anggaran:decimal',
            'realisasi:decimal',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'noWrap' => true,
                'vAlign' => 'top',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'ubah'),
                                'data-toggle' => "modal",
                                'data-target' => "#myModal",
                                'data-title' => "Ubah",
                            ]
                        );
                    },
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'lihat'),
                                'data-toggle' => "modal",
                                'data-target' => "#myModal",
                                'data-title' => "Lihat",
                            ]
                        );
                    },
                ]
            ],
        ],
    ]); ?>


    <?= GridView::widget([
        'id' => 'penyerapan-triwulan',
        'dataProvider' => $dataProviderTriwulan,
        // 'export' => true,
        'responsive' => true,
        'hover' => true,
        // 'resizableColumns' => true,
        'panel' => ['type' => 'primary', 'heading' => 'Anggaran dan Realisasi Per Jenis Per Triwulan'],
        'responsiveWrap' => false,
        'toolbar' => [
            [
                'content' =>
                Html::a(
                    '<i class="glyphicon glyphicon-plus"></i> Input Data Triwulan',
                    ['/penyerapan/triwulan/create'],
                    [
                        'class' => 'btn btn-success',
                        'data-toggle' => "modal",
                        'data-target' => "#myModal",
                        'data-title' => "Input Data Triwulan",
                    ]
                ) .
                    '{toggleData}' .
                    '{export}'
            ],
        ],
        'pager' => [
            'firstPageLabel' => 'Awal',
            'lastPageLabel'  => 'Akhir'
        ],
        'pjax' => true,
        'pjaxSettings' => [
            'options' => ['id' => 'penyerapan-triwulan-pjax', 'timeout' => 5000],
        ],
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            // [
            //     'attribute' => 'bulan',
            //     'group' => true,
            // ],
            [
                'attribute' => 'tanggal_pelaporan',
                'group' => true,
            ],
            'refRek3.nm_rek_3',
            // 'anggaran:decimal',
            // 'realisasi:decimal',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'noWrap' => true,
                'vAlign' => 'top',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'ubah'),
                                'data-toggle' => "modal",
                                'data-target' => "#myModal",
                                'data-title' => "Ubah",
                            ]
                        );
                    },
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'lihat'),
                                'data-toggle' => "modal",
                                'data-target' => "#myModal",
                                'data-title' => "Lihat",
                            ]
                        );
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
    'size' => 'modal-lg'
]);

echo '...';

Modal::end();

$this->registerJs(
    <<<JS
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
JS
);
?>