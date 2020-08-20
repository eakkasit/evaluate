<?php
$title_prefix = 'เพิ่ม';
$action = base_url("criteria/dashboard_criteria");
$prev = base_url("criteria/dashboard_criteria");
$ajax_form_url = base_url("criteria/ajax_get_data_form/");
if (isset($data->id) && $data->id != '') {
	$title_prefix = 'แก้ไข';
	$action .= "/{$data->id}";
	$prev = base_url("criteria/view_criteria_data/{$data->id}");
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
<!-- Modal -->
	 <div id="myModal" class="modal fade" role="dialog">
		 <div class="modal-dialog">

			 <!-- Modal content-->
			 <div class="modal-content">
				 <div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					 <h4 class="modal-title">ตัวแปร</h4>
				 </div>
				 <div class="modal-body">
					 <div id="variable_data"></div>
				 </div>
				 <div class="modal-footer">
					 <input type="button" value="Save" onclick="addRow('dataTable')" class="btn btn-info "  id="addpro" style="display:none"  data-dismiss="modal"/>
					 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				 </div>
			 </div>

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

		function show_variable(kpi_id,kpi_standard_type) {
			$.ajax({
				url: '<?php echo base_url('criteria/ajax_var_data/'); ?>' + kpi_id + '/' + kpi_standard_type ,
				type: "GET",
				success: function (data) {
					$('#variable_data').html(data)
					$('#myModal').modal('show');
				},
			})
		}
    jQuery(document).ready(function () {
				getData();
				setTimeout(function(){
					console.log('1');
					$("input[class*='percent_total_']").each(function(i,e){
						var total_id = $(this).attr('data-percent')
						console.log(total_id);
						var sum_data = 0;
						var $percent = '.percent_'+total_id
						$($percent).each(function(e){
							sum_data += parseInt($(this).val());
						})
						$(this).val((sum_data/$($percent).length).toFixed(2))
					})
				} , 1000);

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
