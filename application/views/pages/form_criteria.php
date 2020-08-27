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
<form  method="post" action="" name="formvariable" id="formvariable">
	 <div id="myModal" class="modal fade" role="dialog">
		 <div class="modal-dialog">

			 <!-- Modal content-->
			 <div class="modal-content">
				 <div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					 <h4 class="modal-title">ตัวแปร</h4>
				 </div>
				 <div class="modal-body">
					 <input type="hidden" name="structure_id" id="structure_id" value="<?php echo $structure_id; ?>">
					 <input type="hidden" name="tree_id" id="tree_id" value="">
					 <div id="variable_data"></div>
				 </div>
				 <div class="modal-footer">
					 <input type="button" value="Save" onclick="addRow('dataTable')" class="btn btn-info "  id="addpro" style="display:none"  data-dismiss="modal"/>
					 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					 <button type="submit" name="savechange" value="savechange" class="btn btn-success" id="savechange" >บันทึก</button>
				 </div>
			 </div>

		 </div>
	 </div>
 </form>
<script type="text/javascript">
	var dependvar = {};
	var dependcond = {};
		function getData(){
			$.ajax({
					url: '<?php echo base_url('criteria/ajax_kpi_tree/'.$structure_id); ?>',
					type: "GET",
					success: function (data) {
						$('#kpi_list').html(data)
						depend_process();
					},
			})
		}

		function show_variable(kpi_id,tree_id,kpi_standard_type) {
			$.ajax({
				url: '<?php echo base_url('criteria/ajax_var_data/'); ?>' + kpi_id + '/' + tree_id +'/'+kpi_standard_type ,
				type: "GET",
				success: function (data) {
					$('#variable_data').html(data)
					$('#tree_id').val(tree_id)
					depend_process()
					$('#myModal').modal('show');
				},
			})
		}

    function saveform(form){
        // Get first form element
        var $form = $('form')[0];

        // Check if valid using HTML5 checkValidity() builtin function
        if ($form.checkValidity()) {
            console.log('valid');
            $form.submit();
        } else {
            console.log('not valid');
        }
        return false;
    }

    function setval(str,obj,val) {
        if(obj.checked) {
            $("input[name='" + str + "']").val(val);
        }else{
            $("input[name='" + str + "']").val(0);
        }
    }

    function depend(input,dp) {
        //alert(dependvar['v2']);
        //alert(input.type);
        if(input.type == 'checkbox'){
            if(input.checked) {
                dependvar[dp] = 1;
            }else{
                dependvar[dp] = 0;
            }
        }else{
            if(input.value) {
                dependvar[dp] = 1;
            }else{
                dependvar[dp] = 0;
            }
        }

        depend_process();
    }

    function depend_process(){
        var txtdp='';
        for(var dp in dependcond){
					// console.log('txtdp',dp);
            txtdp = dependcond[dp];
            if(txtdp==''){
                txtdp = '1';
            }
            //alert("textdp="+txtdp);
            for(var dpv in dependvar){
                txtdp = txtdp.replace(dpv,dependvar[dpv]);
                //alert(dpv+"="+dependvar[dpv]);
            }
            //alert("textdp-out="+txtdp);
						// console.log('txtdp',txtdp);
            var dp_out = eval(txtdp);
            if(dp_out){
                $('.depend_'+dp).prop( "disabled", false );
            }else{
                $('.depend_'+dp).prop( "disabled", true );
            }
        }
    }

    jQuery(document).ready(function () {
				getData();

				setTimeout(function(){
					$("input[class*='percent_total_']").each(function(i,e){
						var total_id = $(this).attr('data-percent')

						var sum_data = 0;
						var $percent = '.percent_'+total_id
						$($percent).each(function(e){
							sum_data += parseInt($(this).val());
						})
						$(this).val((sum_data/$($percent).length).toFixed(2))
					})
				} , 1000);

				jQuery("#formvariable").submit(function(e){
					var err_text = ''
						$.ajax({
							url: '<?php echo base_url("criteria/ajax_save_variable_data"); ?>',
							type: "POST",
							data:  $(this).serialize(),
							success: function (data) {
								// console.log(data);
								// $('#variable-table tbody').append(data);
								// // addRowVariable(data)
								// $('#addKpi').modal('hide')
								// aaaaa();
								// window.location.reload();
								// console.log('data',data)
							},
							error:function (error) {
								console.log('err',error)
							}
						})


					e.preventDefault();
				})

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
