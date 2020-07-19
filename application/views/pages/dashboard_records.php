<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center" width="5%">ลำดับ</th>
			<th class="text-center" width="10%">สถานะ</th>
			<th class="text-center" width="20%">การประชุม</th>
			<th class="text-center" width="15%">วันที่ประชุม</th>
			<th class="text-center" width="30%">รายละเอียดการประชุม</th>
			<th class="text-center" width="20%"></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if (isset($meetings) && !empty($meetings)) {
			foreach ($meetings as $key => $meeting) {
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php echo number_format($key + 1, 0); ?>
					</td>
					<td class="text-center">
						<?php echo $status_list[$meeting->meeting_status]; ?>
					</td>
					<td class="text-left">
						<?php echo $meeting->meeting_name; ?>
					</td>
					<td class="text-center">
						<?php echo date_thai($meeting->meeting_date, false, false); ?>
					</td>
					<td class="text-left text-justify">
						<?php echo $meeting->agenda_name; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("records/view_record/{$meeting->meeting_id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<?php /*if ($meeting->agenda_id != '') { ?>
								<a href="<?php echo base_url("records/edit_record/{$meeting->meeting_id}/{$meeting->agenda_id}"); ?>"
								   class="table-link" title="บันทึกการประชุม">
									<button type="button" class="btn btn-xs btn-primary">
										<i class="fa fa-th-list"></i> บันทึกการประชุม
									</button></a>
							<?php } ?>

							<a href="<?php echo base_url("datas/edit_data/{$meeting->meeting_id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<a href="#"
							   class="table-link"
							   onclick="complete_meeting(<?php echo $meeting->meeting_id; ?>);" title="ระงับ">
								<button type="button" class="btn btn-xs btn-danger">
									<i class="fa fa-trash-o"></i> ระงับ
								</button></a>*/ ?>
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
    function complete_meeting(meeting_id) {
        swal({
                title: "แจ้งเตือน",
                text: "สิ้นสุดการประชุม",
                type: "info",
                showCancelButton: true,
                confirmButtonText: "สิ้นสุด",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("datas/status_data/"); ?>' + meeting_id + '/complete';
                }
            });
    }
</script>
