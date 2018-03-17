<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'bulan',
    ],
    'tahun',
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'SPIP',
        'attribute'=>'nilai_spip',
        'value' => function($model){
            return $model['nilai_spip'] ? $model['nilai_spip']."( ".$model['spip']." )" : "";
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'LAKIP',
        'attribute'=>'nilai_sakip',
        'value' => function($model){
            return $model['nilai_sakip'] ? $model['nilai_sakip']."( ".$model['sakip']['name']." )" : "";
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'LPPD',
        'attribute'=>'nilai_lppd',
        'value' => function($model){
            return $model['nilai_lppd'] ? $model['nilai_lppd']."( ".$model['lppd']['name']." )" : "";
        }
    ],
    'ket',
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {delete}',
        'controller' => 'evaluasi',
        'noWrap' => true,
        'vAlign'=>'top',
        'visibleButtons' => [
            'update' => function($model) use($lapbul){
                if($lapbul) if($lapbul['locked'] == 1) return false;
                return true;
            }
        ],
        'buttons' => [
                'update' => function ($url, $model) {
                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,
                      [  
                         'title' => Yii::t('yii', 'ubah'),
                         'data-toggle'=>"modal",
                         'data-target'=>"#myModal",
                         'data-title'=> "Ubah",
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
];   