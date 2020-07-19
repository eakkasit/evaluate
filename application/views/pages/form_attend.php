<?php
$edit = base_url("attends/view_attend/{$meeting_data->meeting_id}");
$action = base_url("attends/save/{$meeting_data->meeting_id}/{$agenda->agenda_id}");
$prev = base_url("attends/view_attend/{$meeting_data->meeting_id}");
?>
<?php 
 if(!$permit){
	?>
	<script>
		function no_access_meeting() {
			swal({
                    title: "ไม่สามารถเข้าร่วมประชุมได้",
                    text: "ไม่สามารถเข้าร่วมประชุมได้เนื่องจากท่านไม่ได้อยู่ในวาระการประชุมดังกล่าว",
                    type: "info",
                    showCancelButton: false,
                    confirmButtonText: "ตกลง",
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.href = '<?php echo base_url("attends/dashboard_attends") ?>';
                    }
                });
			}
		jQuery(document).ready(function () {
			no_access_meeting();

		});
		
	</script>
	<?php
 }else{
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> เข้าร่วมการประชุม วาระที่ <?php echo number_format($agenda->agenda_no, 0) ?>
	<br/>เรื่อง <?php echo $agenda->agenda_name ?>
</p>
<div class="row">
	<div class="col-md-12 text-right">
		<a href="<?php echo $prev ?>"
		   class="table-link" title="ย้อนกลับ">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button></a>
			&nbsp;&nbsp;
		<?php if (empty($user_meeting) && empty($meeting_status->conclusion)) { ?>
			<a href="#"
			   class="table-link"
			   onclick="join_meeting();" title="เข้าร่วมวาระการประชุม">
				<button type="button" class="btn btn-xs btn-success">
					<i class="fa fa-play"></i> เข้าร่วมวาระการประชุม
				</button></a>
			<?php
		}
		if (!empty($user_meeting) && empty($meeting_status->conclusion) && empty($user_meeting->time_out)) {
			?>
			<a href="#"
			   class="table-link"
			   onclick="leave_meeting();" title="ออกจากวาระการประชุม">
				<button type="button" class="btn btn-xs btn-danger">
					<i class="fa fa-pause"></i> ออกจากวาระการประชุม
				</button></a>
			<?php
		}
		if (empty($meeting_status)) {
			if ($user_type == '1') { ?>
				<a href="#"
				   class="table-link"
				   onclick="open_meeting('เปิดวาระการประชุม');" title="เปิดวาระการประชุม">
					<button type="button" class="btn btn-xs btn-success">
						<i class="fa fa-play"></i> เปิดวาระการประชุม
					</button></a>
			<?php } ?>
			<?php
		} else {
			if (empty($meeting_status->conclusion)) {
				?>
				<a href=""
				   class="table-link"
				   data-toggle="modal" data-target="#close_agenda_modal">
					<button type="button" class="btn btn-xs btn-success">
						<i class="fa fa-pause"></i> สิ้นสุดวาระการประชุม
					</button></a>
				<?php
			}
		}
		?>

	</div>
</div>
<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 widget-container-col">
					<div class="widget-box">
						<div class="widget-header widget-header-large">
							<h4 class="widget-title">บันทึกการประชุม</h4>

							<div class="widget-toolbar">

								<a href="#" data-action="reload" onclick="getHistory()" alt="refresh" title="refresh">
									<i class="ace-icon fa fa-refresh" title="refresh"></i>
								</a>

							</div>
						</div>

						<div class="widget-body">
							<div class="widget-main">
								<ol class="dd-list main">
								</ol>

								<div class="space"></div>
								<?php if (empty($meeting_status->conclusion)) { ?>
									<form method="post" enctype="multipart/form-data" id="form_record"
										  action="<?php echo $action; ?>">
										<div class="row">
											<div class="col-md-12">
												<label for="stext">บันทึกการประชุม <font
														class="text-danger">*</font></label>
												<?php if (isset($configs['speach_to_text']) && in_array(strtolower($configs['speach_to_text']->config_status), array('active'))) { ?>
													<button type="button" id='btn-transcribe'
															class="btn btn-sm btn-primary btn-xs pull-right">
														<i class="fa fa-microphone"></i>
														บันทึกด้วยเสียง
													</button>
												<?php } ?>
											</div>
										</div>

										<div class="widget-box widget-color-green">
											<div class="widget-header widget-header-small"></div>

											<div class="widget-body">
												<div class="widget-main ">

													<div class="row">
														<div class="col-md-12">
												<textarea name="record_detail" id="record_detail" class="form-control"
														  placeholder="ระบุ"
														  rows="5"></textarea>
															<input type="hidden" name="meeting_id"
																   value="<?php if (isset($meeting_data->meeting_id)) {
																	   echo $meeting_data->meeting_id;
																   } ?>"/>
															<input type="hidden" name="agenda_id"
																   value="<?php if (isset($agenda->agenda_id)) {
																	   echo $agenda->agenda_id;
																   } ?>"/>
															<input type="hidden" name="user_id"
																   value="<?php if (isset($user_id)) {
																	   echo $user_id;
																   } ?>"/>
															<input type="hidden" name="reply_id" value="" id="reply_id">
															<input type="hidden" name="record_id" value=""
																   id="record_id">
															<label
																class="col-md-12 text-danger info-mic"><?php echo form_error("record_detail"); ?></label>

															<blockquote class="blockquote">
																<div id="results">
																	<span class="final" id="final_span"></span>
																	<span class="interim" id="interim_span"></span>
																</div>
															</blockquote>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<label for="stext">เอกสารแนบ</label>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<input type="file" id="record_file" name="record_file[]"
																   class="form-control"
																   style="display: none;" multiple/>

															<button type="button" onclick="record_file.click();"
																	class="btn btn-sm btn-primary">
																<i class="fa fa-paperclip"></i> เพิ่มเอกสารแนบ
															</button>
														</div>
														<label
															class="col-md-12 text-danger"><?php if (isset($upload_msg)) {
																echo $upload_msg;
															} ?></label>
													</div>
													<div class="space"></div>
													<div class="row">
															<div class="col-md-12">
																<p><font class="text-danger">*</font> การบันทึกข้อมูลด้วยเสียงรองรับการใช้งานใน Chrome และ Microsoft Edge</p>
															</div>
													</div>
												</div>

												<div class="widget-toolbox padding-4 clearfix">
													<div class="btn-group pull-left">
														<button class="btn btn-sm btn-danger  btn-round">
															<i class="ace-icon fa fa-times bigger-125"></i> ยกเลิก
														</button>
													</div>

													<div class="btn-group pull-right">

														<button class="btn btn-sm btn-success  btn-round">
															<i class="ace-icon fa fa-floppy-o bigger-125"></i> บันทึก
														</button>
													</div>
												</div>
											</div>
										</div>
									</form>
								<?php } ?>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="close_agenda_modal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">ปิดวาระการประชุม</h4>
				</div>
				<div class="modal-body">
					<form
						action="<?php echo base_url("attends/status_data/{$meeting_data->meeting_id}/{$agenda->agenda_id}/close"); ?>"
						method="post" id="form_close_agenda">
						<div class="row">
							<div class="col-md-12">
								<label for="stext">มติที่ประชุม</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea class="form-control" rows="3" name="conclusion">รับทราบ</textarea>
								<input type="hidden" name="minutesdetail" value="">
								<input type="hidden" name="meeting_id" value="<?php echo $meeting_data->meeting_id ?>">
								<input type="hidden" name="agenda_id" value="<?php echo $agenda->agenda_id ?>">
							</div>

						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
						<i class="fa fa-times"></i>
						ยกเลิก
					</button>
					<button type="button" class="btn btn-xs btn-success" onclick="form_close_agenda.submit()">
						<i class="fa fa-save"></i>
						ปิดวาระการประชุม
					</button>
				</div>
			</div>

		</div>
	</div>


	<script type="text/javascript">
        var $path_assets = "<?php echo base_url(); ?>/assets";//this will be used in loading jQuery UI if needed!
	</script>
	<script type="text/javascript">
        function open_meeting() {
            swal({
                    title: "แจ้งเตือน",
                    text: "เปิดวาระการประชุม",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonText: "เริ่ม",
                    cancelButtonText: "ยกเลิก",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.href = '<?php echo base_url("attends/status_data/{$meeting_data->meeting_id}/{$agenda->agenda_id}/open"); ?>';
                    }
                });
        }

		<?php if (empty($user_meeting) && empty($meeting_status->conclusion)) { ?>
        function join_meeting() {
            swal({
                    title: "แจ้งเตือน",
                    text: "เข้าร่วมวาระการประชุม",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonText: "เข้าร่วม",
                    cancelButtonText: "ยกเลิก",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.href = '<?php echo base_url("attends/save_record_log/{$meeting_data->meeting_id}/{$agenda->agenda_id}/{$user_id}/in"); ?>';
                    }
                });
        }
		<?php
		}
		if(!empty($user_meeting) && empty($meeting_status->conclusion) && empty($user_meeting->time_out)){
		?>
        function leave_meeting() {
            swal({
                    title: "แจ้งเตือน",
                    text: "ออกจากวาระการประชุม",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonText: "ออก",
                    cancelButtonText: "ยกเลิก",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.href = '<?php echo base_url("attends/save_record_log/{$meeting_data->meeting_id}/{$agenda->agenda_id}/{$user_id}/out"); ?>';
                    }
                });
        }

		<?php } ?>

        var reply_comment;
        var edit_comment;
        var getHistory;
        var form_data;
        jQuery(document).ready(function () {
            jQuery(".edit-record").click(function (e) {
                var comment = $(this).closest('.comment-detail').find('p').text()
                var record_id = $(this).attr('data-attr-id')
                var trimcomment = $.trim(comment);
                $('#record_detail').html(trimcomment)
                $('#record_id').val(record_id)
                e.preventDefault()
            });

            jQuery(".reply-record").click(function (e) {
                var reply_id = $(this).attr('data-attr-id')
                $('#record_detail').text('')
                $('#reply_id').val(reply_id)
                e.preventDefault()
            });

            jQuery("ol.dd-list.main").on("click", ".edit-record", function () {
                var comment = $(this).closest('.widget-body').find('p').text()
                var record_id = $(this).attr('data-id')
                var trimcomment = $.trim(comment);
                $('#record_detail').html(trimcomment)
                $('#record_detail').focus()
                $('#record_id').val(record_id)
            })

            jQuery("#form_record").submit(function (e) {
                e.preventDefault();
                form_data = new FormData(this);
                console.log(this);
                if ($('#record_detail').val() != '') {
                    $.ajax({
                        url: '<?php echo $action; ?>',
                        type: "POST",
                        data: form_data,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (data) {
                            if ($('#record_id').val() == '') {
                                if ($("#reply_id").val() != '') {
                                    var temp_data = []
                                    temp_data.push(data)
                                    addReply(temp_data, $("#reply_id").val())
                                } else {
                                    addComment(data)
                                }
                            } else {
                                getHistory();
                            }
                            $('#form_record').find("input[type=text], textarea").val("");
                        },

                    })
                } else {
                    $('.info-mic').text('กรุณาระบุ บันทึกการประชุม')
                }
            })

            reply_comment = function (id) {
                $('#reply_id').val(id)
				$('#record_detail').html('')
                $('#record_detail').focus()
            }

            getHistory = function () {
                $('ol.dd-list.main').empty()
                $.ajax({
                    url: "<?php echo base_url("attends/get_history/{$meeting_data->meeting_id}/{$agenda->agenda_id}"); ?>/",
                    success: function (data) {
                        $('.dd-list.main').html(data)
                    },
                    error: function (xhr, status, error) {
                        console.log('err')
                    }
                })
            }

            function ScrollDown() {
                $('html').animate({scrollTop: $('ol.dd-list.main').prop('scrollHeight')}, 1000);
            }

            function addComment(data) {
                var comment = '';
                var position = data.position ? data.position : ''
                comment += '<li class="dd-item dd2-item" data-id="' + data.record_id + '">' +
                    '<div class="widget-box widget-color-blue">' +
                    '<div class="widget-header widget-header-small">' +
                    '<h4 class="widget-title">' + position + ' (' + data.fullname + ') กล่าว</h4>' +
                    '<div class="widget-toolbar">' +
                    '<span class="label label-info label-sm">' + moment(data.create_date).format('hh:mm') + '</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="widget-body">' +
                    '<div class="widget-main">' +
                    '<p class="alert alert-info">' + data.record_detail +
                    '</p>' +
                    '</div>' +
                    '<div class="widget-toolbox padding-4 clearfix">' +
                    '<div class="btn-group pull-left">' +
                    '</div>' +
                    '<div class="btn-group pull-right">' +
                    '<button class="btn btn-sm btn-danger btn-white btn-round" onclick="reply_comment(' + data.record_id + ')">' +
                    '<i class="ace-icon fa fa-reply bigger-125"></i>' +
                    'ตอบกลับ' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</li>';
                comment += '<div class="space"></div>';
                $('.dd-list.main').append(comment)
                if (data.reply != '' && data.reply) {
                    addReply(data.reply, data.record_id)
                }
            }

            function addReply(data, list) {
                var reply = '';
                $.each(data, function (index, value) {
                    var position = value.position ? value.position : ''
                    reply += '<div class="space"></div>';
                    reply += '<li class="dd-item dd2-item">' +
                        '<div class="widget-box widget-color-orange">' +
                        '<div class="widget-header widget-header-small">' +
                        '<h4 class="widget-title">' + position + ' (' + value.fullname + ') กล่าว</h4>' +
                        '<div class="widget-toolbar">' +
                        '<span class="label label-warning label-sm">' + moment(value.create_date).format('hh:mm') + '</span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="widget-body">' +
                        '<div class="widget-main">' +
                        '<p class="alert alert-warning">' + value.record_detail +
                        '</p>' +
                        '</div>' +
                        '<div class="widget-toolbox padding-4 clearfix">' +
                        '<div class="btn-group pull-left">' +
                        '</div>' +
                        '<div class="btn-group pull-right">' +
                        '<button class="btn btn-sm btn-danger btn-white btn-round">' +
                        '<i class="ace-icon fa fa-reply bigger-125"></i>' +
                        'ตอบกลับ' +
                        '</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</li>' +
                        '<div class="space"></div>';
                });
                if ($('li.dd-item[data-id="' + list + '"]').find('ol').length > 0) {
                    $('li.dd-item[data-id="' + list + '"]').find('ol').append(reply)
                } else {
                    var reply_data = '<ol class="dd-list sub">' + reply + '</ol>';
                    $('li.dd-item[data-id="' + list + '"]').append(reply_data)
                }
            }

            getHistory();

        });
	</script>
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
}
	?>
