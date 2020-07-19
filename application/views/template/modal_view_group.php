<div id="group_users" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<table role="grid" id="table-example"
					   class="table table-bordered table-hover dataTable no-footer">
					<thead>
					<tr role="row">
						<th class="text-center start_no" width="10%">ลำดับ</th>
						<th class="text-center" width="40%">ชื่อ นามสกุล</th>
						<th class="text-center" width="25%">ตำแหน่ง</th>
						<th class="text-center" width="25%">หน่วยงาน</th>
					</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
					<i class="fa fa-times"></i>
					ปิด
				</button>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
    function groupUsers(group_id) {
        try {
            jQuery('#group_users .modal-title').html('');
            jQuery('#group_users .modal-body table>tbody').html('');

            jQuery.get('<?php echo base_url("groups/get_group_users/"); ?>' + group_id, function (data) {
                group = jQuery.parseJSON(data);
                jQuery('#group_users .modal-title').html(group.group_name);
                if (group.users_list.length > 0) {
                    jQuery.each(group.users_list, function (index, value) {
                        jQuery('#group_users .modal-body table>tbody').append('<tr class="odd" role="row">' +
							<?php /*'<td class="text-center"><img class="col-xs-12 img-circle" src="' + value.img + '" draggable="false"></td>' +*/ ?>
                            '<td class="text-center">' + (index + 1).toLocaleString() + '</td>' +
                            '<td class="text-left">' + value.full_name + '</td>' +
                            '<td class="text-left">' + value.position_code + '</td>' +
                            '<td class="text-left">' + value.department + '</td>' +
                            '</tr>');
                    });
                } else {
                    jQuery('#group_users .modal-body table>tbody').append('<tr class="odd" role="row">' +
                        '<td class="text-center" colspan="4">ไม่มีข้อมูลรายชื่อผู้ประชุมให้แสดง</td>' +
                        '</tr>');
                }

            });
        } catch (e) {
            console.log(e)
        }
    }
</script>
