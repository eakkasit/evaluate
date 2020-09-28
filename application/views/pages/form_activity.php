<?php
$title_prefix = 'เพิ่ม';
$action = base_url("activities/save");
$prev = base_url("activities/dashboard_activity");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("activities/view_activity/{$data->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>โครงการ / กิจกรรม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อโครงการ / กิจกรรม</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="project_name" class="form-control" value="<?php if (isset($data->project_name)) { echo $data->project_name;	} ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("project_name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ปี</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<select class="form-control" name="year">
									<?php foreach ($year_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->year) && $data->year == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("year"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ระยะเวลาดำเนินการ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
									<input type="text" name="date_start" id="date_start" class="form-control" value="<?php if (isset($data->date_start)) { echo $data->date_end;	} ?>" placeholder="เลือกวันที่เริ่มโครงการ / กิจกรรม">
							</div>
							<div class="col-md-2 text-center">
								<span> - </span>
							</div>
							<div class="col-md-5">
									<input type="text" name="date_end" id="date_end" class="form-control" value="<?php if (isset($data->date_start)) { echo $data->date_end;	} ?>" placeholder="เลือกวันที่สิ้นสุดโครงการ / กิจกรรม">
							</div>
							<label class="col-md-7 text-danger"><?php echo form_error("date_start"); ?></label>
							<label class="col-md-5 text-danger"><?php echo form_error("date_end"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-5">
								<label for="stext">ปีที่เริ่มโครงการ</label>
							</div>
							<div class="col-md-2"></div>
							<div class="col-md-5">
								<label for="stext">ปีที่สิ้นสุดโครงการ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<select class="form-control" name="year_start">
									<?php foreach ($year_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->year_start) && $data->year_start == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-2 text-center">
								<span> - </span>
							</div>
							<div class="col-md-5">
								<select class="form-control" name="year_end">
									<?php foreach ($year_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->year_end) && $data->year_end == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<!-- <label class="col-md-7 text-danger"><?php //echo form_error("date_start"); ?></label>
							<label class="col-md-5 text-danger"><?php //echo form_error("date_end"); ?></label> -->
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">สถานะโครงการ / กิจกรรม</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<select class="form-control" name="status">
									<?php foreach ($status_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>"<?php if (isset($data->status) && $data->status == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("status"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">รายละเอียด</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea type="text" name="detail" cols="4" rows="5" class="form-control" placeholder="ระบุ"><?php if (isset($data->detail)) { echo $data->detail;	} ?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("detail"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ค่าน้ำหนักโครงการ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
									<input type="number" name="weight" id="weight" class="form-control" value="<?php if (isset($data->weight)) { echo $data->weight;	} ?>" placeholder="ค่าน้ำหนักโครงการ">
							</div>
						</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						<a href="<?php echo $prev; ?>" class="btn btn-sm btn-danger">
							<i class="fa fa-times"></i>
							ยกเลิก
						</a>
						&nbsp;&nbsp;
						<button class="btn btn-sm btn-success" type="submit" id="submit">
							<i class="fa fa-floppy-o"></i>
							บันทึก
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
			jQuery(document).ready(function () {

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
					jQuery('#date_start').datepicker({
							format: 'yyyy-mm-dd',
							language: 'th-TH',
							autoclose: true,
					});

					jQuery('#date_end').datepicker({
							format: 'yyyy-mm-dd',
							language: 'th-TH',
							autoclose: true,
					});
			});
</script>
