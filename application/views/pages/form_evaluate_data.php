<?php
$title_prefix = 'เพิ่ม';
$action = base_url("evaluate_datas/save");
$prev = base_url("evaluate_datas/dashboard_evaluate_data_detail/".$project_id);
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>บันทึกรายงานประเมินผล
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">
						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ชื่อโครงการ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo $data->task_name; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ปีงบประมาณ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo $data->task_year+543; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผู้รับผิดชอบ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php // echo $data->sql; ?>-</label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">วัตถุประสงค์ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php // echo $data->sql; ?>-</label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผลการดำเนินโครงการ :</label>
							</div>
							<div class="col-md-8 text-left">
								<input type="text" name="result" class="form-control" value="<?php if (isset($data->result)) { echo $data->result;	} ?>" placeholder="ระบุ">
							</div>
							<label class="col-md-12 text-danger"><?php echo form_error("result"); ?></label>
						</div>

					</div>
				</div>
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
</div>
