<?php
$title_prefix = 'เพิ่ม';
$action = base_url("groups/save");
$prev = base_url("groups/dashboard_groups");
if (isset($group_data->group_id) && $group_data->group_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$group_data->group_id}";
	$prev = base_url("groups/view_group/{$group_data->group_id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>องค์คณะประชุม
</p>

<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">

	<div id="search-filter" class="widget-box">
		<div class="widget-body">
			<div class="widget-main">
				<div class="row">
					<div class="col-md-12">
						<label for="stext">ชื่อองค์คณะการประชุม <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" name="group_name" class="form-control"
							   value="<?php if (isset($group_data->group_name)) {
								   echo $group_data->group_name;
							   } ?>" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger"><?php echo form_error("group_name"); ?></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">สถานะองค์คณะการประชุม <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php foreach ($status_list as $key => $value) { ?>
							<input type="radio" name="group_status"
								   id="group_status_<?php echo $key; ?>"
								   class=""
								   value="<?php echo $key; ?>" <?php if (isset($group_data->group_status) && $group_data->group_status == $key) {
								echo 'checked="checked"';
							} ?>>&nbsp;<label
								for="group_status_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
						<?php } ?>
					</div>
					<label
						class="col-md-12 text-danger"><?php echo form_error("group_status"); ?></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">รายละเอียด <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
								<textarea name="group_description" class="form-control" placeholder="ระบุ" rows="3"><?php
									if (isset($group_data->group_description)) {
										echo $group_data->group_description;
									}
									?></textarea>
					</div>
					<label
						class="col-md-12 text-danger"><?php echo form_error("group_description"); ?></label>
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
			</div>
		</div>
	</div>

	<?php
	$array_tmp = array();
	if (isset($users_list) && !empty($users_list)) {
		?>

		<p class="h4 header text-success">
			<i class="fa fa-user"></i> บัญชีรายชื่อผู้ประชุม
		</p>

		<div class="table-responsive">
			<table role="grid" id="table-example"
				   class="table table-bordered table-hover dataTable no-footer">
				<thead>
				<tr role="row">
					<th class="text-center" width="7%">
						<input type="checkbox" id="checkAll"/>
					</th>
					<th class="text-center" width="10%"></th>
					<th class="text-center" width="43%">ชื่อ นามสกุล</th>
					<th class="text-center" width="20%">ตำแหน่ง</th>
					<th class="text-center" width="20%">สังกัด</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($users_list as $user) {
					$array_tmp[$user->user_id] = $user->user_id;
					$profile_picture = base_url("assets/images/no_images.jpg");
					if (isset($user->profile_picture) && $user->profile_picture != '') {
						$profile_picture = base_url("assets/uploads/{$user->profile_picture}");
					}
					?>
					<tr class="odd" role="row">
						<td class="text-center">
							<input type="checkbox" name="users[]" class="checkChild"
								   value="<?php echo $user->user_id; ?>" <?php if (isset($group_data->group_id) && $group_data->group_id == $user->group_id) {
								echo 'checked="checked"';
							} ?>/>
						</td>
						<td class="text-center">
							<img class="col-xs-12 img-circle"
								 src="<?php echo $profile_picture; ?>" draggable="false"/>
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
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php
			if (isset($users_list_selected) && !empty($users_list_selected)) {
				foreach ($users_list_selected as $user) {
					if (isset($array_tmp[$user->user_id])) continue;
					?>
					<input type="hidden" name="users[]"
						   value="<?php echo $user->user_id; ?>"/>
					<?php
				}
			}
			?>
		</div>
		<div class="pagination pull-right">
			<?php $this->load->view("template/pagination"); ?>
		</div>

		<script>
            jQuery(document).ready(function () {
                $("#checkAll").click(function () {
                    $('input:checkbox[class=checkChild]').not(this).prop('checked', this.checked);
                });
            });
		</script>

	<?php } ?>

</form>
