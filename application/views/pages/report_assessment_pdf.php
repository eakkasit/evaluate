<?php
function loopTreeFormListSub($tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,$html,$symbol,$data_result){
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
					$weight = '';
					if(isset($fomular_value_data)){
						$target = $fomular_value_data->grade_map;
						$result = $fomular_value_data->formula_value;
						// $sum_value[$value->tree_parent][] = $result;
					}


					if(isset($data_result['result'][$value->tree_id])){
						$target = $data_result['result'][$value->tree_id];
					}

					if(isset($data_result['percent'][$value->tree_id])){
						$result = $data_result['percent'][$value->tree_id];
					}

					if(isset($data_result['weight'][$value->tree_id])){
						$weight = $data_result['weight'][$value->tree_id];
					}

					if($result != ''){
							$sum_value[$value->tree_parent][] = $result;
					}
					$project_name = '';
					if(isset($data_result['project_name_list'][$value->tree_id])){
						$project_name = $data_result['project_name_list'][$value->tree_id];
					}
					$html .= '<tr>';
					$html	.= '<td class="text-left">'.$symbol.$value->tree_number.' '.$kpi->kpi_name.'</td>';
					$html	.= '<td class="text-left">'.$project_name.'</td>';
					$html	.= '<td class="text-right"  align="right">'.$target.'</td>';
					$html	.= '<td class="text-right"  align="right">'.$result.'</td>';
					$html	.= '<td class="text-right"  align="right">'.$weight.'</td>';
					$html .= '</tr>';
				}

				$html .= loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;&emsp;&emsp;',$data_result);

				if($value->tree_type=='1'){
					$sum_result = 0;
					if(isset($sum_value[$value->tree_id])){
						$sum_result =  number_format(array_sum($sum_value[$value->tree_id]) / count($sum_value[$value->tree_id]),2) ;
						if($sum_result > 100){
							$sum_result = 100;
						}
						$sum_all[] = $sum_result;
					}

					$html .= '<tr>';
					$html	.= '<td colspan="2">&emsp;&emsp;&emsp;&emsp;<b>รวม</b></td>';
					$html	.= '<td></td>';
					$html	.= '<td class="text-right"  align="right">'.$sum_result.'</td>';
					$html	.= '<td></td>';
					$html .= '</tr>';
				}
			}
	}
	return $html;
}

?>
<html>
<body>
<style>
table{
    font-family: "Garuda";
    font-size: 12pt;
}
p{
    text-align: justify;
}
h1{
    text-align: center;
}
table#table_assessment td {
 border: 1px solid black;
 border-collapse: collapse;
 padding: 4px;
}
</style>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td colspan="15">
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
							<td align="center">ข้อมูลที่เชื่อมโยง</td>
							<td align="center">ผลลัพธ์</td>
							<td align="center">เปอร์เซนต์</td>
							<td align="center">ค่าน้ำหนัก</td>
						</tr>
						<?php
						echo loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;',$result);
						if($value->tree_type=='1'){

							?>
							<tr>
								<td colspan="2" align="center">&emsp;&emsp;&emsp;&emsp;<b>คะแนนเฉลี่ยรวมรายหมวด</b></td>
								<td></td>
								<td class="text-right" align="right">
									<?php  echo (isset($result['total'][$value->tree_id]))? number_format($result['total'][$value->tree_id],2):''; ?>
								</td>
								<td></td>
							</tr>
							<?php
						}
					}
				}
				?>
			</table>
    </td>
	</tr>
</table>
</body>
</html>
