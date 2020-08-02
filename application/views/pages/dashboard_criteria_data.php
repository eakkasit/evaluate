<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="8%">ลำดับ</th>
			<th class="text-center" width="24%">ชื่อการประเมิน</th>
			<th class="text-center" width="23%">รูปแบบการประเมิน</th>
			<th class="text-center" width="13%">วันที่สร้าง</th>
			<th class="text-center" width="20%">รายละเอียด</th>
			<!-- <th class="text-center" width="6%">สถานะ</th> -->
			<th class="text-center" width="12%">
				<a href="<?php echo base_url("criteria_datas/new_criteria_data"); ?>" title="เพิ่ม">
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
						<?php echo $data->name; ?>
					</td>
					<td class="text-left">
						<?php echo $profiles[$data->profile_id]; ?>
					</td>
					<td class="text-left">
							<?php echo date_thai($data->create_date,false,false); ?>
					</td>
					<td class="text-center">
						<?php echo $data->detail; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("criteria_datas/view_criteria_data/{$data->id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<a href="<?php echo base_url("criteria_datas/edit_criteria_data/{$data->id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

								<a href="#"
								   class="table-link"
								   onclick="delete_criteria_data(<?php echo $data->id; ?>);" title="ลบ">
									<button type="button" class="btn btn-xs btn-danger">
										<i class="fa fa-trash-o"></i> ลบ
									</button></a>
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
    function delete_criteria_data(user_id) {
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
                    location.href = '<?php echo base_url("criteria_datas/delete_criteria_data/"); ?>' + user_id;
                }
            });
    }
</script>
