<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center" width="7%">ลำดับ</th>
			<th class="text-center" width="8%">สถานะ</th>
			<th class="text-center" width="19%">ชื่อองค์คณะประชุม</th>
			<th class="text-center" width="12%">สร้างเมื่อ</th>
			<th class="text-center" width="30%">รายละเอียด</th>
			<th class="text-center" width="24%">
				<a href="<?php echo base_url("groups/new_group"); ?>" title="เพิ่ม">
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
		if (isset($groups) && !empty($groups)) {
			foreach ($groups as $key => $group) {
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php echo number_format($no + $key, 0); ?>
					</td>
					<td class="text-center">
						<?php echo $status_list[$group->group_status]; ?>
					</td>
					<td class="text-center">
						<?php echo $group->group_name; ?>
					</td>
					<td class="text-center">
						<?php echo date_thai($group->create_date, true, false); ?>
					</td>
					<td class="text-left">
						<?php echo $group->group_description; ?>
					</td>
					<td class="text-center white">
						<div>

							<a href="<?php echo base_url("groups/view_group/{$group->group_id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<a href="<?php echo base_url("groups/edit_group/{$group->group_id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<?php if (in_array(strtolower($group->group_status), array('active'))) { ?>
								<a href="#"
								   class="table-link"
								   onclick="delete_group(<?php echo $group->group_id; ?>);"
								   title="ระงับ">
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
</script>
