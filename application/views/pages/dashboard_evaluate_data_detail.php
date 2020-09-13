<?php //$this->load->view("template/search"); ?>
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("evaluate_datas/dashboard_evaluate_datas"); ?>"
			 class="table-link" title="ย้อนกลับ">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button></a>
			<label class="col-md-12"></label>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table role="grid" id="table-example"
			   class="table table-bordered table-hover dataTable no-footer">
			<thead>
			<tr role="row">
				<th class="text-center start_no" width="5%">ลำดับ</th>
				<th class="text-center" width="25%">ชื่อโครงการ</th>
				<th class="text-center" width="5%">ปีงบประมาณ</th>
				<th class="text-center" width="15%">ผู้รับผิดชอบ</th>
				<th class="text-center" width="20%">วัตถุประสงค์</th>
				<th class="text-center" width="10%">ผลการดำเนินโครงการ</th>
				<th class="text-center" width="10%"></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			// if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
			if (isset($datas) && !empty($datas)) {
				foreach ($datas as $key => $data) {
					?>
					<tr class="odd" role="row">
						<td class="text-center">
							<?php
							echo number_format($no + $key, 0);
							?>
						</td>
						<td class="text-left">
							<?php echo $data->task_name; ?>
						</td>
						<td class="text-left">
							<?php echo $data->task_year+543; ?>
						</td>
						<td class="text-left">
							<?php //echo $data->department; ?>
						</td>
						<td class="text-left">
							<?php //echo $data->detail; ?>
						</td>
						<td class="text-left">
							<?php if(isset($result[$data->project_id][$data->task_id][$data->task_year])){ echo $result[$data->project_id][$data->task_id][$data->task_year];}?>
						</td>
						<td  class="text-left">
							<div>
									<?php
									if($data->parent_task_id != 0){
										?>
										<a href="<?php echo base_url("evaluate_datas/edit_evaluate_data/{$project_id}/{$data->task_id}"); ?>" class="table-link" title="บันทึกผลการประเมิน">
											<button type="button" class="btn btn-xs btn-success">
												<i class="fa fa-plus"></i> บันทึกผลการประเมิน
											</button>
										</a>
										<?php
									}
									?>

							</div>
						</td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
	</div>
</div>
