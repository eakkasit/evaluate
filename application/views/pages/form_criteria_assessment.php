<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_assessments/save");
$prev = base_url("criteria_assessments/dashboard_criteria_assessments");
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
					<div class="" id="left_box"></div>
				</div>
				<div class="col-md-7" style="border-left: 1px solid #cccccc;">
					<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">

						<div class="row">
							<div class="col-md-12">
								<p class="h2 text-primary">หมวดหมู่ / เกณฑ์การประเมิน</p>
							</div>
						</div>
						<input type="hidden" name="criteria_id" id="criteria_id" value=""/>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อหมวด / เกณฑ์การประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control" id="criteria_name" name="criteria_name" value=""/>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("criteria_name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="tabbable">
									<ul class="nav nav-tabs padding-12 " id="myTab4">
										<li class="active">
											<a data-toggle="tab" href="#variable" aria-expanded="true">ค่าตัวแปร</a>
										</li>

										<li class="">
											<a data-toggle="tab" href="#weight" aria-expanded="false">ค่าน้ำหนัก</a>
										</li>

									</ul>

									<div class="tab-content">
										<div id="variable" class="tab-pane active">
											<p>Raw denim you probably haven't heard of them jean shorts Austin.</p>
										</div>

										<div id="weight" class="tab-pane">
											<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
										</div>
									</div>
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
		function save_data(id){
		var p_text = 'data-'+id;
		var text_detail = 'detail_'+id;
		var btn_save = 'btn-record-save-'+id;
		var btn_edit = 'btn-record-edit-'+id;
		var btn_cancle = 'btn-record-cancle-'+id;

		swal({
					title: "ยืนยันแก้ไขข้อมูล",
					text: "ต้องการแก้ไขข้อมูลบันทึกการประชุมใช่หรือไม่",
					type: "info",
					showCancelButton: true,
					confirmButtonText: "ตกลง",
					cancelButtonText: "ยกเลิก",
			},
			function (isConfirm) {
					if (isConfirm) {
						$.ajax({
								url: '<?php echo base_url('records/update'); ?>',
								type: "POST",
								data: {
									record_id:id,
									record_detail:$("."+text_detail).val()
								},
								success: function (data) {
										$("."+text_detail).addClass('hidden');
										$("."+text_detail).val(data);
										$("."+p_text).html(data);
										$("."+p_text).show();
										$("."+btn_edit).show();
										$("."+btn_save).addClass('hidden');
										$("."+btn_cancle).addClass('hidden');
								},

						})
					}
			});
		}

		function getData(){
			$.ajax({
					url: '<?php echo base_url('criteria_assessments/ajax_get_data/1'); ?>',
					type: "GET",
					success: function (data) {
						$('#left_box').html(data)
					},
			})
		}
    jQuery(document).ready(function () {
				getData();
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
