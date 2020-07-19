<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=รายงานการประชุม{$meeting_data->meeting_name}.doc");
?>
<html>
<body>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="3">
			<center>
				<strong>
					<?php echo thai_number("รายงานการประชุม{$meeting_data->meeting_name}"); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<center>
				<strong>
					<?php echo thai_number('วันที่ ' . date_thai($meeting_data->meeting_date, false) . ' เวลา ' . time_thai($meeting_data->meeting_starttime, false) . ' ถึง ' . time_thai($meeting_data->meeting_endtime)); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<center>
				<strong>
					<?php echo thai_number("ณ ห้องประชุม{$meeting_data->meeting_room}"); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<center>
				.........................................................................
			</center>
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
				<td width="30%">
					<?php echo thai_number("&emsp;&emsp;&emsp;" . number_format($no, 0) . ". {$prefix_list[$user->prename]} {$user->name}"); ?>
				</td>
				<td width="30%">
					<?php echo thai_number($user->surname); ?>
				</td>
				<td width="40%">
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
				<td width="30%">
					<?php echo thai_number("&emsp;&emsp;&emsp;" . number_format($no, 0) . ". {$prefix_list[$user->prename]} {$user->name}"); ?>
				</td>
				<td width="30%">
					<?php echo thai_number($user->surname); ?>
				</td>
				<td width="40%">
					<?php echo thai_number($user->position_code); ?>
				</td>
			</tr>
			<?php
			$no++;
		}
	}
	?>
	<tr>
		<td colspan="3">
			<strong>
				<?php echo thai_number("เริ่มประชุมเวลา " . time_thai($meeting_data->meeting_starttime)); ?>
			</strong>
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
				<td colspan="3">
							<span>
								<strong>
									<?php echo thai_number("วาระที่ " . number_format($agenda->agenda_no, 0)); ?>
								</strong>
							</span>
					<?php echo thai_number(" เรื่อง{$agenda->agenda_name}"); ?>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<?php echo thai_number("&emsp;&emsp;&emsp;{$agenda->agenda_detail}"); ?>
				</td>
			</tr>
			<?php
			if (isset($records[$agenda->agenda_id]) && !empty($records[$agenda->agenda_id])) {
				foreach ($records[$agenda->agenda_id] as $record) {
					?>
					<tr>
						<td colspan="3">
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
</body>
</html>
