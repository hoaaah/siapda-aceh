<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\TaProgram;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\controlhutang\models\TaRASKArsipSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row col-md-12">
                <div class="col-md-3">
                    <?php
                    $model->Kd_Laporan = isset(Yii::$app->request->queryParams['Laporan']['Kd_Laporan']) ? Yii::$app->request->queryParams['Laporan']['Kd_Laporan'] : '';
                    echo $form->field($model, 'Kd_Laporan')->widget(Select2::classname(), [
                        'data' => [
                            1 => 'Form I - ST Kegiatan',
                            // 2 => 'Form II - ST KegiatanAudit',
                            // 3 => 'Form III - ST Kegiatan Evaluasi',
                            // 4 => 'Form IV - ST Kegiatan Lain-Lain',
                            5 => 'Form A. Informasi MOU',
                            6 => 'Lampiran VI. Informasi SPIP',
                            // 7 => 'Lampiran VII. Informasi APIP',
                            8 => 'Lampiran VIII. Informasi APBD',
                            9 => 'Lampiran IX. Informasi LK',
                            10 => 'Lampiran X. Informasi Lakip',
                            11 => 'Lampiran XI. Informasi LPPD',
                            12 => 'Lampiran XII. Informasi SIMDA',
                            13 => 'Lampiran XIII. Informasi Dana Desa',
                            14 => 'Rekapitulasi Skoring Pemda'
                        ],
                        'options' => ['class' => 'form-control input-sm', 'placeholder' => 'Pilih Jenis Laporan ...', 'id' => 'field-kd_laporan'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label(false);
                    ?>
                </div>
                <div id="block-provinsi" style="display:<?= (isset(Yii::$app->request->queryParams['Laporan']['Kd_Laporan']) && Yii::$app->request->queryParams['Laporan']['Kd_Laporan'] == 3) || (isset(Yii::$app->request->queryParams['Laporan']['elimination_level']) && Yii::$app->request->queryParams['Laporan']['elimination_level'] == 1) ? 'block' : 'none' ?>;" class="col-md-4">
                    <?php
                    if (isset(Yii::$app->request->queryParams['Laporan']['kd_provinsi'])) {
                        $model->kd_provinsi = Yii::$app->request->queryParams['Laporan']['kd_provinsi'];
                    }
                    $query =  Yii::$app->db->createCommand(<<<SQL
SELECT CONVERT(a.province_id, UNSIGNED integer) AS province_id,  CONCAT(a.province_id, '. ', b.name) as name
FROM ref_pemda a INNER JOIN
(
	SELECT a.id, RIGHT(a.id,2) AS province_flag, a.name, a.province_id FROM ref_pemda a
    WHERE a.province_id LIKE :province_id
	HAVING province_flag = '00'
)b ON a.id = b.id
WHERE a.province_id LIKE :province_id
GROUP BY a.province_id, b.name
ORDER BY province_id
SQL
)->bindValues([
                        ':province_id' => isset($pemda_id) ? Yii::$app->user->identity->refPemda->province_id : '%',
                    ])
                        ->queryAll();
                    $dropDownProvinsi = ArrayHelper::map($query, 'province_id', 'name');
                    echo $form->field($model, 'kd_provinsi')->widget(Select2::classname(), [
                        'data' => $dropDownProvinsi,
                        'options' => ['placeholder' => 'Pilih Provinsi'],
                        'pluginOptions' => [
                            // 'tags' => true,
                            // 'tokenSeparators' => [',', ' '],
                            // 'maximumInputLength' => 100
                        ],
                    ])->label(false);
                    ?>
                </div>

            </div>
            <div class="row col-md-12">
                <div class="col-md-2 pull-right">
                    <?= Html::submitButton('Pilih', ['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
        <!--box-body-->
    </div>
    <!--box-->
</div>
<!--col-->

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
    $("#field-kd_laporan").on("change", function(){
        // hide all first
        $("#block-wilayah").hide();
        $("#block-provinsi").hide();
        $("#block-pemda").hide();
        $("#block-elim").hide();

        var kdLaporan = $(this).val() - 0; // convert to number first, not work in certain browser
        
        // then show it
        switch(kdLaporan) {
            case 2:
                $("#block-wilayah").show();
                break;
            case 3:
                $("#block-provinsi").show();
                break;
            case 4:
                $("#block-pemda").show();
                break;
            case 5:
                $("#block-elim").show();
                break;
            default:
                // code block
        }
    })

    $("#field-block-elim").on("change", function(){
        // hide all first
        $("#block-wilayah").hide();
        $("#block-provinsi").hide();
        $("#block-pemda").hide();

        var kdElim = $(this).val() - 0; // convert to number first, not work in certain browser
        
        // then show it
        switch(kdElim) {
            case 2:
                $("#block-wilayah").show();
                break;
            case 1:
                $("#block-provinsi").show();
                break;
            default:
                // code block
        }
    })    
JS
);
?>