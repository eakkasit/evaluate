<?php
$title_prefix = 'เพิ่ม';
$action = base_url("config_variables/save");
$prev = base_url("config_variables/dashboard_config_variables");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("config_variables/view_config_variables/{$data->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>ตัวแปรจากระบบ
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อตัวแปร</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="name" class="form-control"
									   value="<?php if (isset($data->name)) { echo $data->name;	} ?>" placeholder="ระบุ">
							</div>
							<label 	class="col-md-12 text-danger"><?php echo form_error("name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">sql</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea type="text" name="sql" cols="4" rows="5" class="form-control" placeholder="ระบุ"><?php if (isset($data->sql)) { echo $data->sql;	} ?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("sql"); ?></label>
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
