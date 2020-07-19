<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> ค่าเริ่มต้นวาระการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("agenda_defaults/dashboard_agenda_defaults"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("agenda_defaults/edit_agenda_default/{$agenda_default_data->agenda_default_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					
					<a href="#"
						class="table-link"
						onclick="delete_agenda_default(<?php echo $agenda_default_data->agenda_default_id; ?>);" title="ลบ">
						<button type="button" class="btn btn-xs btn-danger">
							<i class="fa fa-trash-o"></i> ลบ
						</button></a>
					
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">หัวเรื่อง :</label>
				</div>
				<div class="col-md-8 text-left">
					<label
						for="stext"><?php echo $agenda_default_data->agenda_default_name; ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">เรื่องเดิม :</label>
				</div>
				<div class="col-md-8 text-left">
					<label
						for="stext"><?php echo $agenda_default_data->agenda_default_story; ?></label>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 text-right">
					<label for="stext">เนื้อหาวาระ :</label>
				</div>
				<div class="col-md-8 text-left">
					<label
						for="stext"><?php echo $agenda_default_data->agenda_default_detail; ?></label>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function delete_agenda_default(agenda_default_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการลบค่าเริ่มต้นวาระการประชุม",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("agenda_defaults/delete_agenda_default/"); ?>' + agenda_default_id;
                }
            });
    }
</script>
