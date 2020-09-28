<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> โครงการ / กิจกรรม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("activities/dashboard_activity"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("activities/edit_activity/{$data->id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php if (in_array(strtolower($data->status), array('2'))) { ?>
						<a href="#"
						   class="table-link"
						   onclick="delete_user(<?php echo $data->id; ?>);" title="ระงับ">
							<button type="button" class="btn btn-xs btn-danger">
								<i class="fa fa-trash-o"></i> ระงับ
							</button></a>
					<?php } ?>
				</div>
			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ชื่อโครงการ / กิจกรรม :</label>
						</div>
						<div class="col-md-8">
							<label for="stext"><?php echo $data->project_name; ?></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ปี :</label>
						</div>
						<div class="col-md-8">
							<label for="stext"><?php echo $data->year+543; ?></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ระยะเวลาดำเนินการ :</label>
						</div>
						<div class="col-md-5">
							<label for="stext">
								<?php
								if($data->date_start && $data->date_end){
									echo date_thai($data->date_start,false,false) ." - ". date_thai($data->date_end,false,false);
								}else{
									echo "-";
								}

								?>
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">สถานะโครงการ / กิจกรรม :</label>
						</div>
						<div class="col-md-8">
							<label for="stext"><?php echo $status_list[$data->status]; ?></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">รายละเอียด :</label>
						</div>
						<div class="col-md-8">
							<label for="stext"><?php echo $data->detail; ?></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ค่าน้ำหนักโครงการ :</label>
						</div>
						<div class="col-md-8">
							<label for="stext"><?php echo $data->weight; ?></label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php if (in_array(strtolower($data->status), array('2'))) { ?>
    function delete_user(id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้ต้องการระงับโครงการ / กิจกรรมนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("activities/delete_activity/"); ?>' + id;
                }
            });
    }
	<?php } ?>
</script>
