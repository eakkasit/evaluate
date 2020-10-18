<?php //$this->load->view("template/search"); ?>
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("evaluate_datas/dashboard_evaluate_datas"); ?>"
			 class="table-link" title="ย้อนกลับ">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button></a>
			<label class="col-md-12"></label>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
		<table role="grid" id="table-example"
			   class="table table-bordered table-hover dataTable no-footer">
			<thead>
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
				<th class="text-center" width="5%">
					<?php
					if($btn_add){
						?>
						<a href="<?php echo base_url("evaluate_datas/new_evaluate_data/$project_id"); ?>" title="เพิ่ม">
							<button type="button" class="btn btn-sm btn-success">
								<i class="fa fa-plus"></i> เพิ่ม
							</button>
						</a>
						<?php
					}
					?>

				</th>
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
							<?php echo $project_data[0]->project_name; ?>
						</td>
						<td class="text-center">
							<?php echo $data->year+543; ?>
						</td>
						<td class="text-left"> -
							<?php //echo $data->department; ?>
						</td>
						<td class="text-left"> -
							<?php //echo $data->detail; ?>
						</td>
						<td class="text-left">
							<?php echo word_limiter($data->project_result,10); ?>
						</td>
						<td class="text-left">
							<?php echo word_limiter($data->product,10); ?>
						</td>
						<td class="text-center">
							<?php echo word_limiter($data->result,10); ?>
						</td>
						<td class="text-center">
							<?php echo word_limiter($data->assessment_results,10); ?>
						</td>
						<td  class="text-left">
							<div>
								<a href="<?php echo base_url("evaluate_datas/edit_evaluate_data/{$project_id}/{$data->id}"); ?>" class="table-link" title="แก้ไขผลการประเมิน">
									<button type="button" class="btn btn-xs btn-warning">
										<i class="fa fa-pencil"></i> แก้ไขผลการประเมิน
									</button>
								</a>
								<a href="#" onclick="delete_evaluate_data('<?php echo $project_id; ?>','<?php echo $data->id; ?>')" class="table-link" title="ลบผลการประเมิน">
									<button type="button" class="btn btn-xs btn-danger">
										<i class="fa fa-trash-o"></i> ลบผลการประเมิน
									</button>
								</a>
							</div>
						</td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
	</div>
</div>

<script type="text/javascript">
    function delete_evaluate_data(project_id,id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องลบผลการประเมิน",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("evaluate_datas/delete_evaluate_data/"); ?>' +project_id+'/'+id;
                }
            });
    }
</script>
