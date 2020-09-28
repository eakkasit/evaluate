<?php $this->load->view("template/search"); ?>

<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="8%">ลำดับ</th>
			<th class="text-center" width="17%">แม่แบบเกณฑ์การประเมิน</th>
			<th class="text-center" width="6%">ปี</th>
			<th class="text-center" width="6%">สถานะ</th>
			<th class="text-center" width="12%">
				<a href="<?php echo base_url("structure/new_structure"); ?>" title="เพิ่ม">
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
						<?php echo $data->structure_name; ?>
					</td>
					<td class="text-left">
						<?php echo $data->profile_year+543; ?>
					</td>
					<td class="text-center">
						<?php if($data->structure_status == '1'){?> 	<span class="label label-success">ใช้งาน</span> <?php }else{ ?> <span class="label label-danger">ไม่ใช้งาน</span> <?php } ?>
					</td>
					<td class="text-center white">
						<div>
							<!-- <a href="<?php //echo base_url("structure/view_structure/{$data->structure_id}"); ?>"
							   class="table-link" title="แสดง">
								<button type="button" class="btn btn-xs btn-info">
									<i class="fa fa-eye"></i> แสดง
								</button></a> -->

							<a href="<?php echo base_url("structure/edit_structure/{$data->structure_id}"); ?>"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>
								<a href="<?php echo base_url("structure/new_structure_tree/{$data->structure_id}"); ?>"
								   class="table-link" title="เพิ่มเกณฑ์การประเมิน">
									<button type="button" class="btn btn-xs btn-success">
										<i class="fa fa-add"></i> เกณฑ์การประเมิน
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
    function delete_structure(id) {
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
                    location.href = '<?php echo base_url("structure/delete_structure/"); ?>' + id;
                }
            });
    }
</script>
