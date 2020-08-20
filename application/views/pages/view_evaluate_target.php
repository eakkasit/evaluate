<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> เป้าหมายโครงการ
</p>

<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("evaluate_targets/dashboard_evaluate_targets"); ?>"
			 class="table-link" title="ย้อนกลับ">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button></a>
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
								<td class="text-center" >1.0</td>
								<td class="text-center" >0.5</td>
								<td class="text-center" >50</td>
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
