
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo base_url("evaluate_targets/dashboard_evaluate_targets"); ?>"
			 class="table-link" title="ย้อนกลับ">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button></a>
			<label class="col-md-12"></label>
	</div>
</div>
<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="10%">ลำดับ</th>
			<th class="text-center" width="30%">ชื่อโครงการ</th>
			<th class="text-center" width="10%">ปี</th>
			<th class="text-center" width="20%">รายละเอียด</th>
			<th class="text-center" width="20%">เป้าหมายร้อยละ</th>
			<th class="text-center" width="10%">
				<a href="<?php echo base_url("evaluate_targets/new_evaluate_target/$project_id"); ?>" title="เพิ่ม">
					<button type="button" class="btn btn-sm btn-success">
						<i class="fa fa-plus"></i> เพิ่ม
					</button>
				</a>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$no = 1;
		if (isset($_GET['per_page'])) $no += intval($_GET['per_page']);
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
						<?php echo $project_data[0]->project_name ?>
					</td>
					<td class="text-center">
						<?php
						// if($data->year_start == $data->year_end){
						// 	echo $data->year_start+543;
						// }else{
						// 	$year_start = $data->year_start+543;
						// 	$year_end = $data->year_end+543;
						// 	echo "$year_start - $year_end" ;
						// }
						echo $data->year +543;
						?>
					</td>
					<td class="text-left">
						<?php echo $project_data[0]->detail; ?>
					</td>
					<td class="text-center">
						<?php echo $data->target; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("evaluate_targets/edit_evaluate_target/{$project_id}/{$data->id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button>
							</a>
							<a href="#"
							   class="table-link"
							   onclick="delete_evaluate_target(<?php echo $data->id; ?>);" title="ลบ">
								<button type="button" class="btn btn-xs btn-danger">
									<i class="fa fa-trash-o"></i> ลบ
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
<script type="text/javascript">
    function delete_evaluate_target(id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องเป้าหมายโครงการ",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("evaluate_targets/delete_evaluate_target/"); ?>' + id;
                }
            });
    }
</script>
