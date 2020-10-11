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
			<th class="text-center" width="75px"  rowspan="2">ร้อยละความสำเร็จทั้งโครงการ</th>
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

						if($i == 0){
							?>
							<td class="text-center">
								<span id="target_text_<?php echo "{$data->id}_".$i; ?>" class="save_data_text">
									<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>
								</span>
								<input type="number" id="target_<?php echo "{$data->id}_".$i; ?>" class="form-control save_data" <?php //echo $disable; ?> onchange="changeTarget(this)" name="data[target][<?php echo $data->id; ?>][<?php echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>" >
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
								<input type="number" class="form-control save_data" id="score_<?php echo "{$data->id}_".$i ; ?>"  <?php //echo $disable; ?> onchange="changeResult(this)" name="data[result][<?php //echo $data->id; ?>][<?php //echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>" >
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
								<input type="number" class="form-control save_data" id="target_<?php echo "{$data->id}_".$i ; ?>" <?php //echo $disable; ?> onchange="changeTarget(this)" name="data[target][<?php echo $data->id; ?>][<?php echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['target'][$data->id][$search_year_start+$i])?$data_detail['target'][$data->id][$search_year_start+$i]:''; ?>" >
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
								<input type="number" class="form-control save_data" id="score_<?php echo "{$data->id}_".$i ; ?>"  <?php //echo $disable; ?> onchange="changeResult(this)" name="data[result][<?php //echo $data->id; ?>][<?php //echo $search_year_start+$i; ?>]" value="<?php echo isset($data_detail['score'][$data->id][$search_year_start+$i])?$data_detail['score'][$data->id][$search_year_start+$i]:''; ?>" >
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
								<span id="result_<?php echo "{$data->id}_".$i ; ?>" class="">
									<?php echo isset($data_detail['result'][$data->id][$search_year_start+$i]) && $data_detail['result'][$data->id][$search_year_start+$i] != ''?number_format($data_detail['result'][$data->id][$search_year_start+$i],2):''; ?>
								</span>
							</td>
							<?php
						}
					}
					?>
					<td class="text_center">
						<span id="result_all_<?php echo $data->id; ?>"><?php echo $result_all ?></span>
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
				var result = $('#result_'+profile_id+'_'+index).html().trim();

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
					$('#weight_per_year_'+profile_id+'_'+index).html(weight_per_year);
					$('#point_'+profile_id+'_'+index).html(point);
					$('#weight_result_'+profile_id+'_'+index).html(weight_result);
					$('#weight_diff_'+profile_id+'_'+index).html(weight_diff);
					$('#point_diff_'+profile_id+'_'+index).html(point_diff);
					$('#result_'+profile_id+'_'+index).html(result);
				}else{
					weight_per_year = ((weight * target)/100).toFixed(2);
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

					// if(isNaN(weight_per_year)){
					// 	weight_per_year = (weight_per_year*1).toFixed(2)
					// }else{
					// 	weight_per_year = ''
					// }
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

					$('#weight_per_year_'+profile_id+'_'+index).html(weight_per_year);
					$('#point_'+profile_id+'_'+index).html(point);
					$('#weight_total_'+profile_id+'_'+index).html(weight_total);
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

		}
		jQuery(document).ready(function () {
			$('.save_data').hide();
			// $('.save_data_text').hide();
			$('#btn_save_data').hide();
			$('.btn_cancel').hide();
		})
</script>
