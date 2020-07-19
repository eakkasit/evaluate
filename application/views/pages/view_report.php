<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> บันทึกการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">

					<a href="<?php echo base_url("reports/dashboard_reports"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("reports/export/{$meeting_data->meeting_id}/pdf"); ?>"
					   class="table-link" title="พิมพ์ PDF" target="_blank">
						<button type="button" class="btn btn-xs btn-danger">
							<i class="fa fa-file-pdf-o"></i> PDF
						</button></a>

					<a href="<?php echo base_url("reports/export/{$meeting_data->meeting_id}/word"); ?>"
					   class="table-link" title="ส่งออก Word" target="_blank">
						<button type="button" class="btn btn-xs btn-primary">
							<i class="fa fa-file-word-o"></i> Word
						</button></a>

					<a href="<?php echo base_url("reports/export/{$meeting_data->meeting_id}/excel"); ?>"
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
							<td class="text-center h4 strong" colspan="3">
								<?php echo thai_number("รายงานการประชุม{$meeting_data->meeting_name}"); ?>
							</td>
						</tr>
						<tr>
							<td class="text-center h4 strong" colspan="3">
								<?php echo thai_number('วันที่ ' . date_thai($meeting_data->meeting_date, false) . ' เวลา ' . time_thai($meeting_data->meeting_starttime, false) . ' ถึง ' . time_thai($meeting_data->meeting_endtime)); ?>
							</td>
						</tr>
						<tr>
							<td class="text-center h4 strong" colspan="3">
								<?php echo thai_number("ณ ห้องประชุม{$meeting_data->meeting_room}"); ?>
							</td>
						</tr>
						<tr>
							<td class="text-center h5" colspan="3">
								.........................................................................
							</td>
						</tr>
						<tr>
							<td class="text-left h4 strong" colspan="3">
								ผู้เข้าร่วมประชุม
							</td>
						</tr>
						<?php
						if (isset($users_list) && !empty($users_list)) {
							$no = 1;
							foreach ($users_list as $user) {
								?>
								<tr>
									<td class="text-left h5" width="30%">
										<?php echo thai_number("&emsp;&emsp;&emsp;" . number_format($no, 0) . ". {$prefix_list[$user->prename]} {$user->name}"); ?>
									</td>
									<td class="text-left h5" width="30%">
										<?php echo thai_number($user->surname); ?>
									</td>
									<td class="text-left h5" width="40%">
										<?php echo thai_number($user->position_code); ?>
									</td>
								</tr>
								<?php
								$no++;
							}
						}
						if (isset($users_temporary_list) && !empty($users_temporary_list)) {
							$no++;
							foreach ($users_temporary_list as $user) {
								?>
								<tr>
									<td class="text-left h5" width="30%">
										<?php echo thai_number("&emsp;&emsp;&emsp;" . number_format($no, 0) . ". {$prefix_list[$user->prename]} {$user->name}"); ?>
									</td>
									<td class="text-left h5" width="30%">
										<?php echo thai_number($user->surname); ?>
									</td>
									<td class="text-left h5" width="40%">
										<?php echo thai_number($user->position_code); ?>
									</td>
								</tr>
								<?php
								$no++;
							}
						}
						?>
						<tr>
							<td class="text-left h4 strong" colspan="3">
								<?php echo thai_number("เริ่มประชุมเวลา " . time_thai($meeting_data->meeting_starttime)); ?>
							</td>
						</tr>
						<?php
						if (isset($users_list) && !empty($users_list)) {
							foreach ($users_list as $user) {
								if (strtolower($user->user_type_id) == 4) {
									?>
									<tr>
										<td class="text-left h5" colspan="3">
											<?php
											$name = "{$prefix_list[$user->prename]} {$user->name}   {$user->surname}";
											if ($user->position_code != '') {
												$name .= " {$user->position_code}";
											}
											echo thai_number("&emsp;&emsp;&emsp;{$name} เป็นประธานที่ประชุม กล่าวเปิดประชุมและดำเนินการประชุมตามระเบียบวาระ");
											?>
										</td>
									</tr>
									<?php
								}
							}
						}
						if (isset($agendas) && !empty($agendas)) {
							foreach ($agendas as $agenda) {
								?>
								<tr>
									<td class="text-left h5" colspan="3">
										<span
											class="h4 strong"><?php echo thai_number("วาระที่ " . number_format($agenda->agenda_no, 0)); ?></span>
										<?php echo thai_number(" เรื่อง{$agenda->agenda_name}"); ?>
									</td>
								</tr>
								<tr>
									<td class="text-left h5" colspan="3">
										<?php echo thai_number("&emsp;&emsp;&emsp;{$agenda->agenda_detail}"); ?>
									</td>
								</tr>
								<?php
								if (isset($records[$agenda->agenda_id]) && !empty($records[$agenda->agenda_id])) {
									foreach ($records[$agenda->agenda_id] as $record) {
										?>
										<tr>
											<td class="text-left h5" colspan="3">
												<?php
												$name = "{$prefix_list[$record->prename]} {$record->name}   {$record->surname}";
												echo thai_number("&emsp;&emsp;&emsp;{$name} กล่าว {$record->record_detail}");
												?>
											</td>
										</tr>
										<?php
									}
								}
							}
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


