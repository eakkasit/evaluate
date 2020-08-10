<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="8%">ลำดับ</th>
			<th class="text-center" width="17%">ชื่อเกณฑ์การประเมิน</th>
			<th class="text-center" width="13%">หน่วยวัด</th>
			<th class="text-center" width="13%">ระดับเกณฑ์การประเมิน</th>
			<th class="text-center" width="24%">
				<a href="<?php echo base_url("kpi/new_kpi"); ?>" title="เพิ่ม">
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
						<?php echo $data->kpi_name; ?>
					</td>
					<td class="text-left">
						<?php echo $units_list[$data->unit_id]; ?>
					</td>
					<td class="text-left">
						<?php echo $level_list[$data->level_id]; ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="<?php echo base_url("kpi/view_kpi/{$data->kpi_id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a>

							<a href="<?php echo base_url("kpi/edit_kpi/{$data->kpi_id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<?php //if (in_array(strtolower($data->user_status), array('active'))) { ?>
								<a href="#"
								   class="table-link"
								   onclick="delete_kpi(<?php echo $data->kpi_id; ?>);" title="ระงับ">
									<button type="button" class="btn btn-xs btn-danger">
										<i class="fa fa-trash-o"></i> ลบ
									</button></a>
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
    function delete_kpi(id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการลบเกณฑ์การประเมิน",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("kpi/delete_kpi/"); ?>' + id;
                }
            });
    }
</script>