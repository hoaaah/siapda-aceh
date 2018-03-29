<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\controlhutang\models\RekananSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pelaporan Provinsi';
$this->params['breadcrumbs'][] = 'Pelaporan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<?php echo $this->render('_search', ['model' => $get, 'Tahun' => $Tahun]); ?>
</div>
<?php IF($Kd_Laporan <> NULL) : ?>

				<?php 
	                switch ($Kd_Laporan) {
                        case 6:
                            $heading = 'Lampiran VI. Informasi SPIP';
                            break;
                        case 7:
                            $heading = 'Lampiran VII. Informasi APIP';
                            break;
                        case 8:
                            $heading = 'Lampiran VII. Informasi APBD';
                            break;
                        case 9:
                            $heading = 'Lampiran VII. Informasi LKPD';
                            break;
                        case 10:
                            $heading = 'Lampiran VII. Informasi Lakip';
                            break;
                        case 11:
                            $heading = 'Lampiran VII. Informasi LPPD';
                            break;
                        case 12:
                            $heading = 'Lampiran XII. Informasi SIMDA';
                            break;
                        case 13:
                            $heading = 'Lampiran XII. Informasi Dana Desa';
                            break;
	                    default:
	                        # code...
	                        break;
	                }
                ?>
   
                <?php echo $this->render($render, [
                	'data' => $data, 
		            'data1' => $data1,
		            'data2' => $data2,
		            'data3' => $data3,
		            'data4' => $data4,
		            'data5' => $data5,
		            'data6' => $data6,
                	'heading' => $heading, 
                    'getparam' => $getparam,
                    'tahun' => $Tahun,
					'totalPemda' => $totalPemda]); ?>

<?php endif; ?>
