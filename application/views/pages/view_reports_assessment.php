<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria/dashboard_criteria");
$prev = base_url("criteria/dashboard_criteria");
$ajax_form_url = base_url("criteria/ajax_get_data_form/");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("criteria/view_criteria_data/{$data->id}");
}
$sum_value = array();
$sum_all = array();
function loopTreeFormListSub($tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,$html,$symbol){
	global $sum_value,$sum_all;
	$data = $tree_db->getKpiTree(array('structure_id'=>$structure_id,'tree_parent'=>$tree_id),array('tree_number'=>'ASC'));
		if(count($data)>0){
			$result = 0;
			foreach( $data as $key => $value ){
				if($value->tree_type=='1'){
					$name = $value->tree_name;
					$html .= '<tr>';
					$html	.= '<td class="text-left"><b>'.$symbol.$value->tree_number.' '.$value->tree_name.'</b></td>';
					$html	.= '<td></td>';
					$html	.= '<td></td>';
					$html	.= '<td></td>';
					$html .= '</tr>';


					if(isset($test['text'])){
						$test['text'] .= $html;
					}else{
						$test['text'] = $html;
					}

				}else{
					$kpi = $kpi_db->getKpi(array('kpi_id'=>$value->kpi_id))[0];
					$fomular_value = $formula_db->getFormulaData(array('structure_id' => $structure_id,'kpi_id' => $value->kpi_id));
					if(isset($fomular_value[0])){
						$fomular_value_data = $fomular_value[0];
					}
					$result = '';
					$target = '';
					if(isset($fomular_value_data)){
						$target = $fomular_value_data->grade_map;
						$result = $fomular_value_data->formula_value;
						$sum_value[$value->tree_parent][] = $result;
					}
					$html .= '<tr>';
					$html	.= '<td class="text-left">'.$symbol.$value->tree_number.' '.$kpi->kpi_name.'</td>';
					$html	.= '<td class="text-right">'.$target.'</td>';
					$html	.= '<td class="text-right">'.$result.'</td>';
					$html	.= '<td class="text-right">'.$value->tree_weight.'</td>';
					$html .= '</tr>';
				}
				// echo $sum_value;
				// $sum_value = 0;

				$html .= loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;&emsp;&emsp;');

				if($value->tree_type=='1'){
					$sum_result = 0;
					if(isset($sum_value[$value->tree_id])){
						$sum_result =  ceil( array_sum($sum_value[$value->tree_id]) / count($sum_value[$value->tree_id]) );
						$sum_all[] = $sum_result;
					}

					// echo "<pre>";
					// print_r($sum_value);
					$html .= '<tr>';
					$html	.= '<td>&emsp;&emsp;&emsp;&emsp;<b>รวม</b></td>';
					$html	.= '<td></td>';
					$html	.= '<td class="text-right">'.$sum_result.'</td>';
					$html	.= '<td></td>';
					$html .= '</tr>';
				}
				// echo "<pre>";
				// echo "string";
				// print_r($sum_all);
			}
	}
	return $html;
		// }
}

// function sumAll($sum='')
// {
// 	global $sum_all
// 	$sum_all_value = array_sum($sum_all)/count($sum_all);
// 	return $sum_all_value;
// }


?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> รายงานการประเมินองค์กรรายปี
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo $prev; ?>"
						 class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>
						<a href="<?php echo base_url("report_assessments/export/$structure_id/pdf"); ?>"
					   class="table-link" title="พิมพ์ PDF" target="_blank">
						<button type="button" class="btn btn-xs btn-danger">
							<i class="fa fa-file-pdf-o"></i> PDF
						</button></a>

					<a href="<?php echo base_url("report_assessments/export/$structure_id/word"); ?>"
					   class="table-link" title="ส่งออก Word" target="_blank">
						<button type="button" class="btn btn-xs btn-primary">
							<i class="fa fa-file-word-o"></i> Word
						</button></a>

					<a href="<?php echo base_url("report_assessments/export/$structure_id/excel"); ?>"
					   class="table-link" title="ส่งออก Excel" target="_blank">
						<button type="button" class="btn btn-xs btn-success">
							<i class="fa fa-file-excel-o"></i> Excel
						</button></a>

				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-offset-2 col-md-8 text-center">
					<table class="table borderless" >
						<tr>
							<td class="text-center h4 strong" colspan="3">
								รายงานการประเมินองค์กรรายปี
							</td>
						</tr>
						<?php
						if(isset($tree) && !empty($tree)){
							foreach ($tree as $key => $value) {
								?>
								<tr>
									<td class="text-left"><b><?php echo $value->tree_number.' '.$value->tree_name; ?></b></td>
									<td>ผลลัพธ์</td>
									<td>เปอร์เซนต์</td>
									<td>ค่าน้ำหนัก</td>
								</tr>
								<?php
								echo loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;');
							}
							// $sum_all_value = 0;
							//
							// if(count($sum_all) != 0){
							// 	$sum_all_value = array_sum($sum_all)/count($sum_all);
							// }

							?>
							<!-- <tr>
								<td class="text-center"><b>รวมทั้งหมด</b></td>
								<td></td>
								<td class="text-right"><?php // echo $sum_all_value; ?></td>
								<td></td>
							</tr> -->
							<?php
						}
						?>
					</table>
				</div>
			</div>

	</div>
</div>
<style>
	.dd-item .row{
		padding: 1px
	}
	.mini-box{
		width: 40px !important;
		margin: 0px 1px;
	}
</style>
