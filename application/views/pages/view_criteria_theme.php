<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> แม่แบบเกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("criteria_themes/dashboard_criteria_themes"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>
					<a href="<?php echo base_url("criteria_datas/new_criteria_data/{$data->id}"); ?>"
					   class="table-link" title="เพิ่ม">
						<button type="button" class="btn btn-xs btn-success">
							<i class="fa fa-add"></i> เพิ่มเกณฑ์การประเมิน
						</button></a>
					<a href="<?php echo base_url("criteria_themes/edit_criteria_theme/{$data->id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>

					<?php //if (in_array(strtolower($data->status), array('active'))) { ?>
						<!-- <a href="#"
						   class="table-link"
						   onclick="delete_user(<?php echo $data->id; ?>);" title="ระงับ">
							<button type="button" class="btn btn-xs btn-danger">
								<i class="fa fa-trash-o"></i> ระงับ
							</button></a> -->
					<?php //} ?>
				</div>
			</div>

			<div class="row">

				<div class="col-md-8">

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">แม่แบบเกณฑ์การประเมิน :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $data->profile_name; ?></label>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">ปี :</label>
						</div>
						<div class="col-md-8 text-left">
							<label
								for="stext"><?php echo $data->year; ?></label>
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
					<div class="row">
						<div class="col-md-4 text-right">
							<label for="stext">สถานะการใช้งาน :</label>
						</div>
						<div class="col-md-8 text-left">
							<label for="stext"><?php echo $status_list[$data->status]; ?></label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
