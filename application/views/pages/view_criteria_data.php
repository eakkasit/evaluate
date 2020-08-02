<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> รายชื่อผู้ประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("criteria_datas/dashboard_criteria_datas"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("criteria_datas/edit_criteria_data/{$data->id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php //if (in_array(strtolower($data->user_status), array('active'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="delete_criteria_data(<?php echo $data->id; ?>);" title="ระงับ">
							<button type="button" class="btn btn-xs btn-danger">
								<i class="fa fa-trash-o"></i> ลบ
							</button></a>
					<?php// } ?>
				</div>
			</div>

			<div class="row">
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ชื่อการประเมิน :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->name; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">แม่แบบเกณฑ์การประเมิน, :</label>
						</div>
						<div class="col-md-8 text-left">
							<label
								for="stext"><?php echo $profiles[$data->profile_id]; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">รายละเอียด :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->detail; ?></label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php if (in_array(strtolower($data->user_status), array('active'))) { ?>
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
	<?php } ?>
</script>
