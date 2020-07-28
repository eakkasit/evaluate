<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_assessments/save");
$prev = base_url("criteria_assessments/dashboard_criteria_assessments");
$btn_img_txt = 'เพิ่มรูป';
$btn_img_dsp = false;
if (isset($user_data->user_id) && $user_data->user_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$user_data->user_id}";
	$prev = base_url("criteria_assessments/view_criteria_assessment/{$user_data->user_id}");
}
?>

<div class="row">
	<div class="col-md-6">
		<p class="h4  text-success">
			<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>หมวดหมู่ / เกณฑ์การประเมิน
		</p>
	</div>
	<div class="col-md-6 text-right">
		<a href="" class="table-link">
			<button type="button" class="btn btn-xs btn-success">
				<i class="fa fa-plus"></i> เพิ่มหมวดหมู่ / เกณฑ์การประเมิน
			</button>
		</a>
	</div>
</div>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">
				<div class="col-md-5">
					<?php
					if (isset($agendas) && !empty($agendas)) {
						foreach ($agendas as $value) {
							?>
							<div class="row">
								<a href="<?php echo "{$edit}/{$value->agenda_id}"; ?>">
									<div class="col-md-12">
										<div
											class="panel panel-<?php echo (isset($agenda->agenda_id) && $value->agenda_id == $agenda->agenda_id) ? 'primary' : 'default'; ?>">
											<div class="row">
												<div class="col-md-offset-1 col-md-10">
													<p class="h3">
														วาระที่ <?php echo number_format($value->agenda_no, 0); ?></p>
													<p class="text-muted">เรื่อง <?php echo $value->agenda_name; ?></p>
												</div>
											</div>
										</div>
									</div>
								</a>
							</div>
							<?php
						}
					}
					?>
				</div>
				<div class="col-md-7" style="border-left: 1px solid #cccccc;">
					<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
						<input type="hidden" name="meeting_id" value="<?php if (isset($meeting_data->meeting_id)) {
							echo $meeting_data->meeting_id;
						} ?>"/>
						<input type="hidden" name="agenda_id" value="<?php
						 if (isset($agenda->agenda_id)) {
							echo $agenda->agenda_id;
						} ?>"/>
						<input type="hidden" name="user_id" value="<?php
						if (isset($user_id)) {
							echo $user_id;
						}
						?>"/>

						<div class="row">
							<div class="col-md-12">
								<p class="h2 text-primary">
									วาระที่ <?php //echo number_format($agenda->agenda_no, 0); ?></p>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">วาระเรื่อง</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control" value="<?php
								if (isset($agenda->agenda_name)) {
									echo $agenda->agenda_name;
								}
								?>" readonly="readonly"/>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">เนื้อหา</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
						<textarea class="form-control" rows="3" readonly="readonly"><?php
							if (isset($agenda->agenda_detail)) {
								echo $agenda->agenda_detail;
							}
							?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("agenda_detail"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">บันทึกการประชุม <font class="text-danger">*</font></label>
								<?php if (isset($configs['speach_to_text']) && in_array(strtolower($configs['speach_to_text']->config_status), array('active'))) { ?>
									<button type="button" id='btn-transcribe'
											class="btn btn-sm btn-primary btn-xs pull-right">
										<i class="fa fa-microphone"></i>
										บันทึก
									</button>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
						<textarea name="record_detail" id="record_detail" class="form-control" placeholder="ระบุ"
								  rows="3"><?php
							if (isset($record->record_detail)) {
								echo $record->record_detail;
							}
							?></textarea>
							</div>
							<label
								class="col-md-12 text-danger info-mic"><?php echo form_error("record_detail"); ?></label>

							<blockquote class="blockquote">
								<div id="results">
									<span class="final" id="final_span"></span>
									<span class="interim" id="interim_span"></span>
								</div>
							</blockquote>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ผู้บันทึก</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php
								if (isset($user_fullname)) {
									echo $user_fullname;
								}
								?>
							</div>
						</div>
						<div class="tabbable">
							<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
								<li class="active">
									<a data-toggle="tab" href="#home4" aria-expanded="true">Home</a>
								</li>

								<li class="">
									<a data-toggle="tab" href="#profile4" aria-expanded="false">Profile</a>
								</li>

								<li class="">
									<a data-toggle="tab" href="#dropdown14" aria-expanded="false">More</a>
								</li>
							</ul>

							<div class="tab-content">
								<div id="home4" class="tab-pane active">
									<p>Raw denim you probably haven't heard of them jean shorts Austin.</p>
								</div>

								<div id="profile4" class="tab-pane">
									<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
								</div>

								<div id="dropdown14" class="tab-pane">
									<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade.</p>
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

						<hr/>

						<?php if (isset($files) && !empty($files)) { ?>
							<div class="row">
								<div class="col-md-12">
									<p class="h3"><i class="fa fa-paperclip"></i> เอกสารแนบ</p>
								</div>
							</div>

							<div class="row">
								<div class="col-md-offset-1 col-md-11">
									<ul class="list-unstyled">
										<?php foreach ($files as $file) { ?>
											<li>
												<p>
													<a href="<?php echo base_url("assets/attaches/{$meeting_data->meeting_id}/{$file->agenda_filename}"); ?>"
													   target="_blank"
													   class="table-link label label-<?php echo class_file_type($file->agenda_filename); ?>"><?php echo $file->agenda_detail; ?></a>
												</p>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						<?php } ?>
					</form>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
    function deletePicture() {
        $('input[name=profile_picture_tmp]').val('');
        $('#preview_picture').attr('src', '<?php echo $profile_picture_default; ?>');
    }

    jQuery(document).ready(function () {
        jQuery("#profile_picture").change(function () {

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    jQuery('#preview_picture').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
