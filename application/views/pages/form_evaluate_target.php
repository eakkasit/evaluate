<?php
$title_prefix = 'เพิ่ม';
$action = base_url("evaluate_targets/save");
$prev = base_url("evaluate_targets/dashboard_evaluate_targets");
$btn_img_txt = 'เพิ่มรูป';
$btn_img_dsp = false;
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("evaluate_targets/view_evaluate_target/{$data->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>เป้าหมายโครงการ
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="name" class="form-control"
									   value="<?php echo isset($data->name)?$data->name:''; ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ปี</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<select class="form-control" name="year_start">
									<?php foreach ($year_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->year_start) && $data->year_start == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label class="col-md-1"> - </label>
							<div class="col-md-4">
								<select class="form-control" name="year_end">
									<?php foreach ($year_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->year_end) && $data->year_end == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label class="col-md-12 text-danger"><?php echo form_error("year_start"); ?></label>
							<label class="col-md-12 text-danger"><?php echo form_error("year_end"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">รายละเอียด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea type="text" name="detail" cols="4" rows="5" class="form-control" placeholder="ระบุ"></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("detail"); ?></label>
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

    });
</script>
