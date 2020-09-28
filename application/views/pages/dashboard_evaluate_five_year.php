<?php $this->load->view("template/search_year"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row" class="d-flex">
			<th class="text-center start_no " width="70px"  rowspan="2">ลำดับ</th>
			<th class="text-center " width="250px"  rowspan="2">ชื่อโครงการ</th>
			<th class="text-center" width="100px"  rowspan="2">ปีงบประมาณ</th>
			<th class="text-center" width="100px"  rowspan="2">น้ำหนักโครงการ</th>
			<?php
			for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
				if($i == 0){
					?>
					<th class="text-center" width="225px"  colspan="3">เป้าหมาย <?php echo $search_year_start+$i+543; ?></th>
					<?php
				}else{
					?>
					<th class="text-center" width="375px"  colspan="5">เป้าหมาย <?php echo $search_year_start+$i+543; ?></th>
					<?php
				}
				?>


				<th class="text-center" width="300px"  colspan="4">ผลการประเมิน</th>
				<th class="text-center" width="75px"  rowspan="2">ร้อยละความสำเร็จ</th>
			<?php } ?>
		</tr>
		<tr>
			<?php
				for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
					?>
					<th class="text-center">*เป้าหมายร้อยละ</th>
					<th class="text-center">น้ำหนักรายปี</th>

					<?php
					if($i == 0){
						?>
						<th class="text-center">คะแนนเต็ม</th>
						<?php
					}else{
						?>
						<th class="text-center">น้ำหนักรวม</th>
						<th class="text-center">คะแนนเต็ม(เดิม)</th>
						<th class="text-center">คะแนนเต็มใหม่</th>
						<?php
					}
					?>
					<th class="text-center">น้ำหนักที่ได้</th>
					<th class="text-center">คะแนนที่ได้</th>
					<th class="text-center">ส่วนต่างน้ำหนัก</th>
					<th class="text-center">ส่วนต่างคะแนน</th>
					<?php
				}
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		$no = 1;
		// if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
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
						<?php echo $data->project_name; ?>
					</td>
					<td class="text-left" >
						<?php
						if($data->year_start == $data->year_end){
							echo $data->year_start+543;
						}else{
							$year_start_show = $data->year_start+543;
							$year_end_show = $data->year_end+543;
							echo "$year_start_show - $year_end_show" ;
						}
						?>
					</td>
					<td class="text-right" >
						<?php echo $data->weight; ?>
					</td>
					<?php
						$weight = $data->weight;
						$weight_total = 0;
						$point_total = 0;
						for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
							$target = isset($target_data[$data->id][$search_year_start+$i])?number_format($target_data[$data->id][$search_year_start+$i],2):'';
							$result = isset($result_data[$data->id][$search_year_start+$i])?number_format($result_data[$data->id][$search_year_start+$i],2):'';
							$weight_per_year = '';
							if($weight != '' && $target != ''){
								$weight_per_year = number_format((($weight * $target)/100),2);
							}
							$evaluate_result = '';
							$weight_actual = '';
							$weight_diff = '';
							$result_diff = '';
							if($target != '' && $result != ''){
								if($target != 0){
									$weight_actual = number_format((($result * $weight_per_year)/$target),2);
								}
								$result_diff = number_format(($target - $result),2);

							}

							if($weight_per_year != '' && $weight_actual != ''){
								$weight_diff = number_format(($weight_per_year - $weight_actual),2);

							}

							?>
							<td class="text-center"><?php echo $target; ?></td>
							<td class="text-center"><?php echo $weight_per_year;?></td>
							<?php
							if($i == 0){
								$evaluate_result = number_format((($result*100)/$target),2);
								$weight_total += $weight_diff;
								$point_total += $result_diff;
								?>
								<td class="text-center"><?php echo $target; ?></td>
								<td class="text-center"><?php echo $weight_actual;?></td>
								<?php
							}else{
								if($target == ''){
									$weight_total = '';
								}else{
									$weight_total += $weight_per_year;
								}

								if($result == ''){
									$point_total = '';
								}else{
									$point_total += $target;
									$weight_actual = number_format((($result * $weight_total)/$point_total),2);
									$evaluate_result = number_format((($result*100)/$point_total),2);
									$weight_diff = $weight_total - $weight_actual;
									$result_diff = $point_total - $result;
								}


								?>
								<td class="text-center"><?php echo $weight_total; ?></td>
								<td class="text-center"><?php echo $target; ?></td>
								<td class="text-center"><?php echo $point_total; ?></td>
								<td class="text-center"><?php echo $weight_actual?></td>
								<?php

								$weight_total = $weight_diff;
								$point_total = $result_diff;
								// $evaluate_result = number_format((($result*100)/$point_before),2);
							}
							?>


							<td class="text-center"><?php echo $result; ?></td>
							<td class="text-center"><?php echo $weight_diff; ?></td>
							<td class="text-center"><?php echo $result_diff; ?></td>
							<td class="text-center"><?php echo $evaluate_result; ?></td>
							<?php
						}
					?>

				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
<div class="pagination pull-right">
	<?php //$this->load->view("template/pagination"); ?>
</div>
<style>
table{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  table-layout: fixed;
  word-wrap:break-word;
}
</style>
<script type="text/javascript">
    function delete_criteria_assessment(user_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับผู้ใช้งานนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("criteria_assessments/delete_criteria_assessment/"); ?>' + user_id;
                }
            });
    }
		// jQuery(document).ready(function () {
		// 	var table = jQuery('#table-example').removeAttr('width').DataTable( {
	  //       scrollY:        "300px",
	  //       scrollX:        true,
	  //       scrollCollapse: true,
	  //       paging:         false,
		// 			searching: 			false,
		// 			ordering: 			false,
		// 			info:						false,
		// 			autoWidth: 			false,
	  //       // columnDefs: [
		// 			// 		{ width: 50, targets: 0 }
	  //       //     { width: 250, targets: 1 }
		// 			// 		{ width: 150, targets: 2 }
	  //       // ],
	  //       // fixedColumns: true
	  //   } );
		// })
</script>
