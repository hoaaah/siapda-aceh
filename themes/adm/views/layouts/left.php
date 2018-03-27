<?php /* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk DJPK Kemenkeu.*/ ?>

<aside class="main-sidebar">

    <section class="sidebar">

        <?php
        function akses($menu){
            $akses = \app\models\RefUserMenu::find()->where(['kd_user' => Yii::$app->user->identity->kd_user, 'menu' => $menu])->one();
            IF($akses){
                return true;
            }else{
                return false;
            }
        }
        echo dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Dashboard', 'icon' => 'fa fa-dashboard', 'url' => ['/'],],
                    ['label' => 'Pengaturan', 'icon' => 'circle-o','url' => '#', 'visible' => 1,'items'  =>
                        [
                            // ['label' => 'Pengaturan Global', 'icon' => 'circle-o', 'url' => ['/management/setting'], 'visible' => akses(405)],
                            ['label' => 'User Management', 'icon' => 'circle-o', 'url' => ['/user/index'], 'visible' => akses(102)],
                            ['label' => 'Akses Grup', 'icon' => 'circle-o', 'url' => ['/management/menu'], 'visible' => akses(401)],
                            ['label' => 'Blog/Pengumuman', 'icon' => 'circle-o', 'url' => ['/management/pengumuman'], 'visible' => akses(106)],     
                        ],
                    ],                    
                    ['label' => 'Parameter', 'icon' => 'circle-o','url' => '#', 'visible' => 1,'items'  =>
                        [
                            // ['label' => 'Periode', 'icon' => 'circle-o', 'url' => ['/parameter/periode'], 'visible' => akses(201)],
                            // ['label' => 'Bagan Akun Standar', 'icon' => 'circle-o', 'url' => ['/parameter/bas'], 'visible' => akses(202)],
                            ['label' => 'Pemda', 'icon' => 'circle-o', 'url' => ['/parameter/pemda'], 'visible' => akses(203)],
                            ['label' => 'Opini', 'icon' => 'circle-o', 'url' => ['/parameter/opini'], /* 'visible' => akses(205) */ ],
                            ['label' => 'Perwakilan', 'icon' => 'circle-o', 'url' => ['/parameter/perwakilan'], /* 'visible' => akses(205) */ ],
                            // ['label' => 'Jenis Transfer', 'icon' => 'circle-o', 'url' => ['/parameter/transfer'], 'visible' => akses(204)],
                            // ['label' => 'Wilayah', 'icon' => 'circle-o', 'url' => ['/parameter/wilayah'], 'visible' => akses(205)],
                        ],
                    ],
                    ['label' => 'Data Entry', 'icon' => 'fa fa-edit', 'url' => '#', 'visible' => !Yii::$app->user->isGuest, 'items' => 
                        [
                            ['label' => 'Data Capture', 'icon' => 'circle-o', 'url' => ['/dataentry/datacapture'], /* 'visible' => akses(205) */ ],
                            ['label' => 'Surat Tugas', 'icon' => 'circle-o', 'url' => ['/parameter/datacapture'], /* 'visible' => akses(205) */ ],
                            ['label' => 'Audit', 'icon' => 'circle-o', 'url' => ['/parameter/datacapture'], /* 'visible' => akses(205) */ ],
                            ['label' => 'SPIP', 'icon' => 'circle-o','url' => '#', 'visible' => 309,'items'  =>
                                [
                                    ['label' => 'Perkada/Satgas', 'icon' => 'circle-o', 'url' => ['/dataentry/spip'] , 'visible' => akses(309)],
                                    ['label' => 'Target', 'icon' => 'circle-o', 'url' => ['/dataentry/spiptarget'], 'visible' => akses(309)],
                                    ['label' => 'Laporan Evaluasi', 'icon' => 'circle-o', 'url' => ['/dataentry/spipeval'], 'visible' => akses(309)],
                                ],
                            ], 
                            ['label' => 'Dana Desa', 'icon' => 'circle-o','url' => '#', 'visible' => 1,'items'  =>
                                [
                                    ['label' => 'Alokasi/Realisasi Dana Desa', 'icon' => 'circle-o', 'url' => ['/dataentry/danadesa'] /*, 'visible' => akses(305)*/],
                                    ['label' => 'Siskeudes', 'icon' => 'circle-o', 'url' => ['/management/menu'], 'visible' => akses(401)],
                                ],
                            ],                             
                            // ['label' => 'Data Capture', 'icon' => 'circle-o', 'items' => [
                            //     ['label' => 'Perwakilan', 'icon' => 'circle-o', 'url' => ['/parameter/perwakilan'], /* 'visible' => akses(205) */ ],
                            // ]],
                        ],
                    ],
                    ['label' => 'Konsolidasi', 'icon' => 'fa fa-edit', 'url' => '#', 'visible' => !Yii::$app->user->isGuest, 'items' => 
                        [
                            ['label' => 'Akun Eliminasi', 'icon' => 'circle-o', 'url' => ['/konsolidasi/eliminasi'], 'visible' => akses(501)],
                            // ['label' => 'Data Monitoring', 'icon' => 'circle-o', 'url' => ['/konsolidasi/monitoring'], 'visible' => akses(502)],
                        ],
                    ],
                    ['label' => 'Pelaporan', 'icon' => 'fa fa-edit', 'url' => '#', 'visible' => !Yii::$app->user->isGuest, 'items' => 
                        [
                            ['label' => 'Pelaporan', 'icon' => 'circle-o', 'url' => ['/pelaporan/pelaporanrekap'], 'visible' => akses(601)],
                            ['label' => 'Pelaporan Provinsi', 'icon' => 'circle-o', 'url' => ['/pelaporan/pelaporanprovinsi'], 'visible' => akses(602)],
                            ['label' => 'Pelaporan Pemda', 'icon' => 'circle-o', 'url' => ['/pelaporan/pelaporanpemda'], 'visible' => akses(603)],                           
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
