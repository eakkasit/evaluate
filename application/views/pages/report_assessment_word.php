<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=รายงานการประเมินองค์กร.doc");
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
					$html	.= '<td align="left"><b>'.$symbol.$value->tree_number.' '.$value->tree_name.'</b></td>';
					$html	.= '<td></td>';
					$html	.= '<td></td>';
					$html	.= '<td></td>';
					$html .= '</tr>';

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
					$html	.= '<td align="left">'.$symbol.$value->tree_number.' '.$kpi->kpi_name.'</td>';
					$html	.= '<td align="right">'.$target.'</td>';
					$html	.= '<td align="right">'.$result.'</td>';
					$html	.= '<td align="right">'.$value->tree_weight.'</td>';
					$html .= '</tr>';
				}

				$html .= loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;&emsp;&emsp;');

				if($value->tree_type=='1'){
					$sum_result = 0;
					if(isset($sum_value[$value->tree_id])){
						$sum_result =  ceil( array_sum($sum_value[$value->tree_id]) / count($sum_value[$value->tree_id]) );
						$sum_all[] = $sum_result;
					}

					$html .= '<tr>';
					$html	.= '<td>&emsp;&emsp;&emsp;&emsp;<b>รวม</b></td>';
					$html	.= '<td></td>';
					$html	.= '<td align="right">'.$sum_result.'</td>';
					$html	.= '<td></td>';
					$html .= '</tr>';
				}
			}
	}
	return $html;
}
?>
<html>
<style>
 table#table_assessment td {
  border: 1px solid black;
	border-collapse: collapse;
	padding: 4px;
}
</style>
<body>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td colspan="4">
			<center>
				<strong>
					<?php echo thai_number("รายงานการประเมินองค์กร"); ?>
				</strong>
			</center>
			  <br/>
		</td>
	</tr>
	<tr>
		<td>
			<table border="1" id="table_assessment"  cellspacing="0" cellpadding="0" width="100%">
				<?php
				if(isset($tree) && !empty($tree)){
					foreach ($tree as $key => $value) {
						?>
						<tr>
							<td align="left" ><b><?php echo $value->tree_number.' '.$value->tree_name; ?></b></td>
							<td align="center">ผลลัพธ์</td>
							<td align="center">เปอร์เซนต์</td>
							<td align="center">ค่าน้ำหนัก</td>
						</tr>
						<?php
						echo loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;');
					}
				}
				?>
			</table>
		</td>
	</tr>

</table>
</body>
</html>
