<?php
$title_prefix = 'เพิ่ม';
$action = base_url("structure/save");
$prev = base_url("structure/dashboard_structure");
if (isset($data->structure_id) && $data->structure_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->structure_id}";
	$prev = base_url("structure/dashboard_structure/{$data->structure_id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>แม่แบบเกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">แม่แบบเกณฑ์การประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="structure_name" class="form-control"
									   value="<?php if (isset($data->structure_name)) { echo $data->structure_name;	} ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("structure_name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ปีงบประมาณ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="profile_year">
									<?php foreach ($year_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->profile_year) && $data->profile_year == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("profile_year"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">สถานะการใช้งาน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php foreach ($status_list as $key => $value) { ?>
									<input type="radio" name="structure_status"
										   id="user_status_<?php echo $key; ?>"
										   class=""
										   value="<?php echo $key; ?>" <?php if (isset($data->structure_status) && $data->structure_status == $key) {
										echo 'checked="checked"';
									} ?>>&nbsp;<label
										for="user_status_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
								<?php } ?>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("structure_status"); ?></label>
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
