
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("evaluate_targets/dashboard_evaluate_targets"); ?>"
			 class="table-link" title="ย้อนกลับ">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button></a>
		<!-- <a href="<?php //echo base_url("criteria_datas/new_criteria_data/{$data->id}"); ?>"
			 class="table-link" title="เพิ่ม">
			<button type="button" class="btn btn-xs btn-success">
				<i class="fa fa-add"></i> เพิ่มเกณฑ์การประเมิน
			</button></a> -->
		<!-- <a href="#"
			 class="table-link" title="แก้ไข">
			<button type="button" class="btn btn-xs btn-warning">
				<i class="fa fa-edit"></i> แก้ไข
			</button></a> -->
	</div>
</div>
<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="8%">ลำดับ</th>
			<th class="text-center" width="17%">ชื่อโครงการ</th>
			<th class="text-center" width="6%">ปีงบประมาณ</th>
			<?php
				if($year_show){
					for($i = 0;$year_start+$i<=$year_end;$i++){
						?>
						<th class="text-center" width="35px">เป้าหมายปี <?php echo $year_start+$i; ?></th>
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
								<td class="text-center" ><input type="text" style="width:35px;" value="1.0" disabled></td>
								<td class="text-center" ><input type="text" style="width:35px;" value="0.5" disabled></td>
								<td class="text-center" ><input type="text" style="width:35px;" value="50" disabled></td>
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
