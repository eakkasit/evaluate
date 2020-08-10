<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> ตัวแปรเกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("variable/dashboard_variable"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("variable/edit_variable/{$data->var_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php // if (in_array(strtolower($data->user_status), array('active'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="delete_user(<?php echo $data->var_id; ?>);" title="ลบ">
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
							<label for="stext">ชื่อตัวแปรเกณฑ์การประเมิน :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->var_name; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">หน่วยวัด :</label>
						</div>
						<div class="col-md-8 text-left">
							<label
								for="stext"><?php echo $units_list[$data->var_unit_id]; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ชนิดของการแสดงผล :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $show_type_list[$data->var_type_id]; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ประเภทการนำเข้าข้อมูล :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $field_type_list[$data->var_import_id]; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ค่าตัวแปร :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->var_value; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ความยาวตัวอักษร/จำนวนทศนิยม :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->var_max_length; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">sql :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->var_sql; ?></label>
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
                text: "ต้องการลบตัวแปรเกณฑ์การประเมิน",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("variable/delete_variable/"); ?>' + id;
                }
            });
    }
</script>
