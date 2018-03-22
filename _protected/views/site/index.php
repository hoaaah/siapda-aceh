<?php
use dosamigos\chartjs\ChartJs;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */

function angka($n) {
    // first strip any formatting;
    $n = (0+str_replace(",","",$n));
    
    // is this a number?
    if(!is_numeric($n)) return false;
    
    // now filter it;
    if($n>1000000000000) return round(($n/1000000000000),1);
    else if($n>1000000000) return round(($n/1000000000),1);
    else if($n>1000000) return round(($n/1000000),1);
    else if($n>1000) return round(($n/1000),1);
    
    return number_format($n);
}
$this->title = Yii::t('app', Yii::$app->name);
?>
<div class="site-index">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?= count($skorReassesment) ?><sup style="font-size: 20px"> Pemda</sup></h3>

                    <p>ReAssesment</p>
                </div>
                <div class="icon">
                    <i class="fa fa-paper-plane-o"></i>
                </div>
                <?= Html::a('More info <i class="fa fa-arrow-circle-right"></i>', ['asses'], 
                    [
                        'data-toggle'=>"modal",
                        'data-target'=>"#myModal",
                        'data-title'=>"Daftar Pemda Untuk di Assesment",
                        'class'=>'small-box-footer'
                    ]                    
                )?>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= count($skorBimtek) ?><sup style="font-size: 20px"> Pemda</sup></h3>

                    <p>Bimtek SPIP</p>
                </div>
                <div class="icon">
                    <i class="fa fa-graduation-cap"></i>
                </div>
                <?= Html::a('More info <i class="fa fa-arrow-circle-right"></i>', ['bimtek'], 
                    [
                        'data-toggle'=>"modal",
                        'data-target'=>"#myModal",
                        'data-title'=>"Daftar Pemda Untuk di Bimtek SPIP",
                        'class'=>'small-box-footer'
                    ]                    
                )?>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>44</h3>

                    <p>MoU Expiring</p>
                </div>
                <div class="icon">
                    <i class="fa fa-retweet"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>65</h3>

                    <p>Latest Activity</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row well">
        <div class="col-lg-3 col-xs-6">
            <?php
                echo ChartJs::widget([
                    'type' => 'bar',
                    'options' => [
                        'height' => 400,
                        'width' => 400
                    ],
                    'data' => [
                        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        'datasets' => [
                            [
                                'label' => 'Realisasi Kegiatan APD',
                                'backgroundColor' => "rgba(255,99,132,0.2)",
                                'borderColor' => "rgba(255,99,132,1)",
                                'pointBackgroundColor' => "rgba(255,99,132,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                'data' => [10, 15, 12, 10, 15, 12]
                            ]
                        ]
                    ]
                ]);            
            ?>
        </div>
        <div class="col-lg-3 col-xs-6">
            <?php
                echo ChartJs::widget([
                    'type' => 'bar',
                    'options' => [
                        'height' => 400,
                        'width' => 400
                    ],
                    'data' => [
                        'labels' => [$tahun -4, $tahun -3, $tahun -2, $tahun -1, $tahun],
                        'datasets' => [
                            [
                                'label' => 'Opini WTP',
                                'backgroundColor' => "rgba(255,99,132,0.2)",
                                'borderColor' => "rgba(255,99,132,1)",
                                'pointBackgroundColor' => "rgba(255,99,132,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                'data' => [$opiniGrafik['tahun4'], $opiniGrafik['tahun3'], $opiniGrafik['tahun2'], $opiniGrafik['tahun1'], $opiniGrafik['tahun0']]
                            ]
                        ]
                    ]
                ]);            
            ?>
        </div>
        <div class="col-lg-6 col-xs-12">
            <?php
            echo ChartJs::widget([
                'type' => 'horizontalBar',
                'options' => [
                    'height' => 100,
                    // 'width' => 400
                ],
                'data' => [
                    'labels' => ['Pemda', 'Keu', 'BMD', 'Pendapatan', 'Perencanaan'],
                    'datasets' => [
                        // [
                        //     'label' => 'Anggaran',
                        //     'fillColor' => "rgba(220,220,220,0.5)",
                        //     'strokeColor' => "rgba(220,220,220,1)",
                        //     'pointColor' => "rgba(220,220,220,1)",
                        //     'pointStrokeColor' => "#fff",
                        //     'backgroundColor' => "#f4ff62",
                        //     'data' => [$simdaGrafik['pemda'], $simdaGrafik['keu'], $simdaGrafik['bmd'], $simdaGrafik['pendapatan'], $simdaGrafik['perencanaan']]
                        // ],
                        [
                            'label' => 'Pengguna SIMDA',
                            'fillColor' => "rgba(151,187,205,0.5)",
                            'strokeColor' => "rgba(151,187,205,1)",
                            'pointColor' => "rgba(151,187,205,1)",
                            'pointStrokeColor' => "#fff",
                            'backgroundColor' => "#61ff74",
                            'data' => [$simdaGrafik['pemda'], $simdaGrafik['keu'], $simdaGrafik['bmd'], $simdaGrafik['pendapatan'], $simdaGrafik['perencanaan']]
                        ]
                    ]
                ]
            ]);
            ?>
        </div>  
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="well">
                <?php
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => function ($model, $key, $index, $widget) {
                        // IF(strlen($model->content) <= 1000){
                            return '
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">'.Html::a($model->title, ['view', 'id' => $model->id], ['class' => '']).'</h3>
                                </div>
                                <div class="panel-body">
                                '.$model->content.'
                                </div>
                                
                            </div>
                            ';                  
                        // }ELSE{
                        //   return '
                        //     <div class="panel panel-success">
                        //         <div class="panel-heading">
                        //             <h3 class="panel-title">'.Html::a($model->title, ['view', 'id' => $model->id], ['class' => '']).'</h3>
                        //         </div>
                        //         <div class="panel-body">
                        //         '.substr($model->content, 0, 1000).Html::a('read more...', ['view', 'id' => $model->id], ['class' => 'btn btn-xs btn-default']).'
                        //         </div>
                            
                        //     </div>
                        //   ';                  
                        // }
                    },
                ]);
                ?> 
            </div>
        </div>
        <div class="col-md-4 well">
            <?php
            echo ChartJs::widget([
                'type' => 'horizontalBar',
                'options' => [
                    'height' => 100,
                    // 'width' => 400
                ],
                'data' => [
                    'labels' => ['Pemda', 'Desa', 'Kompilasi'],
                    'datasets' => [
                        // [
                        //     'label' => 'Anggaran',
                        //     'fillColor' => "rgba(220,220,220,0.5)",
                        //     'strokeColor' => "rgba(220,220,220,1)",
                        //     'pointColor' => "rgba(220,220,220,1)",
                        //     'pointStrokeColor' => "#fff",
                        //     'backgroundColor' => "#f4ff62",
                        //     'data' => [$simdaGrafik['pemda'], $simdaGrafik['keu'], $simdaGrafik['bmd'], $simdaGrafik['pendapatan'], $simdaGrafik['perencanaan']]
                        // ],
                        [
                            'label' => 'Pengguna Siskeudes',
                            'fillColor' => "rgba(151,187,205,0.5)",
                            'strokeColor' => "rgba(151,187,205,1)",
                            'pointColor' => "rgba(151,187,205,1)",
                            'pointStrokeColor' => "#fff",
                            'backgroundColor' => "#61ff74",
                            'data' => [$siskeudesQuery['pemda'], $siskeudesQuery['siskeudes'], $siskeudesQuery['kompilasi']]
                        ]
                    ]
                ]
            ]);
            ?>
        </div>
    </div>
<?php /*
    <div class="body-content">
    <div class ="row">
    <div class ="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Posisi Kas Harian 10 Hari Terakhir</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <?php
                // $connection = \Yii::$app->db;           
                // $skpd = $connection->createCommand('SELECT Tanggal, SUM(Jumlah) AS Saldo
                // FROM Ta_Kas_Harian 
                // GROUP BY Tanggal
                // ORDER BY Tanggal ASC
                // LIMIT 0, 10
                // ');
                // $query = $skpd->queryAll();
                // foreach($query AS $query){
                //     $labels[] = DATE('d-m', strtotime($query['Tanggal']));
                //     $data[] = $query['Saldo'];
                // }

                // echo ChartJs::widget([
                //     'type' => 'line',
                //     'options' => [
                //         'height' => 400,
                //         'width' => 400
                //     ],
                //     'data' => [
                //         'labels' => $labels,
                //         'datasets' => [
                //             [
                //                 'label' => 'Realisasi',
                //                 'fillColor' => "rgba(151,187,205,0.5)",
                //                 'strokeColor' => "rgba(151,187,205,1)",
                //                 'pointColor' => "rgba(151,187,205,1)",
                //                 'pointStrokeColor' => "#fff",
                //                 'data' => $data
                //             ]
                //         ]
                //     ]
                // ]);
                ?>
              </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class ="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Pelunasan Utang (Dalam Jutaan Rupiah)</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <?php 
                // echo ChartJs::widget([
                //     'type' => 'bar',
                //     'options' => [
                //         'height' => 400,
                //         'width' => 400
                //     ],
                //     'data' => [
                //         'labels' => ["January", "February", "March", "April", "May", "June", "July"],
                //         'datasets' => [
                //             [
                //                 'label' => 'Anggaran',
                //                 'fillColor' => "rgba(220,220,220,0.5)",
                //                 'strokeColor' => "rgba(220,220,220,1)",
                //                 'pointColor' => "rgba(220,220,220,1)",
                //                 'pointStrokeColor' => "#fff",
                //                 'data' => [65, 59, 90, 81, 56, 55, 40]
                //             ],
                //             [
                //                 'label' => 'Realisasi',
                //                 'fillColor' => "rgba(151,187,205,0.5)",
                //                 'strokeColor' => "rgba(151,187,205,1)",
                //                 'pointColor' => "rgba(151,187,205,1)",
                //                 'pointStrokeColor' => "#fff",
                //                 'data' => [28, 48, 40, 19, 96, 27, 100]
                //             ]
                //         ]
                //     ]
                // ]);
                ?>
              </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>    
    <!-- /.box -->

    </div>
    </div>
*/ ?>
</div>

<?php 
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