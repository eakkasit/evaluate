<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> รายงานการเข้าประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">

					<a href="<?php echo base_url("report_attends/view_record_report/{$meeting_data->meeting_id}"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("report_attends/export_attend/{$meeting_data->meeting_id}/{$agenda_id}/pdf"); ?>"
					   class="table-link" title="พิมพ์ PDF" target="_blank">
						<button type="button" class="btn btn-xs btn-danger">
							<i class="fa fa-file-pdf-o"></i> PDF
						</button></a>

					<a href="<?php echo base_url("report_attends/export_attend/{$meeting_data->meeting_id}/{$agenda_id}/word"); ?>"
					   class="table-link" title="ส่งออก Word" target="_blank">
						<button type="button" class="btn btn-xs btn-primary">
							<i class="fa fa-file-word-o"></i> Word
						</button></a>

					<a href="<?php echo base_url("report_attends/export_attend/{$meeting_data->meeting_id}/{$agenda_id}/excel"); ?>"
					   class="table-link" title="ส่งออก Excel" target="_blank">
						<button type="button" class="btn btn-xs btn-success">
							<i class="fa fa-file-excel-o"></i> Excel
						</button></a>

				</div>
			</div>

			<div class="row">
				<div class="col-xs-12 col-md-offset-2 col-md-8 text-center">
					<table class="table borderless">
					<tr>
							<td class="text-center h4 strong" colspan="4">
								<?php echo thai_number("รายงานการประชุม{$meeting_data->meeting_name}"); ?>
							</td>
						</tr>
						<tr>
							<td class="text-center h4 strong" colspan="4">
								<?php echo thai_number('วันที่ ' . date_thai($meeting_data->meeting_date, false) . ' เวลา ' . time_thai($meeting_data->meeting_starttime, false) . ' ถึง ' . time_thai($meeting_data->meeting_endtime)); ?>
							</td>
						</tr>
						<tr>
							<td class="text-center h4 strong" colspan="4">
								<?php echo thai_number("ณ ห้องประชุม{$meeting_data->meeting_room}"); ?>
							</td>
						</tr>
						<tr>
							<td class="text-center h5" colspan="4">
								.........................................................................
							</td>
						</tr>
						<tr>
							<td class="text-left h4 strong" colspan="4">
								ผู้เข้าร่วมประชุม
							</td>
						</tr>
						<tr>
							<td>ลำดับที่</td>
							<td>ชื่อ - นามสกุล</td>
							<td>เวลาเข้าประชุม</td>
							<td>เวลาออกจากการประชุม</td>
						</tr>
						<?php 
							$i = 0;
							foreach ($report as $key => $value) {
								$i++;
								?>
								<tr>
									<td class="text-left"><?php echo $i ?></td>
									<td class="text-left"><?php echo $prefix_list[$value->prename].$value->name." ".$value->surname ?></td>
									<td><?php echo $value->time_in ?></td>
									<td><?php echo $value->time_out ?></td>
								</tr>
								<?php
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


