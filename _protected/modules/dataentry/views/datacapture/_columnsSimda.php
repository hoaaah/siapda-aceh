<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'bulan',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'use_keu',
        'format' => 'raw',
        'value' => function($model){
            if($model->use_keu === 1) return '<i class="glyphicon glyphicon-ok-circle text-info"></i>';
            return "";
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'use_bmd',
        'format' => 'raw',
        'value' => function($model){
            if($model->use_bmd === 1) return '<i class="glyphicon glyphicon-ok-circle text-info"></i>';
            return "";
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'use_pendapatan',
        'format' => 'raw',
        'value' => function($model){
            if($model->use_pendapatan === 1) return '<i class="glyphicon glyphicon-ok-circle text-info"></i>';
            return "";
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'use_perencanaan',
        'format' => 'raw',
        'value' => function($model){
            if($model->use_perencanaan === 1) return '<i class="glyphicon glyphicon-ok-circle text-info"></i>';
            return "";
        }
    ],
    'ket',
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {delete}',
        'controller' => 'simda',
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