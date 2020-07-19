<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> วาระการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">

					<a href="<?php echo base_url("agendas/dashboard_agendas"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<?php if (in_array(strtolower($meeting_data->meeting_status), array('draft'))) { ?>
					<a href="#"
					   class="table-link"
					   onclick="pending_meeting(<?php echo $meeting_data->meeting_id; ?>);" title="ดำเนินการต่อ">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-play"></i> ดำเนินการต่อ
						</button></a>
					<?php } ?>

				</div>
			</div>

			<div class="row">

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">ชื่อการประชุม :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo $meeting_data->meeting_name; ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">โครงการ :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo $meeting_data->meeting_project; ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">รายละเอียดการประชุม :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo $meeting_data->meeting_description; ?>&nbsp;</label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">วันที่ประชุม :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo date_thai($meeting_data->meeting_date, false); ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">เวลาเริ่ม :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo time_thai($meeting_data->meeting_starttime); ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">เวลาสิ้นสุด :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo time_thai($meeting_data->meeting_endtime); ?></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">ห้องประชุม :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo $meeting_data->meeting_room; ?>&nbsp;</label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 text-right">
						<label for="stext">สถานะ :</label>
					</div>
					<div class="col-md-8 text-left">
						<label for="stext"><?php echo $status_list[$meeting_data->meeting_status]; ?></label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center" width="7%">วาระที่</th>
			<th class="text-center" width="15%">เรื่อง</th>
			<th class="text-center" width="10%">ประเภทวาระ</th>
			<th class="text-center" width="15%">เรื่องเดิม</th>
			<th class="text-center" width="23%">เนื้อหา</th>
			<th class="text-center" width="10%">เอกสารแนบ</th>
			<th class="text-center" width="20%">
				<a href="<?php echo base_url("agendas/new_agenda/{$meeting_data->meeting_id}"); ?>"
				   class="table-link" title="เพิ่มวาระการประชุม">
					<button type="button" class="btn btn-xs btn-primary">
						<i class="fa fa-edit"></i> เพิ่มวาระการประชุม
					</button>
				</a>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$edit = base_url("agendas/edit_agenda/{$meeting_data->meeting_id}");
		if (isset($agendas) && !empty($agendas)) {
			foreach ($agendas as $agenda) {
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php echo number_format($agenda->agenda_no, 0); ?>
					</td>
					<td class="text-left">
						<?php echo $agenda->agenda_name; ?>
					</td>
					<td class="text-center">
						<?php echo $type_list[$agenda->agenda_type_id]; ?>
					</td>
					<td class="text-left">
						<?php echo $agenda->agenda_story; ?>
					</td>
					<td class="text-left">
						<?php echo $agenda->agenda_detail; ?>
					</td>
					<td class="text-center">
						<?php
						if (isset($files[$agenda->agenda_id]) && !empty($files[$agenda->agenda_id])) {
							foreach ($files[$agenda->agenda_id] as $key => $file) {
								if ($key > 0) {
									echo '<br/>';
								}
								?>
								<a href="<?php echo base_url("assets/attaches/{$meeting_data->meeting_id}/{$file->agenda_filename}"); ?>"
								   target="_blank"
								   class="table-link label label-<?php echo class_file_type($file->agenda_filename); ?>"><?php echo $file->agenda_detail; ?></a>
								<?php
							}
						}
						?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo "{$edit}/{$agenda->agenda_id}"; ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<a href="#"
							   class="table-link"
							   onclick="deleteAgenda(<?php echo "{$meeting_data->meeting_id}, {$agenda->agenda_id}"; ?>);"
							   title="ลบ">
								<button type="button" class="btn btn-xs btn-danger">
									<i class="fa fa-trash-o"></i> ลบ
								</button></a>

						</div>
					</td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr class="odd" role="row">
				<td class="text-center" colspan="7">ไม่มีข้อมูลวาระการประชุมให้แสดง</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	<?php if (in_array(strtolower($meeting_data->meeting_status), array('draft'))) { ?>
    function pending_meeting(meeting_id) {
        swal({
                title: "แจ้งเตือน",
                text: "บันทึกข้อมูลเสร็จสิ้นทำการดำเนินการต่อ",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ดำเนินการต่อ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("datas/status_data/"); ?>' + meeting_id + '/pending';
                }
            });
    }
    <?php } ?>

    function deleteAgenda(meeting_id, agenda_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการลบวาระการประชุมนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("agendas/delete_agenda"); ?>' + meeting_id + '/' + agenda_id;
                }
            });
    }
</script>
