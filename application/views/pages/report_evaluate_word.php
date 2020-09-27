<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=รายงานการประเมินผล.doc");
?>
<html>

<style>
 table#table_evaluate td {
  border: 1px solid black;
	border-collapse: collapse;
	padding: 4px;
}
</style>
<body>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td >
			<center>
				<strong>
					<?php echo thai_number("รายงานการประเมินผล"); ?>
				</strong>
			</center>
			<br/>
		</td>
	</tr>
	<tr>
		<td>
			<table border="1" id="table_evaluate"  cellspacing="0" cellpadding="0" width="100%">
        <tr role="row">
  				<th class="text-center start_no" width="5%">ลำดับ</th>
  				<th class="text-center" width="15%">ชื่อโครงการ</th>
  				<th class="text-center" width="5%">ปีงบประมาณ</th>
  				<th class="text-center" width="10%">ผู้รับผิดชอบ</th>
  				<th class="text-center" width="10%">วัตถุประสงค์</th>
  				<th class="text-center" width="10%">ผลการดำเนินโครงการ</th>
  				<th class="text-center" width="10%">ผลผลิต</th>
  				<th class="text-center" width="10%">ผลลัพธ์</th>
  				<th class="text-center" width="10%">ผลการประเมิน</th>
  			</tr>
        <?php
  			$no = 1;

  			if (isset($datas) && !empty($datas)) {
  				foreach ($datas as $key => $data) {
  					?>
  					<tr class="odd" role="row">
  						<td class="text-center">
  							<?php
  							echo number_format($no + $key, 0);
  							?>
  						</td>
  						<td class="text-left">
  							<?php echo $project_data[0]->project_name; ?>
  						</td>
  						<td class="text-left">
  							<?php echo $data->year+543; ?>
  						</td>
  						<td class="text-left"> -
  							<?php //echo $data->department; ?>
  						</td>
  						<td class="text-left"> -
  							<?php //echo $data->detail; ?>
  						</td>
  						<td class="text-left">
  							<?php echo $data->project_result; ?>
  						</td>
  						<td class="text-left">
  							<?php echo $data->product; ?>
  						</td>
  						<td class="text-left">
  							<?php echo $data->result; ?>
  						</td>
  						<td class="text-right">
  							<?php echo $data->assessment_results; ?>
  						</td>

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
