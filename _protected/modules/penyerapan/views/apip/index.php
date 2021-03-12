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
        'panel' => [
            'type' => 'primary',
            'heading' => 'Anggaran dan Realisasi Per Jenis',
            'before' => $anggaranPenyerapan ? '
                <div class="col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>Pendapatan</h3>

                                <p>Rp' . number_format($anggaranPenyerapan[0]->anggaran, 2, ',', '.') . '</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-download"></i>
                            </div>
                        </div>
                </div>
                <div class="col-md-2">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>Belanja</h3>

                                <p>Rp' . number_format($anggaranPenyerapan[1]->anggaran, 2, ',', '.') . '</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                </div>
                <div class="col-md-3">
                    <!-- small box -->
                    <div class="small-box bg-info">
                            <div class="inner">
                                <h3>Pembiayaan Netto</h3>

                                <p>Rp' . number_format($anggaranPenyerapan[2]->anggaran, 2, ',', '.') . '</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-refresh"></i>
                            </div>
                        </div>
                </div>
            ' : '',
        ],
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '0'],
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
                    // '{toggleData}' .
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
            [
                'attribute' => 'tanggal_pelaporan',
                'format' => 'date',
                'group' => true,
            ],
            [
                'attribute' => 'refRek1.nm_rek_1',
                'group' => true,
                // 'groupedRow' => true
            ],
            [
                'attribute' => 'refRek2.nm_rek_2',
                'group' => true,
                // 'groupedRow' => true
            ],
            'refRek3.nm_rek_3',
            'anggaran:decimal',
            'realisasi:decimal',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}', // {delete}
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
        'dataProvider' => $dataProviderUrusan,
        // 'export' => true,
        'responsive' => true,
        'hover' => true,
        // 'resizableColumns' => true,
        'panel' => ['type' => 'primary', 'heading' => 'Anggaran dan Realisasi Per Urusan'],
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '0'],
        'responsiveWrap' => false,
        'toolbar' => [
            [
                'content' =>
                Html::a(
                    '<i class="glyphicon glyphicon-plus"></i> Input Data Urusan',
                    ['/penyerapan/urusan/create'],
                    [
                        'class' => 'btn btn-success',
                        'data-toggle' => "modal",
                        'data-target' => "#myModal",
                        'data-title' => "Input Data Urusan",
                    ]
                ) .
                    // '{toggleData}' .
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
            [
                'attribute' => 'refUrusan.nm_urusan',
                'group' => true,
                // 'groupedRow' => true
            ],
            'refBidang.nm_bidang',
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
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '0'],
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
                    // '{toggleData}' .
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
            [
                'attribute' => 'tanggal_pelaporan',
                'format' => 'date',
                'group' => true,
            ],
            [
                'attribute' => 'refRek1.nm_rek_1',
                'group' => true,
                // 'groupedRow' => true
            ],
            [
                'attribute' => 'refRek2.nm_rek_2',
                'group' => true,
                // 'groupedRow' => true
            ],
            'refRek3.nm_rek_3',
            [
                'label' => 'TW I',
                'hAlign' => 'right',
                'value' => function ($model) {
                    $percetage = (floatval($model->realisasi_tw1) / floatval($model->anggaran_tw1)) * 100;
                    return number_format($model->realisasi_tw1) . "/" . number_format($model->anggaran_tw1) . " ($percetage %)";
                }
            ],
            [
                'label' => 'TW II',
                'hAlign' => 'right',
                'value' => function ($model) {
                    $percetage = (floatval($model->realisasi_tw2) / floatval($model->anggaran_tw2)) * 100;
                    return number_format($model->realisasi_tw2) . "/" . number_format($model->anggaran_tw2) . " ($percetage %)";
                }
            ],
            [
                'label' => 'TW III',
                'hAlign' => 'right',
                'value' => function ($model) {
                    $percetage = (floatval($model->realisasi_tw3) / floatval($model->anggaran_tw3)) * 100;
                    return number_format($model->realisasi_tw3) . "/" . number_format($model->anggaran_tw3) . " ($percetage %)";
                }
            ],
            [
                'label' => 'TW IV',
                'hAlign' => 'right',
                'value' => function ($model) {
                    $percetage = (floatval($model->realisasi_tw4) / floatval($model->anggaran_tw4)) * 100;
                    return number_format($model->realisasi_tw4) . "/" . number_format($model->anggaran_tw4) . " ($percetage %)";
                }
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}', // {delete}
                'controller' => 'triwulan',
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