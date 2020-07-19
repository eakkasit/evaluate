<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> การประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("datas/dashboard_datas"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("datas/edit_data/{$data_data->meeting_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php if (in_array(strtolower($data_data->meeting_status), array('draft', 'pending'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="delete_meeting(<?php echo $data_data->meeting_id; ?>);" title="ระงับ">
							<button type="button" class="btn btn-xs btn-danger">
								<i class="fa fa-trash-o"></i> ระงับ
							</button></a>
					<?php } ?>

				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">ชื่อการประชุม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo $data_data->meeting_name; ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">โครงการ :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo $data_data->meeting_project; ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">รายละเอียดการประชุม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo $data_data->meeting_description; ?>&nbsp;</label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">วันที่ประชุม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo date_thai($data_data->meeting_date, false); ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">เวลาเริ่ม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo time_thai($data_data->meeting_starttime); ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">เวลาสิ้นสุด :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo time_thai($data_data->meeting_endtime); ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">ห้องประชุม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo $data_data->meeting_room; ?>&nbsp;</label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">สถานะ :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo $status_list[$data_data->meeting_status]; ?></label>
				</div>
			</div>

		</div>
	</div>
</div>

<?php if (isset($users_list) && !empty($users_list)) { ?>
	<div>
		<p class="h4 header text-success">
			<i class="fa fa-users"></i> องค์คณะการประชุม
		</p>

		<div class="table-responsive">
			<table role="grid" id="table-example"
				   class="table table-bordered table-hover dataTable no-footer">
				<thead>
				<tr role="row">
					<th class="text-center start_no" width="7%"></th>
					<th class="text-center" width="33%">ชื่อ นามสกุล</th>
					<th class="text-center" width="18%">ตำแหน่ง</th>
					<th class="text-center" width="18%">หน่วยงาน</th>
					<th class="text-center" width="24%">
						<a href="<?php echo base_url("datas/edit_present/{$data_data->meeting_id}"); ?>"
						   class="table-link" title="จัดการผู้เข้าร่วมประชุม">
							<button type="button" class="btn btn-xs btn-primary">
								<i class="fa fa-th-list"></i> จัดการผู้เข้าร่วมประชุม
							</button>
						</a>
					</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$no = 1;
				if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
				$tmp_group = null;
				foreach ($users_list as $key => $user) {
					if ($tmp_group != $user->group_id) {
						$group_name = 'ไม่ระบุ';
						$group_url = '#';
						if (isset($groups_list[$user->group_id])) {
							$group_name = $groups_list[$user->group_id]->group_name;
							$group_url = base_url("groups/view_group/{$user->group_id}");
						} else if ($user->group_id == 0) {
							$group_name = 'ผู้ประชุมภายนอก';
						}
						?>
						<tr class="odd" role="row">
							<td class="text-center" colspan="5">
								<a href="<?php echo $group_url; ?>"
								   class="table-link h4" title="<?php echo $group_name; ?>">
									<?php echo $group_name; ?>
								</a>
							</td>
						</tr>
						<?php
						$tmp_group = $user->group_id;
					}
					$profile_picture = base_url("assets/images/no_images.jpg");
					if (isset($user->profile_picture) && $user->profile_picture != '') {
						$profile_picture = base_url("assets/uploads/{$user->profile_picture}");
					}
					?>
					<tr class="odd" role="row">
						<td class="text-center">
							<?php
							/*<img class="col-xs-12 img-circle" src="<?php echo $profile_picture; ?>"
								 draggable="false"/>*/
							echo number_format($no + $key, 0);
							?>
						</td>
						<td class="text-left">
							<?php echo "{$prefix_list[$user->prename]} {$user->name}   {$user->surname}"; ?>
						</td>
						<td class="text-left">
							<?php echo $user->position_code; ?>
						</td>
						<td class="text-left">
							<?php echo $user->department; ?>
						</td>
						<td class="text-center">
							<?php echo $present_status_list[$user->user_type_id]; ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="pagination pull-right">
			<?php $this->load->view("template/pagination"); ?>
		</div>
	</div>
	<div style="clear: both;"></div>
<?php } ?>

<?php /*if (isset($users_temporary_list) && !empty($users_temporary_list)) { ?>
	<div>
		<p class="h4 header text-success">
			<i class="fa fa-user"></i> บัญชีรายชื่อผู้ประชุมภายนอก
		</p>

		<div class="table-responsive">
			<table role="grid" id="table-example"
				   class="table table-bordered table-hover dataTable no-footer">
				<thead>
				<tr role="row">
					<th class="text-center start_no" width="6%"></th>
					<th class="text-center" width="33%">ชื่อ นามสกุล</th>
					<th class="text-center" width="17%">ตำแหน่ง</th>
					<th class="text-center" width="17%">หน่วยงาน</th>
					<th class="text-center" width="15%">อีเมล</th>
					<th class="text-center" width="12%">โทรศัพท์</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($users_temporary_list as $key => $user) {
					$profile_picture = base_url("assets/images/no_images.jpg");
					if (isset($user->profile_picture) && $user->profile_picture != '') {
						$profile_picture = base_url("assets/uploads/{$user->profile_picture}");
					}
					?>
					<tr class="odd" role="row">
						<td class="text-center">
							<img class="col-xs-12 img-circle" src="<?php echo $profile_picture; ?>"
								 draggable="false"/>
						</td>
						<td class="text-left">
							<?php echo "{$prefix_list[$user->prename]} {$user->name}   {$user->surname}"; ?>
						</td>
						<td class="text-left">
							<?php echo $user->position_code; ?>
						</td>
						<td class="text-left">
							<?php echo $user->department; ?>
						</td>
						<td class="text-center">
							<?php echo $user->email; ?>
						</td>
						<td class="text-center">
							<?php echo phone_number($user->telephone); ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php }*/ ?>

<script type="text/javascript">
	<?php if (in_array(strtolower($data_data->meeting_status), array('draft', 'pending'))) { ?>
    function delete_meeting(meeting_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับการประชุมนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("datas/delete_data/"); ?>' + meeting_id;
                }
            });
    }
    <?php } ?>
</script>
