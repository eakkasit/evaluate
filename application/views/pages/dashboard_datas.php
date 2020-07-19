<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center" width="5%">ลำดับ</th>
			<th class="text-center" width="13%">ชื่อการประชุม</th>
			<th class="text-center" width="17%">รายละเอียดการประชุม</th>
			<th class="text-center" width="8%">วันที่ประชุม</th>
			<th class="text-center" width="9%">เวลาเริ่ม-เวลาสิ้นสุด</th>
			<th class="text-center" width="10%">ห้องประชุม</th>
			<th class="text-center" width="8%">สถานะ</th>
			<th class="text-center" width="30%">
				<a href="<?php echo base_url("datas/new_data"); ?>" title="เพิ่ม">
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
		if (isset($datas) && !empty($datas)) {
			foreach ($datas as $key => $data) {
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php echo number_format($no + $key, 0); ?>
					</td>
					<td class="text-center">
						<?php echo $data->meeting_name; ?>
					</td>
					<td class="text-left">
						<?php echo $data->meeting_description; ?>
					</td>
					<td class="text-center">
						<?php echo date_thai($data->meeting_date, false, false); ?>
					</td>
					<td class="text-center">
						<?php echo time_thai($data->meeting_starttime, false) . ' - ' . time_thai($data->meeting_endtime, false); ?>
					</td>
					<td class="text-left">
						<?php echo $data->meeting_room; ?>
					</td>
					<td class="text-center">
						<?php echo $status_list[$data->meeting_status]; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("datas/view_data/{$data->meeting_id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<?php if (in_array(strtolower($data->meeting_status), array('pending')) && $data->group_id != '') { ?>
								<a href="<?php echo base_url("datas/edit_data/{$data->meeting_id}"); ?>"
								   class="table-link" title="แก้ไข">
									<button type="button" class="btn btn-xs btn-warning">
										<i class="fa fa-edit"></i> แก้ไข
									</button></a>

								<?php if ($data->group_id != '') { ?>
									<a href="<?php echo base_url("datas/edit_present/{$data->meeting_id}"); ?>"
									   class="table-link" title="จัดการผู้เข้าร่วมประชุม">
										<button type="button" class="btn btn-xs btn-primary">
											<i class="fa fa-th-list"></i> จัดการผู้เข้าร่วมประชุม
										</button></a>
									<?php
								}
							}
							?>

							<?php if (in_array(strtolower($data->meeting_status), array('draft', 'pending'))) { ?>
								<a href="#"
								   class="table-link"
								   onclick="delete_meeting(<?php echo $data->meeting_id; ?>);" title="ระงับ">
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
</script>
