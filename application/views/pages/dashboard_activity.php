<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="8%">ลำดับ</th>
			<th class="text-center" width="17%">ชื่อโครงการ / กิจกกรม</th>
			<th class="text-center" width="6%">ปี</th>
			<th class="text-center" width="12%">ระยะเวลาดำเนินการ</th>
			<th class="text-center" width="19%">รายละเอียด</th>
			<th class="text-center" width="6%">สถานะ</th>
			<th class="text-center" width="12%">
				<a href="<?php echo base_url("activities/new_activity"); ?>" title="เพิ่ม">
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
						<?php echo $data->project_name; ?>
					</td>
					<td class="text-left">
						<?php echo $data->year+543; ?>
					</td>
					<td class="text-left">
						<?php
						if($data->date_start && $data->date_end){
							echo date_thai($data->date_start,false,false) ." - ". date_thai($data->date_end,false,false) ;
						}else{
							echo "-";
						}
						?>
					</td>
					<td class="text-left">
						<?php echo $data->detail; ?>
					</td>
					<td class="text-center">
						<?php echo $status_list[$data->status]; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("activities/view_activity/{$data->id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<a href="<?php echo base_url("activities/edit_activity/{$data->id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<?php if (in_array(strtolower($data->status), array('2'))) { ?>
								<a href="#"
								   class="table-link"
								   onclick="delete_activity(<?php echo $data->id; ?>);" title="ระงับ">
									<button type="button" class="btn btn-xs btn-danger">
										<i class="fa fa-trash-o"></i> ระงับ
									</button></a>
							<?php } ?>
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
<div class="pagination pull-right">
	<?php $this->load->view("template/pagination"); ?>
</div>
<script type="text/javascript">
    function delete_activity(project_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับโครงการ / กิจกรรม นี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("activities/delete_activity/"); ?>' + project_id;
                }
            });
    }
</script>
