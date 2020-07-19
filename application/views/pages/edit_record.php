<?php
$edit = base_url("records/edit_record/{$meeting_data->meeting_id}");
$action = base_url("records/save/{$meeting_data->meeting_id}/{$agenda->agenda_id}");
$prev = base_url("records/view_record/{$meeting_data->meeting_id}");
?>
<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> แก้ไขบันทึกการประชุม
</p>
<div class="row">
	<div class="col-md-12 text-right">

		<a href="<?php echo $prev ?>"
			 class="table-link" title="ย้อนกลับ">
			<button type="button" class="btn btn-xs btn-info">
				<i class="fa fa-arrow-left"></i> ย้อนกลับ
			</button></a>
	</div>
</div>
<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12">
					<table role="grid" id="table-example"
						   class="table table-bordered table-hover dataTable no-footer">
						<thead>
						<tr role="row">
							<th class="text-center start_no" width="6%">ลำดับ</th>
							<th class="text-center" width="26%">ชื่อ นามสกุล</th>
							<th class="text-center" width="38%">บันทึกการประชุม</th>
							<th class="text-center" width="20%">ปรับปรุงข้อมูลล่าสุดเมื่อ</th>
							<th class="text-center" width="10%"></th>
						</tr>
						</thead>
						<tbody>
							<?php
							if($record){
								foreach ($record as $key => $records) {
									?>
									<tr>
										<td><?php echo $key+1 ?></td>
										<td><?php echo $prefix_list[$records->prename].$records->name." ".$records->surname?></td>
										<td>
											<p class="data-<?php echo $records->record_id ?>"><?php echo $records->record_detail; ?></p>
											<textarea cols="50" rows="5" class="detail_<?php echo $records->record_id ?> hidden"><?php echo $records->record_detail; ?></textarea>
										</td>
										<td><?php echo date_thai($records->update_date); ?></td>
										<td class="text-center">
											<a  onclick="edit_data(<?php echo $records->record_id ?>)" class="table-link btn-record-edit-<?php echo $records->record_id ?>" title="แก้ไข"> <button type="button" class="btn btn-xs btn-warning"> <i class="fa fa-edit"></i> แก้ไข</button></a>
											<a  onclick="save_data(<?php echo $records->record_id ?>)" class="table-link hidden btn-record-save-<?php echo $records->record_id ?>" title="บันทึก"> <button type="button" class="btn btn-xs btn-success"> <i class="fa fa-save"></i> บันทึก</button></a>
											<a  onclick="cancle_data(<?php echo $records->record_id ?>)" class="table-link hidden btn-record-cancle-<?php echo $records->record_id ?>" title="ยกเลิก"> <button type="button" class="btn btn-xs btn-danger"> ยกเลิก</button></a>
										</td>
									</tr>
									<?php
								}
							}else{
								?>
								<tr>
									<td colspan="5" class="text-center">ไม่มีข้อมูลบันทึกการประชุมให้แสดง</td>
								</tr>
								<?php
							}

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function edit_data(id){
		var p_text = 'data-'+id;
		var text_detail = 'detail_'+id;
		var btn_save = 'btn-record-save-'+id;
		var btn_edit = 'btn-record-edit-'+id;
		var btn_cancle = 'btn-record-cancle-'+id;
		$("."+text_detail).removeClass('hidden');
		$("."+p_text).hide();
		$("."+btn_edit).hide();
		$("."+btn_save).removeClass('hidden');
		$("."+btn_cancle).removeClass('hidden');
	}



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




	function cancle_data(id){
		var p_text = 'data-'+id;
		var text_detail = 'detail_'+id;
		var btn_save = 'btn-record-save-'+id;
		var btn_edit = 'btn-record-edit-'+id;
		var btn_cancle = 'btn-record-cancle-'+id;
		$("."+text_detail).addClass('hidden');
		$("."+text_detail).val($("."+p_text).text());
		$("."+p_text).show();
		$("."+btn_edit).show();
		$("."+btn_save).addClass('hidden');
		$("."+btn_cancle).addClass('hidden');
	}
</script>
