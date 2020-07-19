<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> องค์คณะประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">

					<a href="<?php echo base_url("groups/dashboard_groups"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("groups/edit_group/{$group_data->group_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php if (in_array(strtolower($group_data->group_status), array('active'))) { ?>
					<a href="#"
					   class="table-link"
					   onclick="delete_group(<?php echo $group_data->group_id; ?>);" title="ระงับ">
						<button type="button" class="btn btn-xs btn-danger">
							<i class="fa fa-trash-o"></i> ระงับ
						</button></a>
					<?php } ?>

				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">ชื่อองค์คณะการประชุม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo $group_data->group_name; ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">สถานะองค์คณะการประชุม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext">
						<?php echo $status_list[$group_data->group_status]; ?>
					</label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">สร้างเมื่อ :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo date_thai($group_data->create_date); ?>&nbsp;</label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">รายละเอียด :</label>
				</div>
				<div class="col-md-8 text-left">
					<label for="stext"><?php echo $group_data->group_description; ?></label>
				</div>
			</div>

		</div>
	</div>
</div>

<p class="h4 header text-success">
	<i class="fa fa-user"></i> บัญชีรายชื่อผู้ประชุม
</p>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center" width="7%">ลำดับ</th>
			<th class="text-center" width="10%"></th>
			<th class="text-center" width="43%">ชื่อ นามสกุล</th>
			<th class="text-center" width="20%">ตำแหน่ง</th>
			<th class="text-center" width="20%">สังกัด</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if (isset($users_list) && !empty($users_list)) {
			$no = 1;
			if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
			foreach ($users_list as $key => $user) {
				$array_tmp[$user->user_id] = $user->user_id;
				$profile_picture = base_url("assets/images/no_images.jpg");
				if (isset($user->profile_picture) && $user->profile_picture != '') {
					$profile_picture = base_url("assets/uploads/{$user->profile_picture}");
				}
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php echo number_format(($no + $key), 0); ?>
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
				<?php
			}
		} else {
			?>
			<tr class="odd" role="row">
				<td class="text-center" colspan="4">ไม่มีข้อมูลรายชื่อผู้ประชุมให้แสดง</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<div class="pagination pull-right">
	<?php $this->load->view("template/pagination"); ?>
</div>

<script type="text/javascript">
	<?php if (in_array(strtolower($group_data->group_status), array('active'))) { ?>
    function delete_group(group_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับองค์คณะประชุมนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("groups/delete_group/"); ?>' + group_id;
                }
            });
    }
    <?php } ?>
</script>

