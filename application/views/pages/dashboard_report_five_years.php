<?php $this->load->view("template/search_year"); ?>
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("report_five_years/export/pdf"); ?>"  class="table-link" title="พิมพ์ PDF" target="_blank">
			<button type="button" class="btn btn-xs btn-danger">
				<i class="fa fa-file-pdf-o"></i> PDF
			</button>
		</a>
		<a href="<?php echo base_url("report_five_years/export/word"); ?>" class="table-link" title="ส่งออก Word" target="_blank">
			<button type="button" class="btn btn-xs btn-primary">
				<i class="fa fa-file-word-o"></i> Word
			</button>
		</a>
		<a href="<?php echo base_url("report_five_years/export/excel"); ?>" class="table-link" title="ส่งออก Excel" target="_blank">
			<button type="button" class="btn btn-xs btn-success">
				<i class="fa fa-file-excel-o"></i> Excel
			</button>
		</a>
	</div>
	<label class="col-md-12"></label>
</div>
<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row" class="d-flex">
			<th class="text-center start_no " width="70px" >ลำดับ</th>
			<th class="text-center " width="250px"  >ชื่อโครงการ</th>
			<th class="text-center" width="100px"  >ปีงบประมาณ</th>
			<th class="text-center" width="100px"  >น้ำหนักโครงการ</th>
			<?php
			for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
				?>
				<th class="text-center" width="100px"  >เป้าหมายปี <?php echo $search_year_start+$i+543; ?></th>
				<th class="text-center" width="100px"  >ผลการประเมิน</th>
				<th class="text-center" width="100px"  >ร้อยละความสำเร็จ</th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
		<?php
		$no = 1;
		if (isset($project_list) && !empty($project_list)) {
			foreach ($project_list as $key => $data) {
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
							<?php
							if($i == 0){
								$evaluate_result = number_format((($result*100)/$target),2);
								$weight_total += $weight_diff;
								$point_total += $result_diff;
								?>
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
								<?php

								$weight_total = $weight_diff;
								$point_total = $result_diff;
							}
							?>


							<td class="text-center"><?php echo $result; ?></td>
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
</script>
