<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> หน่วยวัดเกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("units/dashboard_units"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("units/edit_units/{$data->unit_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php // if (in_array(strtolower($data->user_status), array('active'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="delete_user(<?php echo $data->unit_id; ?>);" title="ลบ">
							<button type="button" class="btn btn-xs btn-danger">
								<i class="fa fa-trash-o"></i> ลบ
							</button></a>
					<?php //} ?>
				</div>
			</div>

			<div class="row">

				<div class="col-md-8">

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">หน่วยวัดเกณฑ์การประเมิน :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->unit_name; ?></label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function delete_user(id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการลบหน่วยวัดเกณฑ์การประเมิน",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("units/delete_units/"); ?>' + id;
                }
            });
    }
</script>
