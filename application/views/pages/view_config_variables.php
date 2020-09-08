<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> ตัวแปรจากระบบ
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("config_variables/dashboard_config_variables"); ?>" class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button>
					</a>
					<a href="<?php echo base_url("config_variables/edit_config_variables/{$data->id}"); ?>"  class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button>
					</a>
					<a href="#" class="table-link" onclick="delete_config_variables(<?php echo $data->id; ?>);" title="ลบ">
						<button type="button" class="btn btn-xs btn-danger">
							<i class="fa fa-trash-o"></i> ลบ
						</button>
					</a>
				</div>
			</div>

			<div class="row">

				<div class="col-md-8">

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ชื่อตัวแปรจากระบบ :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->name; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">sql :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->sql; ?></label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function delete_config_variables(id) {
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
                    location.href = '<?php echo base_url("config_variables/delete_config_variables/"); ?>' + id;
                }
            });
    }
</script>
