<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_variables/save");
$prev = base_url("criteria_variables/dashboard_criteria_variables");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("criteria_variables/view_criteria_variable/{$data->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>ตัวแปรเกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อตัวแปรเกณฑ์การประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="variable_name" class="form-control"
									   value="<?php if (isset($data->variable_name)) { echo $data->variable_name;	} ?>" placeholder="ระบุ">
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("variable_name"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">หน่วยวัด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="units">
									<?php foreach ($units_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>" <?php if (isset($data->units) && $data->units == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("units"); ?></label>
							</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชนิดของการแสดงผล</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="type_show">
									<?php foreach ($show_type_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->type_show) && $data->type_show == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("type_show"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ประเภทการนำเข้าข้อมูล</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php foreach ($field_type_list as $key => $value) { ?>
									<input type="radio" name="type_field"
											 id="units_<?php echo $key; ?>"
											 class=""
											 value="<?php echo $key; ?>" <?php if (isset($data->type_field) && $data->type_field == $key) {
										echo 'checked="checked"';
									} ?>>&nbsp;<label
										for="units_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
								<?php } ?>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("field_type"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ค่าตัวแปร</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="variable_value" class="form-control"  value="<?php if (isset($data->variable_value)) { echo $data->variable_value;	} ?>" placeholder="ระบุ">
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("variable_value"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">sql</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea type="text" name="sql_text" cols="4" rows="5" class="form-control" placeholder="ระบุ"><?php if (isset($data->sql_text)) { echo $data->sql_text;	} ?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("sql_text"); ?></label>
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
<script type="text/javascript">
    function deletePicture() {
        $('input[name=profile_picture_tmp]').val('');
        $('#preview_picture').attr('src', '<?php echo $profile_picture_default; ?>');
    }

    jQuery(document).ready(function () {
        jQuery("#profile_picture").change(function () {

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    jQuery('#preview_picture').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
