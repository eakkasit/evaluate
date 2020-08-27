<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=รายงานเป้าหมายโครงการ.xls");
?>
<html>
<body>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4">
			<center>
				<strong>
					<?php echo tdai_number("รายงานเป้าหมายโครงการ"); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr role="row">
		<td class="text-center start_no" widtd="5%" rowspan="2">ลำดับ</td>
		<td class="text-center" widtd="35%" rowspan="2">ชื่อโครงการ</td>
		<td class="text-center" widtd="10%" rowspan="2">ระยะเวลาดำเนินการ</td>
		<td class="text-center" widtd="25%" colspan="5">ค่าน้ำหนักแต่ละปีงบประมาณ</td>
		<td class="text-center" widtd="25%" colspan="5">คะแนนแต่ละปีงบประมาณ</td>
	</tr>
	<tr>
		<?php
			$year = (date('Y')+543);
			for ($i=0; $i < 5; $i++) {
				?>
				<td class="text-center"><?php echo $year+$i; ?></td>
				<?php
			}
			$year = (date('Y')+543);
			for ($i=0; $i < 5; $i++) {
				?>
				<td class="text-center"><?php echo $year+$i; ?></td>
				<?php
			}
		?>
	</tr>
	<?php
		$i = 0;
		if (isset($project_list) && !empty($project_list)) {
			foreach ($project_list as $key => $project) {
				$i++;
				?>
				<tr class="odd" role="row">
					<td class="text-center">
						<?php
						echo number_format($i, 0);
						?>
					</td>
					<td class="text-left">
						<?php echo $project->project_name; ?>
					</td>
					<td class="text-left">
						<?php echo $project->year; ?>
					</td>
					<?php
						for ($i=0; $i < 5; $i++) {
							?>
							<td class="text-center">
								<?php echo isset($data[$project->id][$project->year+$i])?number_format($data[$project->id][$project->year+$i]):''; ?>
							</td>
							<?php
						}
						for ($i=0; $i < 5; $i++) {
							?>
							<td class="text-center">
								<?php //echo isset($data[$project->id][$project->year+$i])?number_format($data[$project->id][$project->year+$i]):''; ?>
							</td>
							<?php
						}
					?>
				</tr>
				<?php
			}
		}
	?>
</table>
</body>
</html>
