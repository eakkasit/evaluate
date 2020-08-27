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
<table border="1" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="18">
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
		<?php
			if($year_show){
				for($i = 0;$year_start+$i<=$year_end;$i++){
					?>
					<td class="text-center" width="35px">เป้าหมายปี <?php echo $year_start+$i; ?></td>
					<td class="text-center" width="35px" >ผลการประเมิน</td>
					<td class="text-center" width="35px" >ร้อยละความสำเร็จ</td>
					<?php
				}
			}
		?>
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
					<?php
						if($year_show){
							for($i = 0;$year_start+$i<=$year_end;$i++){
								?>
								<td class="text-center" ><?php echo isset($data[$project->id][$year_start+$i])?number_format($data[$project->id][$year_start+$i],2):'' ?></td>
								<td class="text-center" ><?php echo isset($project->result)?number_format($project->result,2):''; ?></td>
								<td class="text-center" >
									<?php
										if(isset($data[$project->id][$year_start+$i]) && isset($project->result)){
											if($data[$project->id][$year_start+$i] != 0){
												$percent =  ($project->result/$data[$project->id][$year_start+$i]) * 100;
											}else{
												$percent = 0;
											}
											echo number_format($percent,2);
										}else{
											echo "";
										}
									?>
								</td>
								<?php
							}
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
