<html>
<body>
<style>
table{
    font-family: "Garuda";
    font-size: 12pt;
}
p{
    text-align: justify;
}
h1{
    text-align: center;
}
</style>
<table border="0" cellspacing="0" cellpadding="0" width="100%"> 
<tr>
		<td colspan="4">
			<center>
				<strong>
					<?php echo thai_number("รายงานการเข้าประชุม{$meeting_data->meeting_name}"); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<center>
				<strong>
					<?php echo thai_number('วันที่ ' . date_thai($meeting_data->meeting_date, false) . ' เวลา ' . time_thai($meeting_data->meeting_starttime, false) . ' ถึง ' . time_thai($meeting_data->meeting_endtime)); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<center>
				<strong>
					<?php echo thai_number("ณ ห้องประชุม{$meeting_data->meeting_room}"); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<center>
				.........................................................................
			</center>
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
</body>
</html>
