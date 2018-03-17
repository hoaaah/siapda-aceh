<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    'bulan',
    'nama_kasus',
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {delete}',
        'controller' => 'kasus',
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