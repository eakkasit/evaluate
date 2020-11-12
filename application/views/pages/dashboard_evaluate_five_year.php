<?php $this->load->view("template/search_year"); ?>

<?php
	$action = base_url("evaluate_five_years/save");
?>
<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
<div class="row">
	<div class="col-md-12 text-right">
		<a href="#" class="btn btn-sm btn-warning btn_edit" onclick="showSave()">
			<i class="fa fa-pencil"></i>
			แก้ไข
		</a>
		&nbsp;&nbsp;
		<a href="#" class="btn btn-sm btn-danger btn_cancel"  onclick="showEdit()">
			<i class="fa fa-times"></i>
			ยกเลิก
		</a>
		&nbsp;&nbsp;
		<button class="btn btn-sm btn-success" type="submit" id="btn_save_data">
			<i class="fa fa-floppy-o"></i>
			บันทึก
		</button>
	</div>
	<label class="col-md-12"></label>
</div>
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
					<th class="text-center" width="260px"  colspan="4">เป้าหมาย <?php echo $search_year_start+$i+543; ?></th>
					<?php
				}else{
					?>
					<th class="text-center" width="410px"  colspan="6">เป้าหมาย <?php echo $search_year_start+$i+543; ?></th>
					<?php
				}
				?>


				<th class="text-center" width="300px"  colspan="5">ผลการประเมิน</th>
				<th class="text-center" width="75px"  rowspan="2">ร้อยละความสำเร็จ</th>

			<?php } ?>
			<th class="text-center" width="75px"  rowspan="2">ร้อยละความสำเร็จทั้งโครงการ</th>
			<th class="text-center" width="150px"  rowspan="2">หมายเหตุ</th>
		</tr>
		<tr>
			<?php
				for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
					?>
					<th class="text-center">*เป้าหมายร้อยละ</th>
					<th class="text-center">เป้าหมายร้อยละ(รวม)</th>
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
					<th class="text-center">ส่วนต่างเป้าหมายร้อยละ</th>
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
						<input type="hidden" id="year_search_<?php echo "{$data->id}"; ?>"  disabled  value="<?php echo $search_year_start; ?>" >
						<input type="hidden" id="year_start_<?php echo "{$data->id}"; ?>"  disabled  value="<?php echo $data->year_start; ?>" >
						<input type="hidden" id="year_end_<?php echo "{$data->id}"; ?>" disabled  value="<?php echo $data->year_end; ?>" >
					</td>

					<?php
					$result_all = 0;
					for ($i=0; $search_year_start+$i <= $search_year_end; $i++) {
						if(isset($data_detail['score'][$data->id][$search_year_start+$i])){
							$result_all += $data_detail['score'][$data->id][$search_year_start+$i];
						}

						if($i == 0){
							?>
							<td class="text-center">
								<span id="target_text_<?php echo "{$data->id}_".$i; ?>" class="save_data_text">
									<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>
								</span>
								<input type="number" min="0" max="100" id="target_<?php echo "{$data->id}_".$i; ?>" class="form-control save_data" <?php //echo $disable; ?> onblur="changeTarget(this)"  name="data[target][<?php echo $data->id; ?>][<?php echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>" >
							</td>
							<td class="text-center">
								<span id="target_total_text_<?php echo "{$data->id}_".$i; ?>" class="">
									<?php echo isset($data_detail['target_total'][$data->id][$search_year_start+$i])?$data_detail['target_total'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="weight_per_year_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['weight_per_year'][$data->id][$search_year_start+$i])?$data_detail['weight_per_year'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="point_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['point'][$data->id][$search_year_start+$i]) ?$data_detail['point'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="weight_result_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['weight_result'][$data->id][$search_year_start+$i])?$data_detail['weight_result'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="score_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
									<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>
								</span>
								<input type="number" min="0" max="100" class="form-control save_data" id="score_<?php echo "{$data->id}_".$i ; ?>"  <?php //echo $disable; ?> onchange="changeResult(this)" name="data[result][<?php echo $data->id; ?>][<?php echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>" >
							</td>
							<td class="text-center">
								<span id="weight_diff_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['weight_diff'][$data->id][$search_year_start+$i])?$data_detail['weight_diff'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="point_diff_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['point_diff'][$data->id][$search_year_start+$i])?$data_detail['point_diff'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="target_diff_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['target_diff'][$data->id][$search_year_start+$i])?$data_detail['target_diff'][$data->id][$search_year_start+$i]:''; ?>
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
								<input type="number" min="0" max="100" class="form-control save_data" id="target_<?php echo "{$data->id}_".$i ; ?>" <?php //echo $disable; ?>  onblur="changeTarget(this)" name="data[target][<?php echo $data->id; ?>][<?php echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>" >
							</td>
							<td class="text-center">
								<span id="target_total_text_<?php echo "{$data->id}_".$i; ?>" class="">
									<?php echo isset($data_detail['target_total'][$data->id][$search_year_start+$i])?$data_detail['target_total'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="weight_per_year_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['weight_per_year'][$data->id][$search_year_start+$i])?$data_detail['weight_per_year'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="weight_total_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['weight_total'][$data->id][$search_year_start+$i])?$data_detail['weight_total'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="point_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['point'][$data->id][$search_year_start+$i])?$data_detail['point'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="point_new_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['point_new'][$data->id][$search_year_start+$i])?$data_detail['point_new'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="weight_result_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['weight_result'][$data->id][$search_year_start+$i])?$data_detail['weight_result'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="score_text_<?php echo "{$data->id}_".$i ; ?>" class="save_data_text">
									<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>
								</span>
								<input type="number" min="0" max="100" max="100" class="form-control save_data" id="score_<?php echo "{$data->id}_".$i ; ?>"  <?php //echo $disable; ?> onchange="changeResult(this)" name="data[result][<?php echo $data->id; ?>][<?php echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>" >
							</td>
							<td class="text-center">
								<span id="weight_diff_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['weight_diff'][$data->id][$search_year_start+$i])?$data_detail['weight_diff'][$data->id][$search_year_start+$i]:''; ?>
								</span></td>
							<td class="text-center">
								<span id="point_diff_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['point_diff'][$data->id][$search_year_start+$i])?$data_detail['point_diff'][$data->id][$search_year_start+$i]:''; ?>
								</span>
							</td>
							<td class="text-center">
								<span id="target_diff_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['target_diff'][$data->id][$search_year_start+$i])?$data_detail['target_diff'][$data->id][$search_year_start+$i]:''; ?>
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
					<td class="text-center">
						<span id="remark_text_<?php echo $data->id; ?>" class="save_data_text"><?php echo isset($remark_data[$data->id])?$remark_data[$data->id]:''; ?></span>

						<textarea cols="15" rows="3" id="remark_<?php echo $data->id; ?>" class="save_data" name="data[remark][<?php echo $data->id; ?>]">
							<?php echo isset($remark_data[$data->id])?$remark_data[$data->id]:''; ?>
						</textarea>
					</td>

				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
</form>
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
		function showSave(){
			$('.save_data').show();
			$('#btn_save_data').show();
			$('.btn_cancel').show();
			$('.btn_edit').hide();
			$('.save_data_text').hide();
		}

		function showEdit(){
			$('.save_data').hide();
			$('#btn_save_data').hide();
			$('.btn_cancel').hide();
			$('.btn_edit').show();
			$('.save_data_text').show();
		}

		function changeTarget(ele) {
			var id = $(ele).attr('id');
			var id_temp = id.split('_');
			var position = id_temp[id_temp.length - 1];
			var profile_id = id_temp[id_temp.length - 2];
			var target_data = []
			var weight = $('#text_weight_'+profile_id).html().trim();
			var target_total = 0;
			var year_start = $('#year_start_'+profile_id).val();
			var year_end = $('#year_end_'+profile_id).val();
			var year_search = $('#year_search_'+profile_id).val();
			var index_last_year = '';
			$('input[id^=target_'+profile_id+']').each(function(index,value){
				var target_text = $(this).val();
				var target = $(this).val();
				var weight_per_year = $('#weight_per_year_'+profile_id+'_'+index).html().trim();
				var weight_total = typeof $('#weight_total_'+profile_id+'_'+index).html() !== 'undefined'?$('#weight_total_'+profile_id+'_'+index).html().trim():'';
				var point = $('#point_'+profile_id+'_'+index).html().trim();
				var point_new = typeof $('#point_new_'+profile_id+'_'+index).html() !== 'undefined'?$('#point_new_'+profile_id+'_'+index).html().trim():'';
				var weight_result = $('#weight_result_'+profile_id+'_'+index).html().trim();
				var score_text = $('#score_text_'+profile_id+'_'+index).html().trim();
				var score = $('#score_'+profile_id+'_'+index).val();
				var weight_diff = $('#weight_diff_'+profile_id+'_'+index).html().trim();
				var point_diff = $('#point_diff_'+profile_id+'_'+index).html().trim();
				var target_diff = $('#target_diff_'+profile_id+'_'+index).html().trim();
				var result = $('#result_'+profile_id+'_'+index).html().trim();

				target_total += (target*1);

				if(index == 0 ){

					weight_per_year = ((weight * target)/100).toFixed(2);
					point = target;

					if(point != 0){
						weight_result = ((score*weight_per_year)/point)
						result = (parseFloat(score) * 100)/parseFloat(point)

					}else{
						weight_result = 0
						result = 0
					}

					weight_diff = (parseFloat(weight_per_year) - parseFloat(weight_result))
					point_diff = parseFloat(point) - parseFloat(score)

					if(isNaN(point_diff)){
						point_diff = ''
					}else{
						point_diff = (point_diff*1).toFixed(2)
					}

					if(isNaN(weight_result)){
						weight_result = ''
					}else{
						weight_result = (weight_result*1).toFixed(2)
					}

					if(isNaN(weight_diff)){
						weight_diff = ''
					}else{
						weight_diff = (weight_diff*1).toFixed(2);
					}

					if(isNaN(result)){
						result = '';
					}else{
						result = (result*1).toFixed(2);
					}
					$('#target_text_'+profile_id+'_'+index).html(target_text);
					$('#target_total_text_'+profile_id+'_'+index).html(target_total);
					$('#weight_per_year_'+profile_id+'_'+index).html(weight_per_year);
					$('#point_'+profile_id+'_'+index).html(point);
					$('#weight_result_'+profile_id+'_'+index).html(weight_result);
					$('#weight_diff_'+profile_id+'_'+index).html(weight_diff);
					$('#point_diff_'+profile_id+'_'+index).html(point_diff);
					$('#target_diff_'+profile_id+'_'+index).html(point_diff);
					$('#result_'+profile_id+'_'+index).html(result);
				}else{
					// console.log(target_total);
					// if(target == '' && ((100 - target_total) > 0) && ){
					// 	target = (100 - target_total);
					// 	target_total += (target * 1);
					// }
					// console.log(year_search);
					if(((year_search*1)+index) == year_end && (position < index)){

						if(target_total > 100){

						}

						if($('#target_'+profile_id+'_'+(index+1)).val() != '' && typeof $('#target_'+profile_id+'_'+(index+1)).val() !== undefined){
							var temp_last = (100 - target_total )
							// if(temp_last > 0){
								// target = temp_last ;
								console.log('temp_last',temp_last);
								if(temp_last >= 0){
									$('#target_text_'+profile_id+'_'+(index+1)).html(temp_last);
									$('#target_'+profile_id+'_'+(index+1)).val(temp_last);
								}else{
									$('#target_text_'+profile_id+'_'+(index+1)).html('');
									$('#target_'+profile_id+'_'+(index+1)).val('');
								}

							// }

						}else{
							if(($('#target_'+profile_id+'_'+(index-1)).val() != '' && typeof $('#target_'+profile_id+'_'+(index-1)).val() !== undefined) ){
								var temp_last = (100 - (target_total - target))
								if(temp_last >= 0){
									target = temp_last ;
								}else{
									swal({
			                title: "แจ้งเตือน",
			                text: "เป้าหมายร้อยละเกิน 100 ",
			                type: "warning",
			                showCancelButton: false,
			                confirmButtonText: "ตกลง",
			                cancelButtonText: "ยกเลิก",
			                closeOnConfirm: false,
			                closeOnCancel: true
			            },
			            function (isConfirm) {
			                if (isConfirm) {
													$(ele).focus()
													swal.close()
			                }
			            });

								}
								// console.log('b');
							}

							// console.log('a');
						}
					}else if(((year_search*1)+index) == year_end && (position == index)){
						index_last_year = index
						if(target_total > 100 ){
							// target = 100 - target_data.reduce((prev, cur) => prev + (cur.target*1), 0)
							// alert('เป้าหมายร้อยละไม่ถูกต้อง1')
							swal({
									title: "แจ้งเตือน",
									text: "เป้าหมายร้อยละเกิน 100 ",
									type: "warning",
									showCancelButton: false,
									confirmButtonText: "ตกลง",
									cancelButtonText: "ยกเลิก",
									closeOnConfirm: false,
									closeOnCancel: true
							},
							function (isConfirm) {
									if (isConfirm) {
											$(ele).focus()
											swal.close()
									}
							});
							target = $(this).val()

						}else{
							var amount_target = (100 - target_total)
							if(amount_target > 0 && ($('#target_'+profile_id+'_'+(index+1)).val() == '')){
								swal({
										title: "แจ้งเตือน",
										text: "เป้าหมายร้อยละไม่ถึง 100 ในปีที่กำหนดท่านต้องการเพิ่มในปีถัดไปหรือไม่ ",
										type: "warning",
										showCancelButton: true,
										confirmButtonText: "ตกลง",
										cancelButtonText: "ยกเลิก",
										closeOnConfirm: false,
										closeOnCancel: false
								},
								function (isConfirm) {
										if (isConfirm) {
												// $(ele).focus()
												$('#target_text_'+profile_id+'_'+(index+1)).html(amount_target);
												$('#target_'+profile_id+'_'+(index+1)).val(amount_target);
												swal.close()
										}else{
											swal({
													title: "แจ้งเตือน",
													text: "เป้าหมายร้อยละไม่ถึง 100 ในปีที่กำหนด",
													type: "warning",
													showCancelButton: false,
													confirmButtonText: "ตกลง",
													cancelButtonText: "ยกเลิก",
													closeOnConfirm: false,
													closeOnCancel: true
											},
											function (isConfirm) {
													if (isConfirm) {
															$(ele).focus()
															swal.close()
													}
											});
										}
								});
							}
							// $('#target_text_'+profile_id+'_'+(index+1)).html(amount_target);
							// $('#target_'+profile_id+'_'+(index+1)).val(amount_target);
							// console.log(amount_target);
						}
						// if(position == index){
						// 	console.log('a');
						// }else{
						// 	console.log('b');
						// }

						}else if(((year_search*1)+index) > year_end && (position == index)){
							var check_last_year = (year_end-year_search);

							// console.log('index_last_year',(year_end-year_search));
							// console.log('year', ((year_search*1)+index)-year_end);
							// console.log('index',(index - check_last_year));

							if((index - check_last_year) > 1){
								// console.log('gd');
								// alert('เป้าหมายร้อยละไม่ถูกต้อง4 กรุณาขยายระยะเวลาโครงการ / กิจจกรรม');
								if(target_total < 100){
									swal({
											title: "แจ้งเตือน",
											text: "เป้าหมายร้อยละไม่ครบ 100 กรุณาตรวจสอบรายการ",
											type: "warning",
											showCancelButton: false,
											confirmButtonText: "ตกลง",
											cancelButtonText: "ยกเลิก",
											closeOnConfirm: false,
											closeOnCancel: true
									},
									function (isConfirm) {
											if (isConfirm) {
													$(ele).focus()
													swal.close()
											}
									});
								}else if(target_total >100){
									swal({
											title: "แจ้งเตือน",
											text: "เป้าหมายร้อยละเกิน 100 ",
											type: "warning",
											showCancelButton: false,
											confirmButtonText: "ตกลง",
											cancelButtonText: "ยกเลิก",
											closeOnConfirm: false,
											closeOnCancel: true
									},
									function (isConfirm) {
											if (isConfirm) {
													$(ele).focus()
													swal.close()
											}
									});

								}else{
									// console.log('100%');
								}
								// target = $(ele).val()
								// target = ''
							}else{
								// console.log('target_total',target_total);
								if(target_total > 100){
									// target = 100 - target_data.reduce((prev, cur) => prev + (cur.target*1), 0)
									// alert('เป้าหมายร้อยละไม่ถูกต้อง2')
									swal({
											title: "แจ้งเตือน",
											text: "เป้าหมายร้อยละเกิน 100 ",
											type: "warning",
											showCancelButton: false,
											confirmButtonText: "ตกลง",
											cancelButtonText: "ยกเลิก",
											closeOnConfirm: false,
											closeOnCancel: true
									},
									function (isConfirm) {
											if (isConfirm) {
													$(ele).focus()
													swal.close()
											}
									});
								}
								// console.log('s');
							}
						console.log('target_total',target_total);
						// console.log('da');
						}else{
							console.log('ss');
						}
					weight_per_year = ((weight * target)/100);
					point = target;
					point_new = parseFloat(point) + parseFloat(target_data[index-1].point_diff)
					weight_total = parseFloat(weight_per_year) + parseFloat(target_data[index-1].weight_diff)
					if(point_new != 0){
							weight_result = ((score*weight_total)/point_new);
							result = ((score * 100 )/point_new);
					}else{
							weight_result = '';
							result = '';
					}
					weight_diff =  (weight_total - weight_result);
					point_diff = (point_new - score);

					if(isNaN(weight_per_year) || weight_per_year == 0){
						weight_per_year = ''
					}else{
						// console.log('weight_per_year',weight_per_year);
						weight_per_year = (weight_per_year*1).toFixed(2)
					}
					if(isNaN(weight_total)){
						weight_total = ''
					}else{
						weight_total = (weight_total*1).toFixed(2)
					}

					if(isNaN(point_diff)){
						point_diff = ''
					}else{
						point_diff = (point_diff*1).toFixed(2)
					}

					if(isNaN(weight_result)){
						weight_result = ''
					}else{
						weight_result = (weight_result*1).toFixed(2)
					}

					if(isNaN(weight_diff)){
						weight_diff = ''
					}else{
						weight_diff = (weight_diff*1).toFixed(2);
					}

					if(isNaN(result)){
						result = '';
					}else{
						result = (result*1).toFixed(2);
					}

					if(((year_search*1)+index) > year_end){
						if(target == ''){
							weight_total = ''
						}else{

						}
					}

					if(target == ''){
						target_total = ''
					}

					$('#target_text_'+profile_id+'_'+index).html(target);
					$('#target_'+profile_id+'_'+index).val(target);
					$('#target_total_text_'+profile_id+'_'+index).html(point_new);
					$('#weight_per_year_'+profile_id+'_'+index).html(weight_per_year);
					$('#point_'+profile_id+'_'+index).html(point);
					$('#weight_total_'+profile_id+'_'+index).html(weight_total);
					$('#point_new_'+profile_id+'_'+index).html(point_new);
					$('#weight_diff_'+profile_id+'_'+index).html(weight_diff);
					$('#weight_result_'+profile_id+'_'+index).html(weight_result);
					$('#point_diff_'+profile_id+'_'+index).html(point_diff);
					$('#target_diff_'+profile_id+'_'+index).html(point_diff);
					$('#result_'+profile_id+'_'+index).html(result);
				}
				var temp_val = {
					'target_text': target_text,
					'target':target,
					'target_total':target_total,
					'weight_per_year':weight_per_year,
					'weight_total':weight_total,
					'point':point,
					'point_new':point_new,
					'weight_result':weight_result,
					'score_text':score_text,
					'score':score,
					'weight_diff':weight_diff,
					'point_diff':point_diff,
					'target_diff':target_diff,
					'result':result,
				}
				target_data.push(temp_val);
			})

		}

		function changeResult(ele) {
			var id = $(ele).attr('id');
			var id_temp = id.split('_');
			var position = id_temp[id_temp.length - 1];
			var profile_id = id_temp[id_temp.length - 2];
			var result_data = []
			var weight = $('#text_weight_'+profile_id).html().trim();
			$('input[id^=score_'+profile_id+']').each(function(index,value){
				var target_text = $('#target_text_'+profile_id+'_'+index).html().trim();
				var target = $('#target_'+profile_id+'_'+index).val();
				var weight_per_year = $('#weight_per_year_'+profile_id+'_'+index).html().trim();
				var weight_total = typeof $('#weight_total_'+profile_id+'_'+index).html() !== 'undefined'?$('#weight_total_'+profile_id+'_'+index).html().trim():'';
				var point = $('#point_'+profile_id+'_'+index).html().trim();
				var point_new = typeof $('#point_new_'+profile_id+'_'+index).html() !== 'undefined'?$('#point_new_'+profile_id+'_'+index).html().trim():'';
				var weight_result = $('#weight_result_'+profile_id+'_'+index).html().trim();
				var score_text = $('#score_text_'+profile_id+'_'+index).html().trim();
				var score = $(this).val();
				var weight_diff = $('#weight_diff_'+profile_id+'_'+index).html().trim();
				var point_diff = $('#point_diff_'+profile_id+'_'+index).html().trim();
				var result = $('#result_'+profile_id+'_'+index).html().trim();

				if(index == 0 ){

					if(point != 0){
						weight_result = ((score*weight_per_year)/point)
						result = (parseFloat(score) * 100)/parseFloat(point)
					}else{
						weight_result = 0
						result = 0
					}

					weight_diff = (parseFloat(weight_per_year) - parseFloat(weight_result))
					point_diff = parseFloat(point) - parseFloat(score)

					if(isNaN(point_diff)){
						point_diff = ''
					}else{
						point_diff = (point_diff*1).toFixed(2)
					}

					if(isNaN(weight_result)){
						weight_result = ''
					}else{
						weight_result = (weight_result*1).toFixed(2)
					}

					if(isNaN(weight_diff)){
						weight_diff = ''
					}else{
						weight_diff = (weight_diff*1).toFixed(2);
					}

					if(isNaN(result)){
						result = '';
					}else{
						result = (result*1).toFixed(2);
					}

					$('#weight_result_'+profile_id+'_'+index).html(weight_result);
					$('#weight_diff_'+profile_id+'_'+index).html(weight_diff);
					$('#point_diff_'+profile_id+'_'+index).html(point_diff);
					$('#result_'+profile_id+'_'+index).html(result);
				}else{
					point_new = parseFloat(point) + parseFloat(result_data[index-1].point_diff)
					if(point_new != 0){
							weight_result = ((score*weight_per_year)/point_new);
							result = ((score * 100 )/point_new);
					}else{
							weight_result = 0;
							result = 0;
					}
					weight_diff =  (weight_total - weight_result);
					point_diff = (point_new - score);

					if(isNaN(point_diff)){
						point_diff = ''
					}else{
						point_diff = (point_diff*1).toFixed(2)
					}

					if(isNaN(weight_result)){
						weight_result = ''
					}else{
						weight_result = (weight_result*1).toFixed(2)
					}

					if(isNaN(weight_diff)){
						weight_diff = ''
					}else{
						weight_diff = (weight_diff*1).toFixed(2);
					}

					if(isNaN(result)){
						result = '';
					}else{
						result = (result*1).toFixed(2);
					}

					$('#point_new_'+profile_id+'_'+index).html(point_new);
					$('#weight_diff_'+profile_id+'_'+index).html(weight_diff);
					$('#weight_result_'+profile_id+'_'+index).html(weight_result);
					$('#point_diff_'+profile_id+'_'+index).html(point_diff);
					$('#result_'+profile_id+'_'+index).html(result);
				}
				var temp_val = {
					'target_text': target_text,
					'target':target,
					'weight_per_year':weight_per_year,
					'weight_total':weight_total,
					'point':point,
					'point_new':point_new,
					'weight_result':weight_result,
					'score_text':score_text,
					'score':score,
					'weight_diff':weight_diff,
					'point_diff':point_diff,
					'result':result,
				}
				result_data.push(temp_val);
			})
			$('#result_all_'+profile_id).text(result_data.reduce((prev, cur) => prev + (cur.score*1), 0));

		}
		jQuery(document).ready(function () {
			$('.save_data').hide();
			// $('.save_data_text').hide();
			$('#btn_save_data').hide();
			$('.btn_cancel').hide();
		})
</script>
