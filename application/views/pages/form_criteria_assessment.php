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
		<a href="<?php echo $prev; ?>" class="table-link">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button>
		</a>
		<a href="#" class="table-link"  onclick="addMainData()">
			<button type="button" class="btn btn-xs btn-success">
				<i class="fa fa-plus"></i> เพิ่มหมวดหมู่ / เกณฑ์การประเมิน
			</button>
		</a>
	</div>
</div>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
				<div class="row">
				<div class="col-md-5">
					<div class="" id="left_box"></div>
				</div>
				<div class="col-md-7" style="border-left: 1px solid #cccccc;">
					<form method="post" enctype="multipart/form-data" id="criteria_form" action="<?php echo $action; ?>" style="display:none">
						<input type="hidden" name="profile_id" id="profile_id" value="<?php echo $profile_id; ?>">
						<input type="hidden" name="parent_id" id="parent_id" value="0">
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
								<a href="#" class="btn btn-sm btn-danger" onclick="reset()">
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
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<style>
	#left_box{
		height: 400px;
		overflow-x: hidden;
		overflow-y: auto;
	}
</style>
<script type="text/javascript">

		function getData(){
			$.ajax({
					url: '<?php echo base_url('criteria_assessments/ajax_get_data/'.$profile_id); ?>',
					type: "GET",
					success: function (data) {
						$('#left_box').html(data)
					},
			})
		}



		function addMainData() {
			$('#criteria_form').show();
			$('#parent_id').val(0);
		}

		function saveData() {
			// alert('a');
		}

		function reset() {
			$('#criteria_form').hide();
			$('#parent_id').val(0);
		}

		function addData(id) {
			$('#criteria_form').show();
			$('#parent_id').val(id)
			$('#criteria_name').val('')
		}

		function editData(id) {
			// alert('edit data')
		}

		function deleteData() {
			// alert('delete data')
		}
    jQuery(document).ready(function () {
				getData();
				jQuery("#criteria_form").hide();
        jQuery("#criteria_form").submit(function (e) {
						$.ajax({
									url: '<?php echo $action; ?>',
									type: "POST",
									data:  $(this).serialize(),
									success: function (data) {
										getData();
									},

							})
						e.preventDefault()
        });
    });
</script>
