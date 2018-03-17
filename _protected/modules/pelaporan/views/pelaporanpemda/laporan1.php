<?php 

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;

echo GridView::widget([
	'dataProvider' => $data,
	'responsive'=>true,
	'hover'=>true,     
	'resizableColumns'=>false,
	'panel'=>['type'=>'primary', 'heading'=>$heading],
	'responsiveWrap' => false,        
	'toolbar' => [
            // '{toggleData}',
            '{export}',
            [
                'content' =>    Html::a('<i class="glyphicon glyphicon-print"></i> Cetak', ['cetak', 'Laporan' => [
                                    'Kd_Laporan' => $getparam['Laporan']['Kd_Laporan'], 
                                    // 'elimination_level' => $getparam['Laporan']['elimination_level'],
                                    // 'kd_wilayah' => $getparam['Laporan']['kd_wilayah'],
                                    // 'kd_provinsi' => $getparam['Laporan']['kd_provinsi'],
                                    'kd_pemda' => $getparam['Laporan']['kd_pemda'],
                                    'periode_id' => $getparam['Laporan']['periode_id']
                                ] ], [
                                    'class' => 'btn btn btn-default pull-right',
                                    'onClick' => "return !window.open(this.href, 'SPH', 'width=1024,height=600,scrollbars=1')"
                                        ]) 
            ],    
    ],
    'exportConfig' => [
        GridView::HTML => ['filename' => $heading,],
        GridView::CSV => ['filename' => $heading,],
        GridView::TEXT => ['filename' => $heading,],
        GridView::EXCEL => ['filename' => $heading,], 
        GridView::PDF => [
            'label' => 'PDF',
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'filename' => $heading,
            'alertMsg' => 'The PDF export file will be generated for download.',
            'options' => ['title' => 'Portable Document Format'],
            'mime' => 'application/pdf',
            'config' => [
                'mode' => 'c',
                'format' => 'A4-L',
                'destination' => 'D',
                'marginTop' => 20,
                'marginBottom' => 20,
                'cssInline' => '.kv-wrap{padding:20px;}' .
                    '.kv-align-center{text-align:center;}' .
                    '.kv-align-left{text-align:left;}' .
                    '.kv-align-right{text-align:right;}' .
                    '.kv-align-top{vertical-align:top!important;}' .
                    '.kv-align-bottom{vertical-align:bottom!important;}' .
                    '.kv-align-middle{vertical-align:middle!important;}' .
                    '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                    '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                    '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
                'methods' => [
                    'SetHeader' => $heading,
                    'SetFooter' => '<li role="presentation" class="dropdown-footer">Generated by econsole, '.date('Y-m-d H-i-s T').'</li>',
                ],
                'options' => [
                    'title' => $heading,
                    'subject' => 'PDF export generated by econsole',
                    'keywords' => 'grid, export, yii2-grid, pdf'
                ],
                'contentBefore'=>'',
                'contentAfter'=>''
            ]
        ],
        GridView::JSON => ['filename' => $heading,],
    ],
	'pager' => [
	    'firstPageLabel' => 'Awal',
	    'lastPageLabel'  => 'Akhir'
	],
	'pjax'=>true,
	'pjaxSettings'=>[
	    'options' => ['id' => 'laporan1-pjax', 'timeout' => 5000],
	],
	'showPageSummary'=>true,    
	'columns' => [
        [
            'label' => 'Akun',
            'value' =>function($model){
                $refAkun1 = \app\models\RefAkrual1::findOne(['kd_akrual_1' => $model['kd_rek_1']])->nm_akrual_1;
                return $model['kd_rek_1'].". $refAkun1";
            },
            'group'=>true,  // enable grouping,
            'groupedRow'=>true,                    // move grouped column to a single grouped row
            // 'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
            // 'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                $refAkun1 = \app\models\RefAkrual1::findOne(['kd_akrual_1' => $model['kd_rek_1']])->nm_akrual_1;
                return [
                    'mergeColumns'=>[[2,3]], // columns to merge in summary
                    'content'=>[             // content to show in each summary cell
                        1=>'Total ' . $refAkun1,
                        4=>GridView::F_SUM,
                        5=>GridView::F_SUM,
                    ],
                    'contentFormats'=>[      // content reformatting for each summary cell
                        4=>['format'=>'number', 'decimals'=>0, 'decPoint' => ',', 'thousandSep' => '.'],
                        5=>['format'=>'number', 'decimals'=>0, 'decPoint' => ',', 'thousandSep' => '.'],
                    ],
                    'contentOptions'=>[      // content html attributes for each summary cell
                        1=>['style'=>'font-variant:small-caps'],
                        4=>['style'=>'text-align:right', 'class' => 'summary-rek2'],
                        5=>['style'=>'text-align:right', 'class' => 'summary-rek2'],
                    ],
                    // html attributes for group summary row
                    'options'=>['class'=>'danger','style'=>'font-weight:bold;']
                ];
            }
        ],
        [
            'label' => 'Kelompok',
            'format' => 'raw',
            'value' =>function($model){
                $uraian = '<code>[--Rekening Tidak Terdaftar--]</code>';
                $refAkun2 = \app\models\RefAkrual2::findOne(['kd_akrual_1' => $model['kd_rek_1'], 'kd_akrual_2' => $model['kd_rek_2']]);
                if($refAkun2) $uraian = $refAkun2->nm_akrual_2;
                return $model['kd_rek_1'].$model['kd_rek_2'].". $uraian";
            },
            'group'=>true,  // enable grouping,
            // 'groupedRow'=>true,                    // move grouped column to a single grouped row
            // 'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
            // 'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                $uraian = '<code>[--Rekening Tidak Terdaftar--]</code>';
                $refAkun2 = \app\models\RefAkrual2::findOne(['kd_akrual_1' => $model['kd_rek_1'], 'kd_akrual_2' => $model['kd_rek_2']]);
                if($refAkun2) $uraian = $refAkun2->nm_akrual_2;
                return [
                    'mergeColumns'=>[[2,3]], // columns to merge in summary
                    'content'=>[             // content to show in each summary cell
                        2=>'Total ' . $uraian,
                        4=>GridView::F_SUM,
                        5=>GridView::F_SUM,
                    ],
                    'contentFormats'=>[      // content reformatting for each summary cell
                        4=>['format'=>'number', 'decimals'=>0, 'decPoint' => ',', 'thousandSep' => '.'],
                        5=>['format'=>'number', 'decimals'=>0, 'decPoint' => ',', 'thousandSep' => '.'],
                    ],
                    'contentOptions'=>[      // content html attributes for each summary cell
                        2=>['style'=>'font-variant:small-caps'],
                        4=>['style'=>'text-align:right', 'class' => 'summary-rek2'],
                        5=>['style'=>'text-align:right', 'class' => 'summary-rek2'],
                    ],
                    // html attributes for group summary row
                    'options'=>['class'=>'danger','style'=>'font-weight:bold;']
                ];
            }
        ],
        [
            'label' => 'Kode Akun',
            'width' => '10%',
            'hAlign' => 'center',
            'value' => function($model){
                return $model['kd_rek_1'].$model['kd_rek_2'].$model['kd_rek_3'];
            }
        ],
        [
            'label' => 'Uraian',
            'attribute' => 'nm_akrual_3',
            'format' => 'raw',
            'value' => function($model) use($getparam){
                if($model['nm_akrual_3'] == '[--Rekening Tidak Terdaftar--]'){
                    $return = '<code>'.$model['nm_akrual_3'].'</code>';
                }else{
                    $return = $model['nm_akrual_3'];
                }
                return Html::a($return, ['view', 
                    'id' => $model['kd_rek_1'].'.'.$model['kd_rek_2'].'.'.$model['kd_rek_3'], 
                    'kd_laporan' => $getparam['Laporan']['Kd_Laporan'],
                    'kd_pemda' => $getparam['Laporan']['kd_pemda'],
                    'periode_id' => $getparam['Laporan']['periode_id']
                ], [
                    'class' => 'akunDetail',
                    'data-toggle'=>"modal",
                    'data-target'=>"#myModal",
                    'data-title'=> $model['kd_rek_1'].$model['kd_rek_2'].$model['kd_rek_3']." $return",
                ]);
            }
        ],
        [
            'attribute' => 'realisasi_sebelum',
            'label' => 'Sebelum Eliminasi',
            'format' => ['decimal', 0],
            'hAlign' => 'right',
            // 'pageSummary' => true,
            'value' => function($model){
                return $model['realisasi_sebelum'] ? $model['realisasi_sebelum'] : 0;
            }
        ],
        [
            'attribute' => 'realisasi_sesudah',
            'label' => 'Sesudah Eliminasi',
            'format' => ['decimal', 0],
            'hAlign' => 'right',
            // 'pageSummary' => true,
            'value' => function($model){
                return $model['realisasi_sesudah'] ? $model['realisasi_sesudah'] : 0;
            }
        ],
	],
]); 
 ?>
 <?php
 Modal::begin([
    'id' => 'myModal',
    'header' => '<h4 class="modal-title">Lihat lebih...</h4>',
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ], 
    'size' => 'modal-lg'
]);
 
echo '...';
 
Modal::end();
$this->registerJs("
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
");
$this->registerCss(<<<CSS
    a.akunDetail{
        color:inherit;
    }
CSS
);
$this->registerJs(<<<JS
function number_format (number, decimals, decPoint, thousandsSep) {
    //  discuss at: http://locutus.io/php/number_format/
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    var n = !isFinite(+number) ? 0 : +number
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    var s = ''

    var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec)
        return '' + (Math.round(n * k) / k)
        .toFixed(prec)
    }

    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
    }

    return s.join(dec)
}

$('.summary-rek2').each(function (event) {
    $(this).html(number_format($(this).html(), 0, ',', '.'));
})
JS
);
?>