<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_themes/save");
$prev = base_url("criteria_themes/dashboard_criteria_themes");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("criteria_themes/view_criteria_theme/{$data->id}");
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
								<input type="text" name="profile_name" class="form-control"
									   value="<?php if (isset($data->profile_name)) { echo $data->profile_name;	} ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("profile_name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ปี</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="year">
									<?php foreach ($year_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->year) && $data->year == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("year"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">รายละเอียด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea type="text" name="detail" cols="4" rows="5" class="form-control" placeholder="ระบุ"><?php if (isset($data->detail)) { echo $data->detail;	} ?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("detail"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">สถานะการใช้งาน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php foreach ($status_list as $key => $value) { ?>
									<input type="radio" name="status"
										   id="user_status_<?php echo $key; ?>"
										   class=""
										   value="<?php echo $key; ?>" <?php if (isset($data->status) && $data->status == $key) {
										echo 'checked="checked"';
									} ?>>&nbsp;<label
										for="user_status_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
								<?php } ?>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("status"); ?></label>
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
