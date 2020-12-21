<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\dataentry\models\LkegiatansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk Indonesia.*/

$this->title = 'Surat Tugas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lkegiatans-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?= Html::a('Tambah Surat Tugas', ['create'], [
            'class' => 'btn btn-xs btn-success',
            'data-toggle' => "modal",
            'data-target' => "#myModal",
            'data-title' => "Tambah",
        ]) ?>
    </p>
    <?= GridView::widget([
        'id' => 'lkegiatans',
        'dataProvider' => $dataProvider,
        'export' => false,
        'responsive' => true,
        'hover' => true,
        'resizableColumns' => true,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
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
        'pjax' => true,
        'pjaxSettings' => [
            'options' => ['id' => 'lkegiatans-pjax', 'timeout' => 5000],
        ],
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'pemda.name',
                'label' => 'Pemda'
            ],
            [
                'attribute' => 'kegiatan.name',
                'label' => 'Kegiatan'
            ],
            'nama_kegiatan',
            'no_st',
            'tanggal_st',
            'no_laporan',
            'ket',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
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
JS
);
?>