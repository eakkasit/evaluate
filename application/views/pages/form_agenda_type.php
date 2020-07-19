<?php
$title_prefix = 'เพิ่ม';
$action = base_url("agenda_types/save");
$prev = base_url("agenda_types/dashboard_agenda_types");
if (isset($agenda_type_data->agenda_type_id) && $agenda_type_data->agenda_type_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$agenda_type_data->agenda_type_id}";
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>ประเภทวาระการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">
					<div class="col-md-4">
					<label for="stext">ประเภทวาระการประชุม</label>
					</div>

					<div class="col-md-8">

						<input type="text" name="agenda_type_name" class="form-control"
									   value="<?php if (isset($agenda_type_data->agenda_type_name)) {
										   echo $agenda_type_data->agenda_type_name;
									   } ?>" placeholder="ระบุ">
						<label class="col-md-12 text-danger"><?php echo form_error("agenda_type_name"); ?></label>
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
