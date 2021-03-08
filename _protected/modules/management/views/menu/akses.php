<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* (C) Copyright 2017 Heru Arief Wijaya (http://belajararief.com/) untuk DJPK Kemenkeu.*/

function akses($id, $menu)
{
	$akses = \app\models\RefUserMenu::find()->where(['kd_user' => $id, 'menu' => $menu])->one();
	if ($akses) return true;
}
?>
<table class="table table-hover">
	<tbody>
		<tr>
			<th>Main Menu</th>
			<th>Sub Menu</th>
			<th>Sub Sub Menu</th>
			<th>Akses</th>
		</tr>
		<!--Menu 1 -->
		<tr>
			<td rowspan="4">Pengaturan</td>
			<td>Pengaturan Global</td>
			<td>-</td>
			<td>
				<?php
				$menu = 101;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>User Management</td>
			<td>-</td>
			<td>
				<?php
				$menu = 102;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Grup User dan Akses</td>
			<td>-</td>
			<td>
				<?php
				$menu = 103;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Pengumuman</td>
			<td>-</td>
			<td>
				<?php
				$menu = 106;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<!--end of menu-->
		<!--Menu 2 -->
		<tr>
			<td rowspan="5">Parameter</td>
			<td>Opini</td>
			<td>-</td>
			<td>
				<?php
				$menu = 201;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Perwakilan</td>
			<td>-</td>
			<td>
				<?php
				$menu = 202;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Pemda</td>
			<td>-</td>
			<td>
				<?php
				$menu = 203;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>---</td>
			<td>-</td>
			<td>
				<?php
				$menu = 204;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>---</td>
			<td>-</td>
			<td>
				<?php
				$menu = 205;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<!--end of menu-->
		<tr>
			<td rowspan="7">Data Entry</td>
			<td rowspan="2">Data Capture</td>
			<td>Preview</td>
			<td>
				<?php
				$menu = 301;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Input Data</td>
			<td>
				<?php
				$menu = 302;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Kegiatan</td>
			<td>-</td>
			<td>
				<?php
				$menu = 303;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Data Audit</td>
			<td>-</td>
			<td>
				<?php
				$menu = 304;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Dana Desa</td>
			<td rowspan="2">Penyaluran Dana Desa</td>
			<td>
				<?php
				$menu = 304;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Siskeudes</td>
			<td>
				<?php
				$menu = 302;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>SPIP</td>
			<td>-</td>
			<td>
				<?php
				$menu = 309;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<!--Menu 5 -->
		<tr>
			<td rowspan="2">Penyerapan</td>
			<td>APIP</td>
			<td>-</td>
			<td>
				<?php
				$menu = 310;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Perwakilan</td>
			<td>-</td>
			<td>
				<?php
				$menu = 311;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<!--end of menu-->
		<!--Menu 6 -->
		<tr>
			<td rowspan="2">Pelaporan</td>
			<td>Pelaporan Pusat</td>
			<td>-</td>
			<td>
				<?php
				$menu = 601;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<tr>
			<td>Pelaporan Perwakilan</td>
			<td>-</td>
			<td>
				<?php
				$menu = 602;
				if (akses($model->id, $menu) === true) {
					echo Html::a(
						'<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 0],
						[
							'id' => 'access-' . $menu,
						]
					);
				} else {
					echo Html::a(
						'<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>',
						['give', 'id' => $model->id, 'menu' => $menu, 'akses' => 1],
						[
							'id' => 'access-' . $menu,
						]
					);
				}

				?>
			</td>
		</tr>
		<!--end of menu-->
	</tbody>
</table>
<script>
	$('a[id^="access-"]').on("click", function(event) {
		event.preventDefault();
		var href = $(this).attr('href');
		var id = $(this).attr('id');
		var status = href.slice(-1);
		status = parseInt(status);
		status == 1 ? confirmMessage = 'Berikan akses?' : confirmMessage = 'Hapus Akses?'
		var confirmation = confirm(confirmMessage);
		object = $(this);
		if (confirmation == true) {
			$(this).html('<i class=\"fa fa-spinner fa-spin\"></i>');
			$.ajax({
				url: href,
				type: 'post',
				data: $(this).serialize(),
				beforeSend: function() {
					// create before send here
				},
				complete: function() {
					// create complete here
				},
				success: function(data) {
					if (data == 1) {
						if (status == 1) {
							$(object).html('<span class = "label label-success"><i class="fa  fa-sign-in bg-white"></i></span>');
							href = href.replace('akses=1', 'akses=0');
							$(object).attr('href', href);
						} else {
							$(object).html('<span class = "label label-danger"><i class="fa  fa-lock bg-white"></i></span>');
							href = href.replace('akses=0', 'akses=1');
							$(object).attr('href', href);
						}
					} else {
						$(object).html('<span class = "label label-danger">Gagal!</span>');
					}
				}
			});
		}
	});
</script>