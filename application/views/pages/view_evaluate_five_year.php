<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> รายชื่อผู้ประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("users/dashboard_users"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("users/edit_user/{$user_data->user_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php if (in_array(strtolower($user_data->user_status), array('active'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="delete_user(<?php echo $user_data->user_id; ?>);" title="ระงับ">
							<button type="button" class="btn btn-xs btn-danger">
								<i class="fa fa-trash-o"></i> ระงับ
							</button></a>
					<?php } ?>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<div class="form-group text-center">
						<?php
						$profile_picture = base_url("assets/images/no_images.jpg");
						if (isset($user_data->profile_picture) && $user_data->profile_picture != '') {
							$profile_picture = base_url("assets/uploads/{$user_data->profile_picture}");
						}
						?>
						<img class="col-md-12 img-thumbnail img-custom" src="<?php echo $profile_picture; ?>"
							 draggable="false"/>
					</div>
				</div>

				<div class="col-md-8">

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">เลขบัตรประชาชน :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $user_data->citizen_id; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ชื่อ นามสกุล :</label>
						</div>
						<div class="col-md-8 text-left">
							<label
								for="stext"><?php echo "{$prefix} {$user_data->name}   {$user_data->surname}"; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">เพศ :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $gender; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ตำแหน่ง :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $user_data->position_code; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ระดับ :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $user_data->level_code; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">สังกัด :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $user_data->department; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">อีเมล :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $user_data->email; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">หมายเลขโทรศัพท์ :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo phone_number($user_data->telephone); ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">สถานะผู้ใช้ระบบประชุม :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $status_list[$user_data->user_status]; ?></label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php if (in_array(strtolower($user_data->user_status), array('active'))) { ?>
    function delete_user(user_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับผู้ใช้งานนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("users/delete_user/"); ?>' + user_id;
                }
            });
    }
	<?php } ?>
</script>
