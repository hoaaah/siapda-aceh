<?php

use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;


$this->title = $pemda->name;
$this->params['breadcrumbs'][] = 'Data Entry';
$this->params['breadcrumbs'][] = ['label' => 'Data Capture', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<div class="ref-pemda-view">
    <div class="row">
        <div class="col-md-6">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-aqua-active">
                    <div class="widget-user-image">
                        <img class="img-circle" src="<?= $pemda['saved_image'] ? $pemda->getImageUrl() : Url::to('@web/uploads/no_logo.gif', false) ?>" alt="User Avatar">
                    </div>
                    <!-- /.widget-user-image -->
                    <div class="pull-right">
                        <!-- bg-aqua-active -->
                        <!-- <span class="info-box-icon bg-default"><i><?= $skorPemda ?></i></span> -->
                        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['pemda', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Ubah",
                            'class'=>'btn btn-xs btn-default'
                        ]) ?>
                    </div>
                    <h3 class="widget-user-username"><?= $pemda->name ?></h3>
                    <h5 class="widget-user-desc"><?= $pemda['ibukota'] ?></h5>
                </div>
                <div class="box-footer no-padding">
                    <div class="row">
                        <div class="col-sm-3 border-right">
                            <div class="description-block">
                                <h5 class="description-header <?= $opini['opini_id'] === "B4" ? "text-success" : "text-red" ?>"><?= $opini ? $opini['opini']['name'] : "--" ?></h5>
                                <span class="description-text">OPINI</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 border-right">
                            <div class="description-block">
                                <h5 class="description-header"><?= $evaluasi['kat_lppd'] ? $evaluasi['lppd']['name'] : "--" ?></h5>
                                <span class="description-text">LPPD</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3">
                            <div class="description-block">
                                <h5 class="description-header"><?= $evaluasi['kat_sakip'] ? $evaluasi['sakip']['name'] : "--" ?></h5>
                                <span class="description-text">LAKIP</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3">
                            <div class="description-block">
                                <h5 class="description-header"><?= $evaluasi['spip'] ? $evaluasi['spip'] : "--" ?></h5>
                                <span class="description-text">SPIP</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <ul class="nav nav-stacked">
                        <!-- <li><a href="#">Opini <span class="pull-right badge bg-blue">31</span></a></li>
                        <li><a href="#">Lakip <span class="pull-right badge bg-aqua">5</span></a></li>
                        <li><a href="#">LPPD <span class="pull-right badge bg-green">12</span></a></li>
                        <li><a href="#">SPIP <span class="pull-right badge bg-red">842</span></a></li> -->
                        <?= DetailView::widget([
                            'model' => $pemda,
                            'attributes' => [
                                'id',
                                // 'province_id',
                                // 'name',
                                // 'perwakilan_id',
                                [
                                    'label' => 'APBD',
                                    'value' => $apbd ? $apbd->no_apbd." ( $apbd->status )" : "Data APBD Belum Diisi",
                                ],
                                [
                                    'label' => 'Topografi',
                                    'format' => 'raw',
                                    'value' => "Jumlah Penduduk: ".number_format($profilPemda['jumlah_penduduk'])."ribu, Luas Wilayah: ".number_format($profilPemda['luas_wilayah'])."km2",
                                ],
                                [
                                    'label' => 'Rekomendasi',
                                    'format' => 'raw',
                                    // class="pull-right badge bg-red"
                                    'value' => $rekomendasi. " ( $skorPemda )"
                                ]
                            ],
                        ]) ?>                    
                    </ul>
                </div>
            </div>
            <!-- /.widget-user -->
        </div>

        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?= $kada['saved_image'] ? $kada->getImageUrl() : \yii\helpers\Url::to('@web/uploads/kepala_daerah.png', false) ?>" alt="User profile picture">

                    <h3 class="profile-username text-center"><?= $kada ? $kada->nama : "---" ?></h3>

                    <p class="text-muted text-center"><?= $kada ? $kada['jabatan'] : "---" ?></p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Usia</b> <a class="pull-right"><?= $kada['tanggal_lahir'] ? (date('Y') - date('Y', strtotime($kada['tanggal_lahir']))) : "---" ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Asal Partai</b> <a class="pull-right"><?= $kada ? $kada['partai'] : "---" ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Masa Jabatan</b> <a class="pull-right"><?= $kada ? $kada['masa_jabatan'] : "---" ?></a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?= $waKada['saved_image'] ? $waKada->getImageUrl() : \yii\helpers\Url::to('@web/uploads/kepala_daerah.png', false) ?>" alt="User profile picture">

                    <h3 class="profile-username text-center"><?= $waKada ? $waKada->nama : "---" ?></h3>

                    <p class="text-muted text-center"><?= $waKada ? $waKada['jabatan'] : "---" ?></p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Usia</b> <a class="pull-right"><?= $waKada['tanggal_lahir'] ? (date('Y') - date('Y', strtotime($waKada['tanggal_lahir']))) : "---" ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Asal Partai</b> <a class="pull-right"><?= $waKada ? $waKada['partai'] : "---" ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Masa Jabatan</b> <a class="pull-right"><?= $waKada ? $waKada['masa_jabatan'] : "---" ?></a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

    <?php if($mou): 
    if($mou->tanggal_berlaku >= date('Y-m-d')){
        $calloutClass = "callout-info"; 
        $today = new DateTime();
        $tanggalBerlaku  = new DateTime($mou->tanggal_berlaku);
        $dDiff = $today->diff($tanggalBerlaku);
        // var_dump($dDiff->format('%R')); // use for point out relation: smaller/greater
        // var_dump($dDiff->days);
        if($dDiff->days <= 30) $calloutClass = "callout-warning";

    }else{
        $calloutClass = "callout-danger";
    }
        ?>
    <div class="row" > <!-- MOU -->
        <div class="pad margin no-print">
            <div class="callout <?= $calloutClass ?>" style="margin-bottom: 0!important;">
                <h4><i class="fa fa-info"></i> MoU No: <?= "\"$mou->no_mou\""." / "."\"$mou->no_mou_pemda\""." tentang ".$mou->judul ?> (<?= date('d-m-Y', strtotime($mou->tanggal_mou)) ." s/d ". date('d-m-Y', strtotime($mou->tanggal_berlaku)) ?>)
                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['mou', 'id' => $pemda->id],
                [
                    'title'=> "Input MoU Baru",
                    'data-toggle'=>"modal",
                    'data-target'=>"#myModal",
                    'data-title'=>"Input MoU Baru",
                    // 'class'=>'btn btn-xs btn-warning'
                ]) ?>
                </h4>
                <?= $mou->ruang_lingkup ?>
            </div>
        </div>
    </div>
    <?php else:
        echo Html::a('<i class="glyphicon glyphicon-pencil"></i> Input MoU Baru', ['mou', 'id' => $pemda->id],
        [
            'title'=> "Input MoU Baru",
            'data-toggle'=>"modal",
            'data-target'=>"#myModal",
            'data-title'=>"Input MoU Baru",
            'class'=>'btn btn-lg btn-info'
        ]);
        endif;
    ?>

    <div class="row"> <!--Bagian Profil dan Kada -->
        <div class="col-md-6">
            <?= GridView::widget([
                'id' => 'lapbds',    
                'dataProvider' => $dataProviderProfil,
                'export' => false, 
                'responsive'=>true,
                'hover'=>true,     
                'resizableColumns'=>true,
                'panel'=>['type'=>'primary', 'heading'=>'<i class="glyphicon glyphicon-list"></i> Profil Pemda',],
                'responsiveWrap' => false,        
                'toolbar' => [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/profil/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],       
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'profil-pjax', 'timeout' => 5000],
                ],        
                // 'filterModel' => $searchModel,
                'columns' => require(__DIR__.'/_columnsProfil.php'),
            ]); ?>        
        </div>
        <div class="col-md-6">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProviderKada,
                // 'filterModel' => $searchModelOpini,
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'kada-pjax', 'timeout' => 5000],
                ], 
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/kada/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,   
                'responsiveWrap' => false,       
                'panel' => [
                    'type' => 'primary', 
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Kepala dan Wakil Kepala Daerah ',
                ],
                'columns' => require(__DIR__.'/_columnsKada.php'),
            ])?>
        </div>        
    </div>

    <div class="row"> <!--Bagian Opini dan APBD -->
        <div class="col-md-6">
            <?= GridView::widget([
                'id' => 'lapbds',    
                'dataProvider' => $dataProviderApbd,
                'export' => false, 
                'responsive'=>true,
                'hover'=>true,     
                'resizableColumns'=>true,
                'panel'=>['type'=>'primary', 'heading'=>'<i class="glyphicon glyphicon-list"></i> APBD Tahun '.(substr($tahunBulan, 0, 4)),],
                'responsiveWrap' => false,        
                'toolbar' => [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/apbd/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],       
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'lapbds-pjax', 'timeout' => 5000],
                ],        
                // 'filterModel' => $searchModel,
                'columns' => require(__DIR__.'/_columnsApbd.php'),
            ]); ?>        
        </div>
        <div class="col-md-6">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProviderOpini,
                // 'filterModel' => $searchModelOpini,
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'opini-pjax', 'timeout' => 5000],
                ], 
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/opini/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,   
                'responsiveWrap' => false,       
                'panel' => [
                    'type' => 'primary', 
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Opini LKPD Tahun '.(substr($tahunBulan, 0, 4)-1),
                ],
                'columns' => require(__DIR__.'/_columnsOpini.php'),
            ])?>
        </div>        
    </div>

    <div class="row"> <!-- bagian Evaluasi dan kasus -->
        <div class="col-md-6">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProviderEvaluasi,
                // 'filterModel' => $searchModelOpini,
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'evaluasi-pjax', 'timeout' => 5000],
                ], 
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/evaluasi/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,   
                'responsiveWrap' => false,       
                'panel' => [
                    'type' => 'primary', 
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Hasil Evaluasi Tahun '.(substr($tahunBulan, 0, 4)-1),
                ],
                'columns' => require(__DIR__.'/_columnsEvaluasi.php'),
            ])?>
        </div>

        <div class="col-md-6">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProviderKasus,
                // 'filterModel' => $searchModelOpini,
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'kasus-pjax', 'timeout' => 5000],
                ], 
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/kasus/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,   
                'responsiveWrap' => false,       
                'panel' => [
                    'type' => 'primary', 
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Kasus Sejak Tahun '.(substr($tahunBulan, 0, 4)-3),
                ],
                'columns' => require(__DIR__.'/_columnsKasus.php'),
            ])?>
        </div>
    </div>

        <div class="row"> <!-- bagian Lappd dan  -->
        <div class="col-md-6">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProviderLappd,
                // 'filterModel' => $searchModelOpini,
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'lappds-pjax', 'timeout' => 5000],
                ], 
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/lappd/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,   
                'responsiveWrap' => false,       
                'panel' => [
                    'type' => 'primary', 
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Penyampaian Lakip dan LPPD '.(substr($tahunBulan, 0, 4)-1),
                ],
                'columns' => require(__DIR__.'/_columnsLappd.php'),
            ])?>
        </div>

        <div class="col-md-6">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProviderSimda,
                // 'filterModel' => $searchModelOpini,
                'pager' => [
                    'firstPageLabel' => 'Awal',
                    'lastPageLabel'  => 'Akhir'
                ],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => ['id' => 'simda-pjax', 'timeout' => 5000],
                ], 
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/dataentry/simda/create', 'id' => $pemda->id],
                        [
                            'data-toggle'=>"modal",
                            'data-target'=>"#myModal",
                            'data-title'=>"Tambah",
                            'class'=>'btn btn-default'
                        ]).
                        '{toggleData}'.
                        '{export}'
                    ],
                ],          
                'striped' => true,
                'condensed' => true,
                'responsive' => true,   
                'responsiveWrap' => false,       
                'panel' => [
                    'type' => 'primary', 
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Penggunaan Simda '.(substr($tahunBulan, 0, 4)),
                ],
                'columns' => require(__DIR__.'/_columnsSimda.php'),
            ])?>
        </div>
    </div>
</div>

<?php 
Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ], 

]);
Modal::end(); 
Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin

]);
Modal::end(); 

Modal::begin([
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
JS
);
?>
