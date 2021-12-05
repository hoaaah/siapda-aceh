<?php

use yii\helpers\Html;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk DJPK Kemenkeu.*/

if (Yii::$app->session->get('tahun')) {
    $Tahun = Yii::$app->session->get('tahun');
} else {
    $Tahun = DATE('Y');
}
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <?php if (Yii::$app->user->identity->perwakilan_id != NULL && Yii::$app->user->identity->perwakilan_id == NULL) : ?>
                <span class"pull-left"><B><?= strtoupper(Yii::$app->user->identity->refPerwakilan->name) ?></B></span>
            <?php endif; ?>
            <?php if (isset(Yii::$app->user->identity->pemda_id)) : ?>
                <span class"pull-left"><B><?= strtoupper(Yii::$app->user->identity->refPemda->name) ?></B></span>
            <?php endif; ?>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <?php
                if (!Yii::$app->user->isGuest) :
                ?>
                    <li class="dropdown tahun notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag"></i> <span class="hidden-xs"> Tahun: </span><?= Yii::$app->session->get('tahun') ? Yii::$app->session->get('tahun') : 'Pilih!' ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <ul class="menu">
                                    <?php
                                    $existedYears = Yii::$app->db->createCommand(" SELECT a.tahun FROM (SELECT LEFT(bulan, 4) AS tahun FROM lkegiatans) a GROUP BY a.tahun ORDER BY a.tahun DESC")->queryAll();
                                    $previousYear = date('Y') - 1;
                                    $currentYear = date('Y');
                                    $nextYear = date('Y') + 1;
                                    $tahun = \yii\helpers\ArrayHelper::getColumn($existedYears, 'tahun');
                                    // $tahun = [];
                                    if (!in_array($previousYear, $tahun)) $tahun = array_merge($tahun, [$previousYear]);
                                    if (!in_array($currentYear, $tahun)) $tahun = array_merge($tahun, [$currentYear]);
                                    if (!in_array($nextYear, $tahun)) $tahun = array_merge($tahun, [$nextYear]);
                                    rsort($tahun);
                                    foreach ($tahun as $tahun) :
                                    ?>
                                        <li><?= Html::a($tahun, ['/site/tahun', 'id' => $tahun]) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown bulan notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag"></i> <span class="hidden-xs"> Bulan: </span><?= Yii::$app->session->get('bulan') ? Yii::$app->session->get('bulan') : 'Pilih!' ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <ul class="menu">
                                    <?php
                                    $bulan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                                    foreach ($bulan as $bulan) :
                                    ?>
                                        <li><?= Html::a($bulan, ['/site/bulan', 'id' => $bulan]) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?= Html::img('@web/images/logo.png', ['alt' => 'User Image', 'class' => 'user-image']); ?>
                            <?php /*<img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/> */ ?>
                            <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <?= Html::img('@web/images/logo.png', ['alt' => 'User Image', 'class' => 'img-circle']); ?>
                                <?php /*<img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/> */ ?>
                                <p>
                                    <?= Yii::$app->user->identity->username ?>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?= Html::a(
                                        'Profile',
                                        ['/profile'],
                                        ['class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                                <div class="pull-right">
                                    <?= Html::a(
                                        'Sign out',
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php
                else :
                ?>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">Login User/Registrasi</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><?= Html::a('Login', ['/site/login']) ?></li>
                            <li><?= Html::a('Registrasi', ['/site/signup']) ?></li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </nav>
</header>