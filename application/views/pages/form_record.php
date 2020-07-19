<?php
$edit = base_url("records/edit_record/{$meeting_data->meeting_id}");
$action = base_url("records/save/{$meeting_data->meeting_id}/{$agenda->agenda_id}");
$prev = base_url("records/view_record/{$meeting_data->meeting_id}");
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> บันทึกการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
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
						<input type="hidden" name="agenda_id" value="<?php if (isset($agenda->agenda_id)) {
							echo $agenda->agenda_id;
						} ?>"/><input type="hidden" name="user_id" value="<?php
						if (isset($user_id)) {
							echo $user_id;
						}
						?>"/>

						<div class="row">
							<div class="col-md-12">
								<p class="h2 text-primary">
									วาระที่ <?php echo number_format($agenda->agenda_no, 0); ?></p>
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
		</div>
	</div>
</div>
<?php
if (isset($configs['speach_to_text']) && in_array(strtolower($configs['speach_to_text']->config_status), array('active'))) {
	$data = array(
		'elements' => array(
			'button' => '#btn-transcribe',
			'info' => '.info-mic',
			'output' => '#record_detail',
		)
	);
	$this->load->view('template/speech_to_text.php', $data);
}
?>
