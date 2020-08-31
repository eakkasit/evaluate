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
					<th align="center" width="5%">ลำดับ</th>
					<th align="center" width="30%">ชื่อโครงการ</th>
					<th align="center" width="10%">ปีงบประมาณ</th>
					<th align="center" width="15%">ผู้รับผิดชอบ</th>
					<th align="center" width="20%">ผลการดำเนินงาน</th>
				</tr>
				<?php
				if (isset($datas) && !empty($datas)) {
					$no = 1;
					foreach ($datas as $key => $data) {
						?>
						<tr class="odd" role="row">
							<td align="center">
								<?php
								echo number_format($no + $key, 0);
								?>
							</td>
							<td align="left">
								<?php echo $data->project_name; ?>
							</td>
							<td align="left">
								<?php echo $data->year; ?>
							</td>
							<td align="center">
								<?php //echo $data->department; ?>
								-
							</td>
							<td align="right">
								<?php echo isset($data->result)?$data->result:''; ?>
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
