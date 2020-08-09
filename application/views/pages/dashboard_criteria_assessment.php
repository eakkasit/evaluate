<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
			<tr role="row">
				<th class="text-center start_no" width="8%">ลำดับ</th>
				<th class="text-center" width="17%">แม่แบบเกณฑ์การประเมิน</th>
				<th class="text-center" width="6%">ปี</th>
				<th class="text-center" width="13%">วันที่สร้าง</th>
				<th class="text-center" width="24%">รายละเอียด</th>
				<th class="text-center" width="6%">สถานะ</th>
				<th class="text-center" width="12%">
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
							<?php echo $data->profile_name; ?>
						</td>
						<td class="text-left">
							<?php echo $data->year; ?>
						</td>
						<td class="text-left">
							<?php echo date_thai($data->create_date,false,false); ?>
						</td>
						<td class="text-center">
							<?php echo $data->detail; ?>
						</td>
						<td class="text-center">
							<?php echo $status_list[$data->status]; ?>
						</td>
						<td class="text-center white">
							<div>
								<a href="<?php echo base_url("criteria_assessments/view_criteria_assessment/{$data->id}"); ?>"
								   class="table-link" title="แสดง">
									<button type="button" class="btn btn-xs btn-info">
										<i class="fa fa-eye"></i> แสดง
									</button></a>

								<a href="<?php echo base_url("criteria_assessments/new_criteria_assessment/{$data->id}"); ?>"
								   class="table-link" title="เพิ่มเกณฑ์การประเมิน">
									<button type="button" class="btn btn-xs btn-success">
										<i class="fa fa-add"></i> เกณฑ์การประเมิน
									</button></a>

								<?php //if (in_array(strtolower($data->status), array('1'))) { ?>
									<!-- <a href="#"
									   class="table-link"
									   onclick="delete_criteria_theme(<?php echo $data->id; ?>);" title="ระงับ">
										<button type="button" class="btn btn-xs btn-danger">
											<i class="fa fa-trash-o"></i> ระงับ
										</button></a> -->
								<?php //} ?>
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
