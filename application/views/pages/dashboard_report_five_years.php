<?php $this->load->view("template/search_year"); ?>
<style>

.td-border-top-bottom-odd{
	border-top:2px solid #dac400 !important;
	border-bottom:2px solid #dac400 !important;
	background-color: #dac400;
}

.td-border-top-bottom-even{
	border-top:2px solid #7ea3e6 !important;
	border-bottom:2px solid #7ea3e6 !important;
	background-color: #7ea3e6;
}

.td-border-left-top-odd{
	border-top:2px solid #dac400 !important;
	border-left:2px solid #dac400 !important;
	border-bottom:2px solid #dac400 !important;
	background-color: #dac400;
}

.td-border-left-top-even{
	border-top:2px solid #7ea3e6 !important;
	border-left:2px solid #7ea3e6 !important;
	border-bottom:2px solid #7ea3e6 !important;
	background-color: #7ea3e6;
}


.td-border-right-top-odd{
	border-top:2px solid #dac400 !important;
	border-right:2px solid #dac400 !important;
	border-bottom:2px solid #dac400 !important;
	background-color: #dac400;
}

.td-border-right-top-even{
	border-top:2px solid #7ea3e6 !important;
	border-right:2px solid #7ea3e6 !important;
	border-bottom:2px solid #7ea3e6 !important;
	background-color: #7ea3e6;
}

.table > tbody > tr:last-child >td.td-bottom-odd{
	border-bottom:2px solid #dac400 !important;
	background-color: #dac400;
}

.table > tbody > tr:last-child >td.td-bottom-even{
	border-bottom:2px solid #7ea3e6 !important;
	background-color: #7ea3e6;
}

.td-left-odd{
	border-left:2px solid #dac400 !important;
	background-color: #dac400;
}

.td-left-even{
	border-left:2px solid #7ea3e6 !important;
	background-color: #7ea3e6;

}

.td-right-odd{
	border-right:2px solid #dac400 !important;
	background-color: #dac400;
}

.td-right-even{
	border-right:2px solid #7ea3e6 !important;
	background-color: #7ea3e6;
}
th.odd,td.odd{
	background-color: #dac400;
}

th.even,td.even{
	background-color: #7ea3e6;
}

th[class^='color_']{
	color: white;
	/* background-color: #FDC12E;
	font-weight: bold; */
}

th.color_1{
	color: white;
	background-color: #FDC12E !important;
	font-weight: bold;
}

th.color_2{
	color: white;
	background-color: #FD4263 !important;
	font-weight: bold;
}

th.color_3{
	color: white;
	background-color: #35C2AD !important;
	font-weight: bold;
}

th.color_4{
	color: white;
	background-color: #45C7E0 !important;
	font-weight: bold;
}

th.color_5{
	color: white;
	background-color: #3197FC !important;
	font-weight: bold;
}



</style>
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
			$column = 4;
			for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
				// if(($i%2)==0){
				// 	$cls = "odd";
				// }else{
				// 	$cls = "even";
				// }
				$cls = "color_".($i+1);

				$column += 3;
				?>
				<th class="text-center <?php echo $cls; ?>" width="100px"  >เป้าหมายปี <?php echo $search_year_start+$i+543; ?></th>
				<th class="text-center <?php echo $cls; ?>" width="100px"  >ผลการประเมิน</th>
				<th class="text-center <?php echo $cls; ?>" width="100px"  >ร้อยละความสำเร็จ</th>
			<?php }
			$column += 2;
			 ?>
			<th class="text-center" width="75px"  >ร้อยละความสำเร็จทั้งโครงการ</th>
			<th class="text-center" width="100px"  >หมายเหตุ</th>
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
					<td class="text-center" >
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
					<td class="text-center" >
						<span id="text_weight_<?php echo $data->id; ?>">
							<?php echo $data->weight; ?>
						</span>
					</td>

					<?php
					$result_all = 0;
					for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
						if(isset($data_detail['score'][$data->id][$search_year_start+$i])){
							$result_all += $data_detail['score'][$data->id][$search_year_start+$i];
						}
						if(($i%2)==0){
							$cls = "odd";
						}else{
							$cls = "even";
						}

						if($i == 0){
							?>
							<td class="text-center">
								<span id="target_text_<?php echo "{$data->id}_".$i; ?>" class="save_data_text">
									<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>

							<td class="text-center">
								<span id="score_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
									<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>
								</span>

							</td>
							<td class="text-center">
								<span id="result_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['result'][$data->id][$search_year_start+$i]) && $data_detail['result'][$data->id][$search_year_start+$i] != '' ?number_format($data_detail['result'][$data->id][$search_year_start+$i],2):''; ?>
								</span>
							</td>
							<?php
						}else{
							?>
							<td class="text-center">
								<span id="target_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
									<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>

							<td class="text-center">
								<span id="score_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
									<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="result_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['result'][$data->id][$search_year_start+$i]) && $data_detail['result'][$data->id][$search_year_start+$i] != ''?number_format($data_detail['result'][$data->id][$search_year_start+$i],2):''; ?>
								</span>
							</td>
							<?php
						}
					}
					?>
					<td class="text-center">
						<span id="result_all_<?php echo $data->id; ?>"><?php echo $result_all ?></span>
					</td>
					<td>
						<?php echo isset($remark_data[$data->id])?$remark_data[$data->id]:''; ?>
					</td>
				</tr>
				<?php
			}
		}else{
			?>
			<tr>
				<td colspan="<?php echo $column; ?>" align="center">- <b>ไม่พบข้อมูล</b> -</td>
			</tr>
			<?php
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
