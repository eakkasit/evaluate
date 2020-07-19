<?php
$action = base_url("datas/save_present/{$meeting_id}");
$prev = base_url("datas/view_data/{$meeting_id}");
?>
<p class="h4 header text-success">
	<i class="fa fa-users"></i> จัดการผู้เข้าร่วมประชุม
</p>

<?php $this->load->view("template/search_group"); ?>

<div class="table-responsive">
	<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
		<table role="grid" id="table-example"
			   class="table table-bordered table-hover dataTable no-footer">
			<thead>
			<tr role="row">
				<th class="text-center start_no" width="7%"></th>
				<th class="text-center" width="33%">ชื่อ นามสกุล</th>
				<th class="text-center" width="17%">ตำแหน่ง</th>
				<th class="text-center" width="17%">หน่วยงาน</th>
				<th class="text-center" width="26%">
					<a href="<?php echo $prev; ?>" class="btn btn-sm btn-danger">
						<i class="fa fa-times"></i>
						ยกเลิก
					</a>

					<button type="submit" class="btn btn-sm btn-success" title="บันทึก">
						<i class="fa fa-floppy-o"></i> บันทึก
					</button>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
			$tmp_group = null;
			if (isset($users_list) && !empty($users_list)) {
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
							<select name="user_type_id[<?php echo $user->group_id; ?>][<?php echo $user->user_id; ?>]"
									id="user_type_id_<?php echo $user->group_id . $user->user_id . $key; ?>"
									class="form-control">
								<?php foreach ($status_list as $key => $value) { ?>
									<option
										value="<?php echo $key; ?>"<?php if (isset($user->user_type_id) && $user->user_type_id == $key) {
										echo 'selected="selected"';
									} ?>><?php echo $value; ?></option>
								<?php }
								?>
							</select>
						</td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
		</table>
	</form>
</div>
<div class="pagination pull-right">
	<?php $this->load->view("template/pagination"); ?>
</div>

