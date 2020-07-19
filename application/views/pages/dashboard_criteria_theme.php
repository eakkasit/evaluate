<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="8%">ลำดับ</th>
			<th class="text-center" width="17%">แม่แบบเกณฑ์การประเมิน</th>
			<th class="text-center" width="13%">วันที่สร้าง</th>
			<th class="text-center" width="12%">รายละเอียด</th>
			<th class="text-center" width="6%">สถานะ</th>
			<th class="text-center" width="24%">
				<a href="<?php echo base_url("criteria_themes/form_criteria_theme"); ?>" title="เพิ่ม">
					<button type="button" class="btn btn-sm btn-success">
						<i class="fa fa-plus"></i> เพิ่ม
					</button>
				</a>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$no = 1;
		if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
		if (isset($users) && !empty($users)) {
			foreach ($users as $key => $user) {
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
						<?php echo $user->email; ?>
					</td>
					<td class="text-center">
						<?php echo phone_number($user->telephone); ?>
					</td>
					<td class="text-center">
						<?php echo $status_list[$user->user_status]; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("users/view_user/{$user->user_id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<a href="<?php echo base_url("users/edit_user/{$user->user_id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<?php if (in_array(strtolower($user->user_status), array('active'))) { ?>
								<a href="#"
								   class="table-link"
								   onclick="delete_user(<?php echo $user->user_id; ?>);" title="ระงับ">
									<button type="button" class="btn btn-xs btn-danger">
										<i class="fa fa-trash-o"></i> ระงับ
									</button></a>
							<?php } ?>
						</div>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
<div class="pagination pull-right">
	<?php $this->load->view("template/pagination"); ?>
</div>

<script type="text/javascript">
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
</script>
