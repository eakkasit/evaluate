<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_variables/save");
$prev = base_url("criteria_variables/dashboard_criteria_variables");
$profile_picture_default = base_url("assets/images/no_images.jpg");
$profile_picture = $profile_picture_default;
$btn_img_txt = 'เพิ่มรูป';
$btn_img_dsp = false;
if (isset($user_data->user_id) && $user_data->user_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$user_data->user_id}";
	$prev = base_url("criteria_variables/view_user/{$user_data->user_id}");
	if (isset($user_data->profile_picture_tmp) && $user_data->profile_picture_tmp != '') {
		$profile_picture = base_url("assets/uploads/{$user_data->profile_picture_tmp}");
		$btn_img_txt = 'แก้ไขรูป';
		$btn_img_dsp = true;
	} else if (isset($user_data->profile_picture) && $user_data->profile_picture != '') {
		$profile_picture = base_url("assets/uploads/{$user_data->profile_picture}");
		$btn_img_txt = 'แก้ไขรูป';
		$btn_img_dsp = true;
	}
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>รายชื่อผู้ประชุม
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
								<input type="text" name="citizen_id" class="form-control"
									   value="" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("citizen_id"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">รายละเอียด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea type="text" name="citizen_id" cols="4" rows="5" class="form-control" placeholder="ระบุ"></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("citizen_id"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">สถานะการใช้งาน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php foreach ($status_list as $key => $value) { ?>
									<input type="radio" name="user_status"
										   id="user_status_<?php echo $key; ?>"
										   class=""
										   value="<?php echo $key; ?>" <?php if (isset($user_data->user_status) && $user_data->user_status == $key) {
										echo 'checked="checked"';
									} ?>>&nbsp;<label
										for="user_status_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
								<?php } ?>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("user_status"); ?></label>
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
