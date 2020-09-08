<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_themes/save");
$prev = base_url("criteria_themes/dashboard_criteria_themes");
$btn_img_txt = 'เพิ่มรูป';
$btn_img_dsp = false;
if (isset($user_data->user_id) && $user_data->user_id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$user_data->user_id}";
	$prev = base_url("criteria_themes/view_criteria_theme/{$user_data->user_id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>รายชื่อผู้ประชุม
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">
						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ชื่อโครงการ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo $data->name; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ปีงบประมาณ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo $data->sql; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผู้รับผิดชอบ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo $data->sql; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">วัตถุประสงค์ :</label>
							</div>
							<div class="col-md-8 text-left">
								<label for="stext"><?php echo $data->sql; ?></label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 text-right">
								<label for="stext">ผลการดำเนินโครงการ :</label>
							</div>
							<div class="col-md-8 text-left">
								<input type="text" name="result" class="form-control" value="<?php if (isset($data->result)) { echo $data->result;	} ?>" placeholder="ระบุ">
							</div>
							<label class="col-md-12 text-danger"><?php echo form_error("result"); ?></label>
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
