<?php $this->load->view("template/search"); ?>
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("report_targets/export/pdf"); ?>"  class="table-link" title="พิมพ์ PDF" target="_blank">
			<button type="button" class="btn btn-xs btn-danger">
				<i class="fa fa-file-pdf-o"></i> PDF
			</button>
		</a>
		<a href="<?php echo base_url("report_targets/export/word"); ?>" class="table-link" title="ส่งออก Word" target="_blank">
			<button type="button" class="btn btn-xs btn-primary">
				<i class="fa fa-file-word-o"></i> Word
			</button>
		</a>
		<a href="<?php echo base_url("report_targets/export/excel"); ?>" class="table-link" title="ส่งออก Excel" target="_blank">
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
				<!-- <th class="text-center" width="15%">เป้าหมายปี <?php echo $year; ?></th>
				<th class="text-center" width="15%" >ผลการประเมิน</th>
				<th class="text-center" width="15%" >ร้อยละความสำเร็จ</th> -->
				<?php
					if($year_show){
						for($i = 0;$year_start+$i<=$year_end;$i++){
							?>
							<th class="text-center" width="35px">เป้าหมายปี <?php  echo $year_start+$i; ?></th>
							<th class="text-center" width="35px" >ผลการประเมิน</th>
							<th class="text-center" width="35px" >ร้อยละความสำเร็จ</th>
							<?php
						}
					}
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
							<?php echo $project->year ; ?>
						</td>

						<?php
							if($year_show){
								for($i = 0;$year_start+$i<=$year_end;$i++){
									?>
									<td class="text-right" ><?php echo isset($data[$project->id][$year_start+$i])?number_format($data[$project->id][$year_start+$i],2):'' ?></td>
									<td class="text-right" ><?php echo isset($project->result)?number_format($project->result,2):''; ?></td>
									<td class="text-right" >
										<?php
											if(isset($data[$project->id][$year_start+$i]) && isset($project->result)){
												if($data[$project->id][$year_start+$i] != 0){
													$percent =  ($project->result/$data[$project->id][$year_start+$i]) * 100;
												}else{
													$percent = 0;
												}
												echo number_format($percent,2);
											}else{
												echo "";
											}
										?>
									</td>
									<?php
								}
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
	<?php $this->load->view("template/pagination"); ?>
</div>
<script type="text/javascript">
    function delete_evaluate_target(id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องเป้าหมายโครงการ",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("evaluate_targets/delete_evaluate_targets/"); ?>' + id;
                }
            });
    }
</script>
