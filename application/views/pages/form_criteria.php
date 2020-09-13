<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria/save_data");
$prev = base_url("criteria/dashboard_criteria");
$ajax_form_url = base_url("criteria/ajax_get_data_form/");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("criteria/view_criteria_data/{$data->id}");
}

$sum_value = array();
$sum_all = array();
function loopTreeFormListSub($tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,$html,$symbol,$activity,$data_result){
	global $sum_value,$sum_all;
	$data = $tree_db->getKpiTree(array('structure_id'=>$structure_id,'tree_parent'=>$tree_id),array('tree_number'=>'ASC'));
		if(count($data)>0){
			$result = 0;
			foreach( $data as $key => $value ){
				if($value->tree_type=='1'){
					$name = $value->tree_name;
					$html .= '<tr>';
					$html	.= '<td class="text-left">';
					$html .= '<div class="form-group">';
					$html .= 				'<label class="col-md-4"><b>'.$symbol.'หมวด</b></label>';
					$html .= 					'<div class="col-md-8">';
					$html .= 						'<input type="hidden" name="criteria_data['.$value->tree_id.'][structure_id]" class="form-control" value="'.$structure_id.'">';
					$html .= 						'<input type="hidden" name="criteria_data['.$value->tree_id.'][tree_number]" class="form-control" value="'.$value->tree_number.'">';
					$html .= 						'<input type="text" name="criteria_data['.$value->tree_id.'][criteria_name]" class="form-control" value="'.$value->tree_number.' '.$value->tree_name.'">';
					$html .= 					'</div>';
					$html .= 				'</div>';
					$html .= '</td>';
					$html	.= '<td></td>';
					$html	.= '<td></td>';
					$html	.= '<td></td>';
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
					$html	.= '<td class="text-left">';
					$html .= '<div class="form-group">';
					$html .= 		'<label class="col-md-4"><b>เกณฑ์</b></label>';
					$html .= 		'<div class="col-md-8">';
					$html .= 			'<input type="hidden" name="criteria_data['.$value->tree_id.'][structure_id]" class="form-control" value="'.$structure_id.'">';
					$html .= 			'<input type="hidden" name="criteria_data['.$value->tree_id.'][tree_number]" class="form-control" value="'.$value->tree_number.'">';
					$html .= 			'<input type="text" name="criteria_data['.$value->tree_id.'][criteria_name]" class="form-control" value="'.$value->tree_number.' '.$kpi->kpi_name.'">';
					$html .= 		'</div>';
					$html .= '</div>';
					$html .= '</td>';
					$html	.= '<td>';
					$html	.= '<select name="criteria_data['.$value->tree_id.'][project_id]" class="form-control mw-150"><option value="">----- เลือกโครงการ -----</option>';
					if(isset($activity) && !empty($activity)){
						foreach ($activity as $activity_key => $activity_value) {
							$sel = "";
							if(isset($data_result['project_id'][$value->tree_id])){
								if($activity_key == $data_result['project_id'][$value->tree_id]){
									$sel = "selected";
								}else{
									$sel = "";
								}
							}
							$html	.= '<option value="'.$activity_key.'" '.$sel.'>'.$activity_value.'</option>';
						}
					}
					$html	.= '</select>';
					// $html .= '<label></label>';
					// $html	.= '<select name="" class="form-control mw-150"><option>----- เลือกกิจกรรม -----</option>';
					// $html	.= '</select>';
					$html	.= '</td>';
					$html	.= '<td><a href="#" onClick="show_variable(\''.$value->kpi_id.'\',\''.$value->tree_id.'\',\''.$kpi->kpi_standard_type.'\')" class="btn btn-sm btn-success">บันทึกค่าตัวแปร</a></td>';
					$html	.= '<td class="text-right">';
					$html .= '<div class="form-group">';
					$html .= 		'<label class="col-md-6">ผลลัพธ์ > </label>';
					$html .= 		'<div class="col-md-6">';
					$html .= 			'<input type="text" name="criteria_data['.$value->tree_id.'][result]" class="form-control w-50 text-right target_value_'.$value->kpi_id.'" value="'.$target.'">';
					$html .= 		'</div>';
					$html .= '</div>';
					$html .= '</td>';
					$html	.= '<td class="text-right">';
					$html .= 			'<input type="text" name="criteria_data['.$value->tree_id.'][percent]" class="form-control w-50 text-right result_value_'.$value->kpi_id.' percent_'.$value->tree_parent.'" value="'.$result.'">';
					$html	.= '</td>';
					$html	.= '<td class="text-right">';
					$html .= 			'<input type="text" name="criteria_data['.$value->tree_id.'][weight]" class="form-control w-50 text-right" value="'.$value->tree_weight.'">';
					$html	.= '</td>';
					$html	.= '<td><a href="#" class="btn btn-sm btn-warning">เอกสารแนบ</a></td>';
					$html .= '</tr>';
				}

				$html .= loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;&emsp;&emsp;',$activity,$data_result);

				if($value->tree_type=='1'){
					$sum_result = 0;
					if(isset($sum_value[$value->tree_id])){
						$sum_result =  ceil( array_sum($sum_value[$value->tree_id]) / count($sum_value[$value->tree_id]) );
						$sum_all[] = $sum_result;
					}
					$html .= '<tr>';
					$html	.= '<td colspan="3" align="center">&emsp;&emsp;&emsp;&emsp;<b>คะแนนเฉลี่ยรวมรายหมวด</b></td>';
					$html	.= '<td></td>';
					$html	.= '<td class="text-right">';
					$html .= 			'<input type="text" name="criteria_data['.$value->tree_id.'][total]" data-percent="'.$value->tree_id.'" class="form-control w-50 text-right percent_total_'.$value->tree_id.'" value="'.$sum_result.'">';
					$html	.= '</td>';
					$html	.= '<td></td>';
					$html	.= '<td></td>';
					$html .= '</tr>';
				}
			}
	}
	return $html;
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> บันทึกการประเมินองค์กรรายปี
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

				</div>
			</div>
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<input type="hidden" name="structure_id" class="form-control" value="<?php echo $structure_id; ?>">
				<table class="table borderless" >
					<?php
					if(isset($tree) && !empty($tree)){
						foreach ($tree as $key => $value) {
							?>
							<tr>
								<td class="text-left">
									<div class="form-group">
										<label class="col-md-3"><b>หมวด</b></label>
										<div class="col-md-8">
											<input type="hidden" name="criteria_data[<?php echo $value->tree_id; ?>][structure_id]" class="form-control" value="<?php echo $structure_id; ?>">
											<input type="hidden" name="criteria_data[<?php echo $value->tree_id; ?>][tree_number]" class="form-control" value="<?php echo $value->tree_number; ?>">
											<input type="text" name="criteria_data[<?php echo $value->tree_id; ?>][criteria_name]" class="form-control" value="<?php echo $value->tree_number.' '.$value->tree_name; ?>">
										</div>
									</div>
								</td>
								<td></td>
								<td></td>
								<td align="center">ผลลัพธ์</td>
								<td>เปอร์เซนต์</td>
								<td>ค่าน้ำหนัก</td>
								<td></td>
							</tr>
							<?php
							echo loopTreeFormListSub($value->tree_id,$structure_id,$tree_db,$kpi_db,$formula_db,'','&emsp;&emsp;',$activity,$result);
						}
						?>
						<?php
					}
					?>
				</table>
				<div class="row">
					<div class="col-md-12 text-center">
						<a href="<?php echo $prev; ?>" class="btn btn-sm btn-danger">
							<i class="fa fa-times"></i>
							ยกเลิก
						</a>
						&nbsp;&nbsp;
						<button class="btn btn-sm btn-success" type="submit" id="submit">
							<i class="fa fa-floppy-o"></i>
							บันทึก
						</button>
					</div>
				</div>
			</form>
	</div>
</div>
<!-- Modal -->
<form  method="post" action="" name="formvariable" id="formvariable">
	 <div id="myModal" class="modal fade" role="dialog">
		 <div class="modal-dialog">

			 <!-- Modal content-->
			 <div class="modal-content">
				 <div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					 <h4 class="modal-title">ตัวแปร</h4>
				 </div>
				 <div class="modal-body">
					 <input type="hidden" name="structure_id" id="structure_id" value="<?php echo $structure_id; ?>">
					 <input type="hidden" name="tree_id" id="tree_id" value="">
					 <div id="variable_data"></div>
				 </div>
				 <div class="modal-footer">
					 <input type="button" value="Save" onclick="addRow('dataTable')" class="btn btn-info "  id="addpro" style="display:none"  data-dismiss="modal"/>
					 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					 <button type="submit" name="savechange" value="savechange" class="btn btn-success" id="savechange" >บันทึก</button>
				 </div>
			 </div>

		 </div>
	 </div>
 </form>
<script type="text/javascript">
	var dependvar = {};
	var dependcond = {};
		function getData(){
			$.ajax({
					url: '<?php echo base_url('criteria/ajax_kpi_tree/'.$structure_id); ?>',
					type: "GET",
					success: function (data) {
						$('#kpi_list').html(data)
						depend_process();
					},
			})
		}

		function show_variable(kpi_id,tree_id,kpi_standard_type) {
			$.ajax({
				url: '<?php echo base_url('criteria/ajax_var_data/'); ?>' + kpi_id + '/' + tree_id +'/'+kpi_standard_type ,
				type: "GET",
				success: function (data) {
					$('#variable_data').html(data)
					$('#tree_id').val(tree_id)
					depend_process()
					$('#myModal').modal('show');
				},
			})
		}

    function saveform(form){
        // Get first form element
        var $form = $('form')[0];

        // Check if valid using HTML5 checkValidity() builtin function
        if ($form.checkValidity()) {
            console.log('valid');
            $form.submit();
        } else {
            console.log('not valid');
        }
        return false;
    }

    function setval(str,obj,val) {
        if(obj.checked) {
            $("input[name='" + str + "']").val(val);
        }else{
            $("input[name='" + str + "']").val(0);
        }
    }

    function depend(input,dp) {
        //alert(dependvar['v2']);
        //alert(input.type);
        if(input.type == 'checkbox'){
            if(input.checked) {
                dependvar[dp] = 1;
            }else{
                dependvar[dp] = 0;
            }
        }else{
            if(input.value) {
                dependvar[dp] = 1;
            }else{
                dependvar[dp] = 0;
            }
        }

        depend_process();
    }

    function depend_process(){
        var txtdp='';
        for(var dp in dependcond){
					// console.log('txtdp',dp);
            txtdp = dependcond[dp];
            if(txtdp==''){
                txtdp = '1';
            }
            //alert("textdp="+txtdp);
            for(var dpv in dependvar){
                txtdp = txtdp.replace(dpv,dependvar[dpv]);
                //alert(dpv+"="+dependvar[dpv]);
            }
            //alert("textdp-out="+txtdp);
						// console.log('txtdp',txtdp);
            var dp_out = eval(txtdp);
            if(dp_out){
                $('.depend_'+dp).prop( "disabled", false );
            }else{
                $('.depend_'+dp).prop( "disabled", true );
            }
        }
    }

		function calulateCriteria(){
			$("input[class*='percent_total_']").each(function(i,e){
				var total_id = $(this).attr('data-percent')

				var sum_data = 0;
				var $percent = '.percent_'+total_id
				var length_percent = 0;
				$($percent).each(function(e){
					var point = parseInt($(this).val())
					if(!isNaN(point)){
						sum_data += point;
						length_percent++;
					}
				})
				var percent_data = sum_data/length_percent
				if(!isNaN(percent_data)){
					if(percent_data > 100){
						$(this).val(100.00)
					}else if (percent_data < 0) {
						$(this).val(0.00)
					}else{
						$(this).val(percent_data.toFixed(2))
					}

				}

			})
		}

    jQuery(document).ready(function () {
				getData();

				setTimeout(function(){
					calulateCriteria()
				} , 1000);

				// $("input").on("keyup change keypress focus",function(e) {
				//   console.log('change',$(this).attr('class'));
				// });

				jQuery("#formvariable").submit(function(e){
					var err_text = ''
						$.ajax({
							url: '<?php echo base_url("criteria/ajax_save_variable_data"); ?>',
							type: "POST",
							data:  $(this).serialize(),
							success: function (data) {
								var result = JSON.parse(data)
								// console.log(result);
								// $('#variable-table tbody').append(data);
								// // addRowVariable(data)
								$('#myModal').modal('hide')
								$('.target_value_'+result['kpi_id']).val(result['grade_map'])
								$('.result_value_'+result['kpi_id']).val(result['fomular_value'])
								//
								setTimeout(function(){
									calulateCriteria()
								} , 1000);
								// aaaaa();
								// window.location.reload();
								// console.log('data',data)
							},
							error:function (error) {
								console.log('err',error)
							}
						})


					e.preventDefault();
				})

    });
</script>
<style>
	.dd-item .row{
		padding: 1px
	}
	.mini-box{
		width: 40px !important;
		margin: 0px 1px;
	}
	.w-50{
		width: 50px !important;
	}

	.mw-150{
		width: 150px !important;
	}
</style>
