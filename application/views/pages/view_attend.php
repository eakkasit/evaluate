<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> บันทึกการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("attends/dashboard_attends"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>
						&nbsp;&nbsp;
					<?php if (in_array(strtolower($meeting_data->meeting_status), array('pending'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="open_meeting();" title="เปิดการประชุม">
							<button type="button" class="btn btn-xs btn-success">
								<i class="fa fa-play"></i> เปิดการประชุม
							</button></a>
						<?php
					}
					if (in_array(strtolower($meeting_data->meeting_status), array('active'))) {
						?>
						<a href="#"
						   class="table-link"
						   onclick="close_meeting();" title="สิ้นสุดการประชุม">
							<button type="button" class="btn btn-xs btn-success">
								<i class="fa fa-pause"></i> สิ้นสุดการประชุม
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
			<th class="text-center" width="5%">วาระที่</th>
			<th class="text-center" width="14%">เรื่อง</th>
			<th class="text-center" width="8%">ประเภทวาระ</th>
			<th class="text-center" width="13%">เรื่องเดิม</th>
			<th class="text-center" width="22%">เนื้อหา</th>
			<th class="text-center" width="8%">เอกสารแนบ</th>
			<th class="text-center" width="10%">สถานะ</th>
			<th class="text-center" width="20%"></th>
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
					<td class="text-center">
						<?php
						if (!isset($agenda->conclusion) || $agenda->conclusion == '') {
							echo 'อยู่ระหว่างดำเนินการ';
						} else {
							echo 'สิ้นสุดวาระการประชุม';
						}
						?>
					</td>
					<td class="text-center white">
						<div>

							<a href="#"
							   onclick="meeting_records(<?php echo "{$meeting_data->meeting_id}, {$agenda->agenda_id}"; ?>);"
							   data-toggle="modal" data-target="#meeting_records" title="แสดงบันทึกการประชุม">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดงบันทึกการประชุม
								</button></a>

							<?php if (!isset($agenda->conclusion) || $agenda->conclusion == '') { ?>
							<a href="<?php echo base_url("attends/edit_attend/{$meeting_data->meeting_id}/{$agenda->agenda_id}"); ?>"
							   class="table-link" title="เข้าร่วมประชุม">
								<button type="button" class="btn btn-xs btn-primary">
									<i class="fa fa-briefcase"></i> เข้าร่วมประชุม
								</button></a>
							<?php } ?>

						</div>
					</td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr class="odd" role="row">
				<td class="text-center" colspan="8">ไม่มีข้อมูลวาระการประชุมให้แสดง</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>

<div id="meeting_records" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<p class="agenda-story"></p>
				<p class="agenda-detail"></p>
				<table role="grid" id="table-example"
					   class="table table-bordered table-hover dataTable no-footer">
					<thead>
					<tr role="row">
						<th class="text-center start_no" width="6%">ลำดับ</th>
						<th class="text-center" width="30%">ชื่อ นามสกุล</th>
						<th class="text-center" width="36%">บันทึกการประชุม</th>
						<th class="text-center" width="28%">ปรับปรุงข้อมูลล่าสุดเมื่อ</th>
					</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
					<i class="fa fa-times"></i>
					ปิด
				</button>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	<?php if (in_array(strtolower($meeting_data->meeting_status), array('pending'))) { ?>
    function open_meeting() {
        swal({
                title: "แจ้งเตือน",
                text: "เริ่มการประชุม",
                type: "info",
                showCancelButton: true,
                confirmButtonText: "เริ่ม",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("datas/status_data/{$meeting_data->meeting_id}/active"); ?>';
                }
            });
    }
	<?php
	}
	if (in_array(strtolower($meeting_data->meeting_status), array('active'))) {
	?>

    function close_meeting() {
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
                    location.href = '<?php echo base_url("datas/status_data/{$meeting_data->meeting_id}/complete"); ?>';
                }
            });
    }
	<?php } ?>

    function meeting_records(meeting_id, agenda_id) {
        jQuery('#meeting_records .modal-title').html('');
        jQuery('#meeting_records .modal-body .agenda-story').html('');
        jQuery('#meeting_records .modal-body .agenda-detail').html('');
        jQuery('#meeting_records .modal-body table>tbody').html('');

        jQuery.get('<?php echo base_url("records/get_meeting_records/"); ?>' + meeting_id + '/' + agenda_id, function (data) {
            record = jQuery.parseJSON(data);
            jQuery('#meeting_records .modal-title').html(record.agenda_name);
            jQuery('#meeting_records .modal-body .agenda-story').html(record.agenda_story);
            jQuery('#meeting_records .modal-body .agenda-detail').html(record.agenda_detail);
            if (record.records_list.length > 0) {
                jQuery.each(record.records_list, function (index, value) {
                    ud = new Date(value.update_date);
                    str_ud = ud.toLocaleDateString('th-TH', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                    }) + ' น.';
                    jQuery('#meeting_records .modal-body table>tbody').append('<tr class="odd" role="row">' +
                        '<td class="text-center">' + (index + 1).toLocaleString() + '</td>' +
                        '<td class="text-left">' + value.full_name + '</td>' +
                        '<td class="text-left">' + value.record_detail + '</td>' +
                        '<td class="text-center">' + str_ud + '</td>' +
                        '</tr>');
                });
            } else {
                jQuery('#meeting_records .modal-body table>tbody').append('<tr class="odd" role="row">' +
                    '<td class="text-center" colspan="4">ไม่มีข้อมูลบันทึกการประชุมให้แสดง</td>' +
                    '</tr>');
            }

        });
    }
</script>
