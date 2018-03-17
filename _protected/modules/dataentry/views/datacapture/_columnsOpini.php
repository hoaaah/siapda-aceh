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
        'attribute'=>'tanggal',
        'format' => 'date'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'pihak_bantu_susun',
        'value' => function($model){
            return $model['bantuSusun']['name'];
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'pihak_bantu_reviu',
        'value' => function($model){
            return $model['bantuReviu']['name'];
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'opini_id',
        'value' => function($model){
            return $model->opini->name;
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ket',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {delete}',
        'controller' => 'opini',
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