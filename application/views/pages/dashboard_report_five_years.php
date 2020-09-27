<?php $this->load->view("template/search"); ?>
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("report_five_years/export/pdf"); ?>"  class="table-link" title="พิมพ์ PDF" target="_blank">
			<button type="button" class="btn btn-xs btn-danger">
				<i class="fa fa-file-pdf-o"></i> PDF
			</button>
		</a>
		<a href="<?php echo base_url("report_five_years/export/word"); ?>" class="table-link" title="ส่งออก Word" target="_blank">
			<button type="button" class="btn btn-xs btn-primary">
				<i class="fa fa-file-word-o"></i> Word
			</button>
		</a>
		<a href="<?php echo base_url("report_five_years/export/excel"); ?>" class="table-link" title="ส่งออก Excel" target="_blank">
			<button type="button" class="btn btn-xs btn-success">
				<i class="fa fa-file-excel-o"></i> Excel
			</button>
		</a>
	</div>
	<label class="col-md-12"></label>
</div>
<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
			<tr role="row">
				<th class="text-center start_no" width="5%">ลำดับ</th>
				<th class="text-center" width="40%">ชื่อโครงการ</th>
				<th class="text-center" width="10%">ปีงบประมาณ</th>
				<th class="text-center" width="10%">น้ำหนักโครงการ</th>
				<!-- <th class="text-center" width="15%">เป้าหมายปี <?php //echo $year; ?></th>
				<th class="text-center" width="15%" >ผลการประเมิน</th>
				<th class="text-center" width="15%" >ร้อยละความสำเร็จ</th> -->
				<?php
					// if($year_show){
						for($i = 0;$year_start+$i<=$year_end;$i++){
							?>
							<th class="text-center" width="125px">เป้าหมายปี <?php  echo $year_start+$i+543; ?></th>
							<th class="text-center" width="125px" >ผลการประเมิน</th>
							<th class="text-center" width="125px" >ร้อยละความสำเร็จ</th>
							<?php
						}
					// }
				?>
			</tr>
			</thead>
			<tbody>
			<?php
			if (isset($project_list) && !empty($project_list)) {
				foreach ($project_list as $key => $project) {
					?>
					<tr class="odd" role="row">
						<td class="text-center">
							<?php
							echo $key+1;
							?>
						</td>
						<td class="text-left">
							<?php echo $project->project_name ?>
						</td>
						<td class="text-left">
							<?php
							if($project->year_start == $project->year_end){
								echo $project->year_start+543;
							}else{
								$year_start_show = $project->year_start+543;
								$year_end_show = $project->year_end+543;
								echo "$year_start_show - $year_end_show" ;
							}
							?>
						</td>
						<td class="text-right">
							<?php echo $project->weight ; ?>
						</td>
						<?php
							// if($year_show){
								$arr_temp = array();
								for($i = 0;$year_start+$i<=$year_end;$i++){
									$target = isset($target_data[$project->id][$year_start+$i])?number_format($target_data[$project->id][$year_start+$i],2):'' ;
									$result = isset($result_data[$project->id][$year_start+$i])?number_format($result_data[$project->id][$year_start+$i],2):'' ;
									$evaluate_result = '';
									if($target != '' && $result != ''){
										if($project->year == $year_start+$i){
											$evaluate_result = ($result*100)/$target;
											$arr_temp[$year_start+$i]  = $target - $result;
										}else{
											$arr_temp[$year_start+$i] = ($target + $arr_temp[$year_start+$i-1]);
											$point_before = $target_data[$project->id][$year_start+$i] + ($target_data[$project->id][$year_start+$i-1] - $result_data[$project->id][$year_start+$i-1])  ;
											$evaluate_result = number_format((($result*100)/$point_before),2);
										}

									}
									?>
									<td class="text-right" ><?php echo $target ?></td>
									<td class="text-right" ><?php echo $result ?></td>
									<td class="text-right" ><?php echo $evaluate_result; ?></td>
									<?php
								// }
							}
						?>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
	</table>
</div>
<div class="pagination pull-right">
	<?php //$this->load->view("template/pagination"); ?>
</div>
<script type="text/javascript">
    function delete_criteria_assessment(user_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับผู้ใช้งานนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("criteria_assessments/delete_criteria_assessment/"); ?>' + user_id;
                }
            });
    }
</script>
