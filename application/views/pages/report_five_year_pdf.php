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
table#table_five_year td {
 border: 1px solid black;
 border-collapse: collapse;
 padding: 4px;
}
</style>
<table border="1" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="15">
			<center>
				<strong>
					<?php echo thai_number("รายงานโครงการ5ปี"); ?>
				</strong>
			</center>
      <br/>
      <br/>
		</td>
	</tr>
  <tr>
		<td>
			<table  border="1" cellspacing="0" id="table_five_year" cellpadding="0" width="100%">
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
									for ($j=0; $j < 5; $j++) {
										?>
										<td class="text-center">
											<?php echo isset($data[$project->id][$project->year+$j])?number_format($data[$project->id][$project->year+$j]):''; ?>
										</td>
										<?php
									}
									for ($k=0; $k < 5; $k++) {
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
		</td>
	</tr>
</table>
</body>
</html>
