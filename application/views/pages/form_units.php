<?php
$title_prefix = 'เพิ่ม';
$action = base_url("units/save");
$prev = base_url("units/dashboard_units");
if (isset($data->unit_id) && $data->unit_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->unit_id}";
	$prev = base_url("units/view_units/{$data->unit_id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>หน่วยวัดเกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อหน่วยวัดเกณฑ์การประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="unit_name" class="form-control"
									   value="<?php if (isset($data->unit_name)) { echo $data->unit_name;	} ?>" placeholder="ระบุ">
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("unit_name"); ?></label>
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
