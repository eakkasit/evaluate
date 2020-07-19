<?php
$title_prefix = 'เพิ่ม';
$action = base_url("users/save");
$prev = base_url("users/dashboard_users");
$profile_picture_default = base_url("assets/images/no_images.jpg");
$profile_picture = $profile_picture_default;
$btn_img_txt = 'เพิ่มรูป';
$btn_img_dsp = false;
if (isset($user_data->user_id) && $user_data->user_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$user_data->user_id}";
	$prev = base_url("users/view_user/{$user_data->user_id}");
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
					<div class="col-md-4">
						<div class="form-group text-center">
							<img id="preview_picture" class="col-md-12 img-thumbnail img-custom"
								 src="<?php echo $profile_picture; ?>" draggable="false"/>
							<input type="file" id="profile_picture" name="profile_picture"
								   class="form-control"
								   accept="image/*"
								   style="display: none;"
								   value=""/>
							<input type="hidden" name="profile_picture_tmp"
								   value="<?php if (isset($user_data->profile_picture_tmp) && $user_data->profile_picture_tmp != '') {
									   echo $user_data->profile_picture_tmp;
								   } else if (isset($user_data->profile_picture) && $user_data->profile_picture != '') {
									   echo $user_data->profile_picture;
								   } ?>"/>

							<div style="clear: both;"></div>
							<br/>

							<?php if ($btn_img_dsp) { ?>
								<button type="button" onclick="deletePicture();" class="btn btn-sm btn-danger">
									<i class="fa fa-trash"></i>
									ลบรูปภาพ
								</button>
							<?php } ?>

							<button type="button" onclick="profile_picture.click();" class="btn btn-sm btn-primary">
								<i class="fa fa-camera"></i>
								<?php echo $btn_img_txt; ?>
							</button>
							<label class="col-md-12 text-danger"><?php if (isset($upload_msg)) {
									echo $upload_msg;
								} ?></label>
						</div>
					</div>

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">เลขบัตรประชาชน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="citizen_id" class="form-control"
									   value="<?php if (isset($user_data->citizen_id)) {
										   echo $user_data->citizen_id;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("citizen_id"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">คำนำหน้าชื่อ <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<select name="prename" class="form-control">
									<?php foreach ($prefix_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($user_data->prename) && $user_data->prename == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php }
									?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("prename"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อ <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="name" class="form-control"
									   value="<?php if (isset($user_data->name)) {
										   echo $user_data->name;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("name"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">นามสกุล <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="surname" class="form-control"
									   value="<?php if (isset($user_data->surname)) {
										   echo $user_data->surname;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("surname"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">เพศ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php foreach ($gender_list as $key => $value) { ?>
									<input type="radio" name="gender"
										   id="gender_<?php echo $key; ?>" class=""
										   value="<?php echo $key; ?>" <?php if (isset($user_data->gender) && $user_data->gender == $key) {
										echo 'checked="checked"';
									} ?>>&nbsp;<label
										for="gender_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
								<?php } ?>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("gender"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ตำแหน่ง</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="position_code" class="form-control"
									   value="<?php if (isset($user_data->position_code)) {
										   echo $user_data->position_code;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("position_code"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ระดับ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="level_code" class="form-control"
									   value="<?php if (isset($user_data->level_code)) {
										   echo $user_data->level_code;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("level_code"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">สังกัด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="department" class="form-control"
									   value="<?php if (isset($user_data->department)) {
										   echo $user_data->department;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("department"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">อีเมล <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="email" name="email" class="form-control"
									   value="<?php if (isset($user_data->email)) {
										   echo $user_data->email;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("email"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">หมายเลขโทรศัพท์ <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="telephone" class="form-control"
									   value="<?php if (isset($user_data->telephone)) {
										   echo $user_data->telephone;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("telephone"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">สถานะผู้ใช้ระบบประชุม</label>
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
