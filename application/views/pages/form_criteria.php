<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria_datas/save");
$prev = base_url("criteria_datas/dashboard_criteria_datas");
$ajax_form_url = base_url("criteria_datas/ajax_get_data_form/");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("criteria_datas/view_criteria_data/{$data->id}");
}
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> บันทึกการประเมินองค์กรรายปี
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form method="post" enctype="multipart/form-data" action="<?php echo $action; ?>">
				<div id="kpi_list"></div>
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

<script type="text/javascript">

		function getData(){
			$.ajax({
					url: '<?php echo base_url('criteria/ajax_kpi_tree/'.$structure_id); ?>',
					type: "GET",
					success: function (data) {
						$('#kpi_list').html(data)
					},
			})
		}
    jQuery(document).ready(function () {
				getData();
    });
</script>
<style>
	.dd-item .row{
		padding: 1px
	}
	.mini-box{
		width: 40px !important;
		margin: 0px 1px;
	}
</style>
