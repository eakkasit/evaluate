<?php
$title_prefix = 'เพิ่ม';
$edit = base_url("agendas/edit_agenda/{$meeting_data->meeting_id}");
$action = base_url("agendas/save/{$meeting_data->meeting_id}");
$prev = base_url("agendas/view_agenda/{$meeting_data->meeting_id}");
$agenda_no = count($agendas) + 1;
if (isset($agenda->agenda_id) && $agenda->agenda_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$agenda->agenda_id}";
	$agenda_no = $agenda->agenda_no;
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>วาระการประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-5">

					<div class="row">
						<?php if (isset($agenda->agenda_id) && $agenda->agenda_id != '') { ?>
							<div class="col-md-12">
								<a href="<?php echo base_url("agendas/new_agenda/{$meeting_data->meeting_id}"); ?>"
								   class="table-link pull-left" title="เพิ่มวาระการประชุม">
									<button type="button" class="btn btn-xs btn-primary">
										<i class="fa fa-edit"></i> เพิ่มวาระการประชุม
									</button>
								</a>
							</div>

							<div class="col-md-12">&nbsp;</div>
						<?php } ?>
					</div>

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
					} else {
						?>
						<div class="row">
							<div class="col-md-12">
								<div
									class="panel panel-default">
									<div class="row">
										<div class="col-md-offset-1 col-md-10">
											<p class="h5 text-center text-muted">ไม่มีข้อมูลวาระการประชุมให้แสดง</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<div class="col-md-7" style="border-left: 1px solid #cccccc;">
					<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
						<input type="hidden" name="meeting_id" value="<?php if (isset($meeting_data->meeting_id)) {
							echo $meeting_data->meeting_id;
						} ?>"/>
						<input type="hidden" name="agenda_no" value="<?php echo $agenda_no; ?>"/>

						<div class="row">
							<div class="col-md-12">
								<p class="h2 text-primary">
									วาระที่ <?php echo number_format($agenda_no, 0); ?>
								</p>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">วาระเรื่อง <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="agenda_name" class="form-control"
									   value="<?php if (isset($agenda->agenda_name)) {
										   echo $agenda->agenda_name;
									   } ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("agenda_name"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">วาระ <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<select name="agenda_type_id" class="form-control">
									<?php foreach ($type_list as $key => $value) { ?>
										<option
											value="<?php echo $key; ?>" <?php if (isset($agenda->agenda_type_id) && $agenda->agenda_type_id == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $value; ?></option>
									<?php } ?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("agenda_type_id"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">เรื่องเดิม</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
						<textarea name="agenda_story" class="form-control" placeholder="ระบุ" rows="3"><?php
							if (isset($agenda->agenda_story)) {
								echo $agenda->agenda_story;
							}
							?></textarea>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("agenda_story"); ?></label>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="stext">เนื้อหา <font class="text-danger">*</font></label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
						<textarea name="agenda_detail" class="form-control" placeholder="ระบุ" rows="3"><?php
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
								<label for="stext">เอกสารแนบ</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="file" id="agenda_file" name="agenda_file[]"
									   class="form-control"
									   style="display: none;" multiple/>

								<button type="button" onclick="agenda_file.click();" class="btn btn-sm btn-primary">
									<i class="fa fa-paperclip"></i> เพิ่มเอกสารแนบ
								</button>
							</div>
							<label
								class="col-md-12 text-danger"><?php if (isset($upload_msg)) {
									echo $upload_msg;
								} ?></label>
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

													<a href="#"
													   onclick="delete_file(<?php echo "{$meeting_data->meeting_id}, {$file->agenda_id}, {$file->agenda_file_id}"; ?>);"
													   title="ลบเอกสารแนบ <?php echo $file->agenda_detail; ?>"
													   class="table-link label label-danger"><i
															class="fa fa-trash-o"></i> ลบ</a>
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

<script type="text/javascript">
    function delete_file(meeting_id, agenda_id, agenda_file_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการลบเอกสารแนบนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("agendas/delete_agenda_file/"); ?>' + meeting_id + '/' + agenda_id + '/' + agenda_file_id;
                }
            });
    }
</script>
