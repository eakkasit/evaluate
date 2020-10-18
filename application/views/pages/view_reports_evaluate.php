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
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("report_evaluates/export/{$project_id}/pdf"); ?>"  class="table-link" title="พิมพ์ PDF" target="_blank">
			<button type="button" class="btn btn-xs btn-danger">
				<i class="fa fa-file-pdf-o"></i> PDF
			</button>
		</a>
		<a href="<?php echo base_url("report_evaluates/export/{$project_id}/word"); ?>" class="table-link" title="ส่งออก Word" target="_blank">
			<button type="button" class="btn btn-xs btn-primary">
				<i class="fa fa-file-word-o"></i> Word
			</button>
		</a>
		<a href="<?php echo base_url("report_evaluates/export/{$project_id}/excel"); ?>" class="table-link" title="ส่งออก Excel" target="_blank">
			<button type="button" class="btn btn-xs btn-success">
				<i class="fa fa-file-excel-o"></i> Excel
			</button>
		</a>
	</div>
	<label class="col-md-12"></label>
</div>
<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table role="grid" id="table-example"
			   class="table table-bordered table-hover dataTable no-footer">
			<thead>
			<tr role="row">
				<th class="text-center start_no" width="5%">ลำดับ</th>
				<th class="text-center" width="15%">ชื่อโครงการ</th>
				<th class="text-center" width="5%">ปีงบประมาณ</th>
				<th class="text-center" width="10%">ผู้รับผิดชอบ</th>
				<th class="text-center" width="10%">วัตถุประสงค์</th>
				<th class="text-center" width="10%">ผลการดำเนินโครงการ</th>
				<th class="text-center" width="10%">ผลผลิต</th>
				<th class="text-center" width="10%">ผลลัพธ์</th>
				<th class="text-center" width="10%">ผลการประเมิน</th>
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
							<?php echo $project_data[0]->project_name; ?>
						</td>
						<td class="text-center">
							<?php echo $data->year+543; ?>
						</td>
						<td class="text-left"> -
							<?php //echo $data->department; ?>
						</td>
						<td class="text-left"> -
							<?php //echo $data->detail; ?>
						</td>
						<td class="text-left">
							<?php echo $data->project_result; ?>
						</td>
						<td class="text-left">
							<?php echo $data->product; ?>
						</td>
						<td class="text-center">
							<?php echo $data->result; ?>
						</td>
						<td class="text-center">
							<?php echo $data->assessment_results; ?>
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
