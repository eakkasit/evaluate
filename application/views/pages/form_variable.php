<?php
$title_prefix = 'เพิ่ม';
$action = base_url("variable/save");
$prev = base_url("variable/dashboard_variable");
if (isset($data->var_id) && $data->var_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->var_id}";
	$prev = base_url("variable/view_variable/{$data->var_id}");
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
								<input type="text" name="var_name" class="form-control"
									   value="<?php if (isset($data->var_name)) { echo $data->var_name;	} ?>" placeholder="ระบุ">
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("var_name"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">หน่วยวัด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="var_unit_id">
									<?php foreach ($units_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>" <?php if (isset($data->var_unit_id) && $data->var_unit_id == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("var_unit_id"); ?></label>
							</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชนิดของการแสดงผล</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="var_type_id">
									<?php foreach ($show_type_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->var_type_id) && $data->var_type_id == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("var_type_id"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ประเภทการนำเข้าข้อมูล</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php foreach ($field_type_list as $key => $value) { ?>
									<input type="radio" name="var_import_id"
											 id="var_import_id_<?php echo $key; ?>"
											 class=""
											 value="<?php echo $key; ?>" <?php if (isset($data->var_import_id) && $data->var_import_id == $key) {
										echo 'checked="checked"';
									} ?>>&nbsp;<label
										for="units_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
								<?php } ?>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("var_import_id"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ค่าตัวแปร</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="var_value" class="form-control" id="var_value_input"  value="<?php if (isset($data->var_value)) { echo $data->var_value;	} ?>" placeholder="ระบุ">
								<select name="var_value" id="var_value_select" class="form-control" style="display:none">
									<?php
										if(isset($variable_system) && !empty($variable_system)){
											foreach ($variable_system as $key => $value) {
												$select = "";
												if (isset($data->var_value) && $data->var_value == $key) {
													$select = 'selected';
												}
												?>
												<option value="<?php echo $key; ?>" <?php echo $select; ?>><?php echo $value; ?></option>
												<?php
											}
										}
									?>
								</select>
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("var_value"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ความยาวตัวอักษร/จำนวนทศนิยม</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="var_max_length" class="form-control"  value="<?php if (isset($data->var_max_length)) { echo $data->var_max_length;	} ?>" placeholder="ระบุ">
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("var_max_length"); ?></label>
						</div>

						<!-- sbs hide -->
						<div class="row hide">
							<div class="col-md-12">
								<label for="stext">sql</label>
							</div>
						</div>
						<div class="row hide">
							<div class="col-md-12">
								<textarea type="text" name="var_sql" cols="4" rows="5" class="form-control" placeholder="ระบุ"><?php if (isset($data->var_sql)) { echo $data->var_sql;	} ?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("var_sql"); ?></label>
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

    jQuery(document).ready(function () {
				var var_value = jQuery("input[name=var_import_id]:checked").val();
				if(var_value == 2){
					$('#var_value_input').hide()
					$('#var_value_select').show()
				}else{
					$('#var_value_input').show()
					$('#var_value_select').hide()
				}

        jQuery("input[name=var_import_id]").change(function () {
					if($(this).val() == 2){
						$('#var_value_input').hide()
						$('#var_value_select').show()
					}else{
						$('#var_value_input').show()
						$('#var_value_select').hide()
					}
        });
    });
</script>
