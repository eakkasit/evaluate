<?php
$title_prefix = 'เพิ่ม';
$action = base_url("agenda_defaults/save");
$prev = base_url("agenda_defaults/dashboard_agenda_defaults");
if (isset($agenda_default_data->agenda_default_id) && $agenda_default_data->agenda_default_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$agenda_default_data->agenda_default_id}";
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>ค่าเริ่มต้นวาระการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">
					<div class="col-md-4">
					<label for="stext">หัวเรื่อง <font class="text-danger">*</font></label>
					</div>

					<div class="col-md-8">

						<input type="text" name="agenda_default_name" class="form-control"
									   value="<?php if (isset($agenda_default_data->agenda_default_name)) {
										   echo $agenda_default_data->agenda_default_name;
									   } ?>" placeholder="ระบุ">
						<label class="col-md-12 text-danger"><?php echo form_error("agenda_default_name"); ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
					<label for="stext">เรื่องเดิม</label>
					</div>

					<div class="col-md-8">

						<input type="text" name="agenda_default_story" class="form-control"
									   value="<?php if (isset($agenda_default_data->agenda_default_story)) {
										   echo $agenda_default_data->agenda_default_story;
									   } ?>" placeholder="ระบุ">
						<label class="col-md-12 text-danger"><?php echo form_error("agenda_default_story"); ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
					<label for="stext">เนื้อหาวาระ</label>
					</div>

					<div class="col-md-8">

						<input type="text" name="agenda_default_detail" class="form-control"
									   value="<?php if (isset($agenda_default_data->agenda_default_detail)) {
										   echo $agenda_default_data->agenda_default_detail;
									   } ?>" placeholder="ระบุ">
						<label class="col-md-12 text-danger"><?php echo form_error("agenda_default_detail"); ?></label>
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
