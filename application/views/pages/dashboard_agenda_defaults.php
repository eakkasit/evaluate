<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="5%">ลำดับ</th>
			<th class="text-center" width="20%">หัวเรื่อง</th>
			<th class="text-center" width="25%">เรื่องเดิม</th>
			<th class="text-center" width="25%">เนื้อหาวาระ</th>
			<th class="text-center" width="25%">
				<a href="<?php echo base_url("agenda_defaults/new_agenda_default"); ?>" title="เพิ่ม">
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
		if (isset($agenda_defaults) && !empty($agenda_defaults)) {
			foreach ($agenda_defaults as $key => $agenda_default) {
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php echo number_format($no + $key, 0); ?>
					</td>
					<td class="text-left">
						<?php echo $agenda_default->agenda_default_name; ?>
					</td>
					<td class="text-left">
						<?php echo $agenda_default->agenda_default_story; ?>
					</td>
					<td class="text-left">
						<?php echo $agenda_default->agenda_default_detail; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("agenda_defaults/view_agenda_default/{$agenda_default->agenda_default_id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<a href="<?php echo base_url("agenda_defaults/edit_agenda_default/{$agenda_default->agenda_default_id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<a href="#"
								class="table-link"
								onclick="delete_agenda_default(<?php echo $agenda_default->agenda_default_id; ?>);" title="ลบ">
								<button type="button" class="btn btn-xs btn-danger">
									<i class="fa fa-trash-o"></i> ลบ
								</button></a>
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
    function delete_agenda_default(agenda_default_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการลบค่าเริ่มต้นวาระการประชุม",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("agenda_defaults/delete_agenda_default/"); ?>' + agenda_default_id;
                }
            });
    }
</script>
