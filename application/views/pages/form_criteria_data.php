<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_datas/save");
$prev = base_url("criteria_datas/dashboard_criteria_datas");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("criteria_datas/view_criteria_data/{$data->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> <?php echo $title_prefix; ?>การประเมินองค์กรรายปี
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div class="row">

					<div class="col-md-8">

						<div class="row">
							<div class="col-md-12">
								<label for="stext">ชื่อการประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" name="name" class="form-control"  value="<?php if (isset($data->name)) { echo $data->name;	} ?>" placeholder="ระบุ">
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("name"); ?></label>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label for="stext">เกณฑ์แม่แบบการประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<select class="form-control" name="profile_id">
									<?php
									if(isset($criteria_profiles) && !empty($criteria_profiles)){
										foreach ($criteria_profiles as $key => $criteria_profile) {
											?>
											<option value="<?php echo $criteria_profile->id; ?>" <?php if (isset($data->profile_id) && $data->profile_id == $key) {
											echo 'selected="selected"';
										} ?>><?php echo $criteria_profile->profile_name; ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
							<label
								class="col-md-12 text-danger"><?php echo form_error("profile_id"); ?></label>
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
								<label for="stext">บันทึกการประเมิน</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="criteria_data"></div>
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
