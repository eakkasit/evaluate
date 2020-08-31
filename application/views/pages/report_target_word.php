<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=รายงานเป้าหมายโครงการ.doc");
?>
<html>
<body>
<table border="1" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="6">
			<center>
				<strong>
					<?php echo thai_number("รายงานเป้าหมายโครงการ"); ?>
				</strong>
			</center>
		</td>
	</tr>
	<tr>
		<td>ลำดับ</td>
		<td>ชื่อโครงการ</td>
		<td>ปีงบประมาณ</td>
		<td class="text-center" width="35px">เป้าหมายปี <?php echo $year; ?></td>
		<td class="text-center" width="35px" >ผลการประเมิน</td>
		<td class="text-center" width="35px" >ร้อยละความสำเร็จ</td>
	</tr>
	<?php
		$no = 0;
		if (isset($project_list) && !empty($project_list)) {
			foreach ($project_list as $key => $project) {
				$no++;
				?>
				<tr>
					<td class="text-left"><?php echo $no ?></td>
					<td class="text-left"><?php echo $project->project_name ?></td>
					<td><?php echo $project->year ; ?></td>
					<td class="text-center" ><?php echo isset($data[$project->id][$year])?number_format($data[$project->id][$year],2):'' ?></td>
					<td class="text-center" ><?php echo isset($project->result)?number_format($project->result,2):''; ?></td>
					<td class="text-center" >
						<?php
							if(isset($data[$project->id][$year]) && isset($project->result)){
								if($data[$project->id][$year] != 0){
									$percent =  ($project->result/$data[$project->id][$year]) * 100;
								}else{
									$percent = 0;
								}
								echo number_format($percent,2);
							}else{
								echo "";
							}
						?>
					</td>
				</tr>
				<?php
			}
		}
	?>
</table>
</body>
</html>
