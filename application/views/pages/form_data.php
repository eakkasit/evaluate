<?php
$title_prefix = 'เพิ่ม';
$action = base_url("datas/save");
$prev = base_url("datas/dashboard_datas");
if (isset($data_data->meeting_id) && $data_data->meeting_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data_data->meeting_id}";
	$prev = base_url("datas/view_data/{$data_data->meeting_id}");
}
?>
<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">

	<p class="h4 header text-success">
		<i class="fa fa-file-text-o"></i> การประชุม
	</p>

	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#tab1" data-toggle="tab">
				<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>การประชุม
			</a>
		</li>
		<li>
			<a href="#tab2" data-toggle="tab">
				<i class="fa fa-users"></i> องค์คณะประชุม
			</a>
		</li>
		<li>
			<a href="#tab3" data-toggle="tab">
				<i class="fa fa-user"></i> บัญชีรายชื่อผู้ประชุมภายนอก
			</a>
		</li>
		<div class="text-center pull-right">
			<a href="<?php echo $prev; ?>" class="btn btn-sm btn-danger">
				<i class="fa fa-times"></i>
				ยกเลิก
			</a>

			<button class="btn btn-sm btn-success" type="submit" id="submit">
				<i class="fa fa-floppy-o"></i>
				บันทึก
			</button>
		</div>
	</ul>

	<div class="tab-content ">
		<div class="tab-pane active" id="tab1">
			<div class="widget-body">
				<div class="widget-main">

					<div class="row">
						<div class="col-md-12">
							<label for="stext">ชื่อการประชุม <font class="text-danger">*</font></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="text" name="meeting_name" class="form-control"
								   value="<?php if (isset($data_data->meeting_name)) {
									   echo $data_data->meeting_name;
								   } ?>" placeholder="ระบุ">
						</div>
						<label
							class="col-md-12 text-danger"><?php echo form_error("meeting_name"); ?></label>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label for="stext">โครงการ</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="text" name="meeting_project" class="form-control"
								   value="<?php if (isset($data_data->meeting_project)) {
									   echo $data_data->meeting_project;
								   } ?>" placeholder="ระบุ">
						</div>
						<label
							class="col-md-12 text-danger"><?php echo form_error("meeting_project"); ?></label>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label for="stext">รายละเอียดการประชุม</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
						<textarea name="meeting_description" class="form-control" placeholder="ระบุ" rows="3"><?php
							if (isset($data_data->meeting_description)) {
								echo $data_data->meeting_description;
							}
							?></textarea>
						</div>
						<label
							class="col-md-12 text-danger"><?php echo form_error("meeting_description"); ?></label>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label for="stext">วันที่ประชุม <font class="text-danger">*</font></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<input type="text" name="meeting_date" class="form-control" id="meeting_date"
								   value="<?php if (isset($data_data->meeting_date)) {
									   echo $data_data->meeting_date;
								   } else {
									   echo date('Y-m-d');
								   } ?>" placeholder="ระบุ">
						</div>
						<label
							class="col-md-12 text-danger"><?php echo form_error("meeting_date"); ?></label>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label for="stext">เวลาเริ่ม <font class="text-danger">*</font></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<input type="text" name="meeting_starttime" class="form-control" id="meeting_starttime"
								   value="<?php if (isset($data_data->meeting_starttime)) {
									   echo $data_data->meeting_starttime;
								   } ?>" placeholder="ระบุ">
						</div>
						<label
							class="col-md-12 text-danger"><?php echo form_error("meeting_starttime"); ?></label>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label for="stext">เวลาสิ้นสุด <font class="text-danger">*</font></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<input type="text" name="meeting_endtime" class="form-control" id="meeting_endtime"
								   value="<?php if (isset($data_data->meeting_endtime)) {
									   echo $data_data->meeting_endtime;
								   } ?>" placeholder="ระบุ">
						</div>
						<label
							class="col-md-12 text-danger"><?php echo form_error("meeting_endtime"); ?></label>
					</div>

					<div class="row">
						<div class="col-md-12">
							<label for="stext">ห้องประชุม</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="text" name="meeting_room" class="form-control"
								   value="<?php if (isset($data_data->meeting_room)) {
									   echo $data_data->meeting_room;
								   } ?>" placeholder="ระบุ">
						</div>
						<label
							class="col-md-12 text-danger"><?php echo form_error("meeting_room"); ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab2">
			<div class="table-responsive">
				<table class="table table-bordered table-hover dataTable no-footer">
					<thead>
					<tr>
						<th class="text-center" width="10%">
							<input type="checkbox" id="checkAll"/>
						</th>
						<th class="text-center" width="30%">ชื่อองค์คณะประชุม</th>
						<th class="text-center" width="40%">รายละเอียด</th>
						<th class="text-center" width="20%"></th>
					</tr>
					</thead>
					<tbody>
					<?php
					$array_tmp = array();
					if (isset($groups_list) && !empty($groups_list)) {
						foreach ($groups_list as $group) {
							$array_tmp[$group->group_id] = $group->group_id;
							?>
							<tr>
								<td class="text-center">
									<input type="checkbox" name="groups[]" class="checkChild"
										   value="<?php echo $group->group_id; ?>" <?php if (isset($data_data->meeting_id) && $data_data->meeting_id == $group->meeting_id) {
										echo 'checked="checked"';
									} ?>/>
								</td>
								<td class="text-left"><?php echo $group->group_name; ?></td>
								<td class="text-left"><?php echo $group->group_description; ?></td>
								<td class="text-center white">
									<div>
										<a href="#" onclick="groupUsers(<?php echo $group->group_id; ?>);"
										   data-toggle="modal" data-target="#group_users"
										   class="table-link" title="แสดง">
											<button type="button" class="btn btn-xs btn-info">
												<i class="fa fa-eye"></i> แสดง
											</button>
										</a>
									</div>
								</td>
							</tr>
							<?php
						}
					}
					?>
					</tbody>
				</table>
				<?php
				if (isset($groups_list_selected) && !empty($groups_list_selected)) {
					foreach ($groups_list_selected as $group) {
						if (isset($array_tmp[$group->group_id])) continue;
						?>
						<input type="hidden" name="groups[]"
							   value="<?php echo $group->group_id; ?>"/>
						<?php
					}
				}
				?>
			</div>
			<div class="pagination pull-right">
				<?php $this->load->view("template/pagination"); ?>
			</div>
		</div>
		<div class="tab-pane" id="tab3">
			<?php $this->load->view("pages/form_user_temporary"); ?>
		</div>
	</div>

	<?php $this->load->view("template/modal_view_group"); ?>

	<script type="text/javascript">
        jQuery(document).ready(function () {
            $("#checkAll").click(function () {
                $('input:checkbox[class=checkChild]').not(this).prop('checked', this.checked);
            });

            jQuery.fn.datepicker.dates['th-TH'] = {
                days: ['วันอาทิตย์', 'วันจันทร์', 'วันอังคาร', 'วันพุธ', 'วันพฤหัสบดี', 'วันศุกร์', 'วันเสาร์'],
                daysShort: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
                daysMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                months: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
                monthsShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                today: 'วันนี้',
                clear: 'ล้างค่า',
                format: 'dd MM yyyy',
                titleFormat: 'MM yyyy', /* Leverages same syntax as 'format' */
                weekStart: 0
            };
            jQuery('#meeting_date').datepicker({
                format: 'yyyy-mm-dd',
                language: 'th-TH',
                autoclose: true,
            });

            jQuery('#meeting_starttime').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false,
                defaultTime: '00:00:00'
            });
            jQuery('#meeting_endtime').timepicker({
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false,
                defaultTime: '00:00:00'
            });
        });
	</script>
</form>
