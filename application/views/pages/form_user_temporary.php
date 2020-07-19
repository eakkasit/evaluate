<div class="table-responsive">
	<table role="grid" id="table-example"
		   class="table table-bordered table-hover dataTable no-footer">
		<thead>
		<tr role="row">
			<th class="text-center start_no" width="10%">ลำดับ</th>
			<th class="text-center" width="20%">ชื่อ นามสกุล</th>
			<th class="text-center" width="13%">ตำแหน่ง</th>
			<th class="text-center" width="13%">หน่วยงาน</th>
			<th class="text-center" width="10%">อีเมล</th>
			<th class="text-center" width="10%">โทรศัพท์</th>
			<th class="text-center" width="24%">
				<a href="#" onclick="form_temporary_user();" data-toggle="modal" data-target="#temporary_users"
				   class="table-link" title="จัดการผู้เข้าร่วมประชุม">
					<button type="button" class="btn btn-xs btn-success">
						<i class="fa fa-plus"></i> เพิ่มผู้เข้าร่วมประชุมภายนอก
					</button>
				</a>
			</th>
		</tr>
		</thead>
		<tbody id="table_users_temporary">
		<?php
		if (isset($users_temporary) && !empty($users_temporary)) {
			foreach ($users_temporary as $key => $user) {
				?>
				<tr class="odd" role="row" id="temporary_row_user_<?php echo $user->user_id; ?>">
					<td class="text-center">
						<?php echo number_format(($key + 1), 0); ?>
					</td>
					<td class="text-left">
						<?php echo "{$prefix_list[$user->prename]} {$user->name}   {$user->surname}"; ?>
					</td>
					<td class="text-left">
						<?php echo $user->position_code; ?>
					</td>
					<td class="text-left">
						<?php echo $user->department; ?>
					</td>
					<td class="text-center">
						<?php echo $user->email; ?>
					</td>
					<td class="text-center">
						<?php echo phone_number($user->telephone); ?>
					</td>
					<td class="text-center white">
						<div>
							<a href="#" onclick="form_temporary_user(<?php echo $user->user_id; ?>, 'edit');"
							   data-toggle="modal"
							   data-target="#temporary_users"
							   class="table-link" title="แก้ไข">
								<button type="button" class="btn btn-xs btn-warning">
									<i class="fa fa-edit"></i> แก้ไข
								</button></a>

							<?php if (in_array(strtolower($user->user_status), array('active'))) { ?>
								<a href="#"
								   class="table-link"
								   onclick="delete_user(<?php echo "{$data_data->meeting_id}, {$user->user_id}"; ?>);"
								   title="ระงับ">
									<button type="button" class="btn btn-xs btn-danger">
										<i class="fa fa-trash-o"></i> ระงับ
									</button></a>
							<?php } ?>
						</div>
					</td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr class="odd" role="row">
				<td class="text-center" colspan="7">ไม่มีข้อมลผู้เข้าร่วมประชุมภายนอกให้แสดง</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<div id="form_users_temporary">
		<?php
		if (isset($users_temporary) && !empty($users_temporary)) {
			foreach ($users_temporary as $key => $user) {
				?>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][user_id]"
					   value="<?php echo $user->user_id; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][citizen_id]"
					   value="<?php echo $user->citizen_id; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][prename]"
					   value="<?php echo $user->prename; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][name]"
					   value="<?php echo $user->name; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][surname]"
					   value="<?php echo $user->surname; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][position_code]"
					   value="<?php echo $user->position_code; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][level_code]"
					   value="<?php echo $user->level_code; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][gender]"
					   value="<?php echo $user->gender; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][department]"
					   value="<?php echo $user->department; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][email]"
					   value="<?php echo $user->email; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][telephone]"
					   value="<?php echo $user->telephone; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][user_status]"
					   value="<?php echo $user->user_status; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][user_type]"
					   value="<?php echo $user->user_type; ?>"/>
				<input type="hidden" name="users_temporary[edit][<?php echo $user->user_id; ?>][profile_picture]"
					   value="<?php echo $user->profile_picture; ?>"/>
				<?php
			}
		}
		?>
	</div>
</div>

<div id="temporary_users" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">เพิ่มผู้เข้าร่วมประชุมภายนอก</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="user_id" value="">
				<input type="hidden" id="user_status" value="">
				<input type="hidden" id="user_type" value="">
				<input type="hidden" id="profile_picture" value="">

				<div class="row">
					<div class="col-md-12">
						<label for="stext">เลขบัตรประชาชน</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="citizen_id" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-citizen_id"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">คำนำหน้าชื่อ <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<select id="prename" class="form-control">
							<?php foreach ($prefix_list as $key => $value) { ?>
								<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php }
							?>
						</select>
					</div>
					<label
						class="col-md-12 text-danger warning-prename"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">ชื่อ <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="name" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-name"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">นามสกุล <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="surname" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-surname"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">เพศ</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php foreach ($gender_list as $key => $value) { ?>
							<input type="radio" name="gender" id="gender_<?php echo $key; ?>" class=""
								   value="<?php echo $key; ?>">&nbsp
							<label for="gender_<?php echo $key; ?>"><?php echo $value; ?></label>&emsp;
						<?php } ?>
					</div>
					<label
						class="col-md-12 text-danger warning-gender"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">ตำแหน่ง</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="position_code" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-position_code"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">ระดับ</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="level_code" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-level_code"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">สังกัด</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="department" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-department"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">อีเมล <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="email" id="email" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-email"></label>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for="stext">หมายเลขโทรศัพท์ <font class="text-danger">*</font></label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="telephone" class="form-control" value="" placeholder="ระบุ">
					</div>
					<label
						class="col-md-12 text-danger warning-telephone"></label>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-xs btn-success" id="btn_users_temporary"
						onclick="">
					<i class="fa fa-floppy-o"></i>
					บันทึก
				</button>

				<button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
					<i class="fa fa-times"></i>
					ปิด
				</button>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
    function delete_user(meeting_id, user_id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการระงับผู้ใช้งานนี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ระงับ",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    location.href = '<?php echo base_url("datas/delete_temporary_user/"); ?>' + meeting_id + '/' + user_id;
                }
            });
    }

	<?php
	$fields = array('user_id', 'citizen_id', 'prename', 'name', 'surname', 'position_code', 'level_code', 'gender', 'department', 'email', 'telephone', 'user_status', 'user_type', 'user_type', 'profile_picture');
	$field_checkboxs = array('gender');
	?>

    function form_temporary_user(user_id, type) {
        try {
            if (typeof user_id !== 'undefined') {
                var form_title = 'แก้ไขผู้เข้าร่วมประชุมภายนอก';
                jQuery('#btn_users_temporary').attr('onclick', 'validate_form(' + user_id + ', \'' + type + '\');');
            } else {
                var form_title = 'เพิ่มผู้เข้าร่วมประชุมภายนอก';
                jQuery('#btn_users_temporary').attr('onclick', 'validate_form();');
            }
            jQuery('#temporary_users .modal-title').html(form_title);
            jQuery('#temporary_users label[class*="warning-"]').html('');

            var row_data = jQuery('#temporary_row_user_' + user_id);
            if (row_data.length > 0) {
				<?php
				foreach ($fields as $key => $field) {
					echo "var tmp_{$field} = jQuery('input[name=\"users_temporary[' + type + '][' + user_id + '][{$field}]\"]').val();\n";
				}
				?>
            } else {
				<?php
				foreach ($fields as $key => $field) {
					echo "var tmp_{$field} = '';\n";
				}
				?>
            }

			<?php
			foreach ($fields as $key => $field) {
				if (in_array($field, $field_checkboxs)) {
					echo "if (tmp_{$field} == '') jQuery('input[name={$field}]').prop('checked', false);\n";
					echo "jQuery('input[id={$field}_' + tmp_{$field} + ']').prop('checked', true);\n";
				} else {
					echo "jQuery('#{$field}').val(tmp_{$field});\n";
				}
			}
			?>
        } catch (e) {
            console.log(e);
        }
    }

    function validate_form(user_id, type) {
        try {
            var form_data = {<?php
				foreach ($fields as $key => $field) {
					if (in_array($field, $field_checkboxs)) {
						echo "'{$field}': jQuery('input[name={$field}]:checked').val(),\n";
					} else {
						echo "'{$field}': jQuery('#{$field}').val(),\n";
					}
				}
				?>};
            jQuery.post('<?php echo base_url("users/ajax_validate"); ?>', form_data, function (response) {
                var validate = jQuery.parseJSON(response);
                if (validate.length !== 0) {
                    jQuery.each(validate, function (field, warning) {
                        jQuery('.warning-' + field).html(warning);
                    });
                } else {
                    save_user_temporary(user_id, type);
                    $('#temporary_users').modal('toggle');
                }
            });
        } catch (e) {
            console.log(e);
        }
    }

    function save_user_temporary(user_id, type) {
        try {
			<?php
			foreach ($fields as $key => $field) {
				if (in_array($field, $field_checkboxs)) {
					echo "var tmp_{$field} = jQuery('input[name={$field}]:checked').val();\n";
				} else {
					echo "var tmp_{$field} = jQuery('#{$field}').val();\n";
				}
			}
			?>
            var tmp_fullname = get_prefix_text(tmp_prename) + ' ' + tmp_name + ' ' + tmp_surname;
            if (typeof user_id !== 'undefined') {
				<?php
				foreach ($fields as $key => $field) {
					echo "jQuery('input[name=\"users_temporary[' + type + '][' + user_id + '][{$field}]\"]').val(tmp_{$field});\n";
				}
				?>
                jQuery('#table_users_temporary tr#temporary_row_user_' + user_id + '>td').eq(1).html(tmp_fullname);
                jQuery('#table_users_temporary tr#temporary_row_user_' + user_id + '>td').eq(2).html(tmp_position_code);
                jQuery('#table_users_temporary tr#temporary_row_user_' + user_id + '>td').eq(3).html(tmp_department);
                jQuery('#table_users_temporary tr#temporary_row_user_' + user_id + '>td').eq(4).html(tmp_email);
                jQuery('#table_users_temporary tr#temporary_row_user_' + user_id + '>td').eq(5).html(phone_number(tmp_telephone));
            } else {
                var tmp_idx = new Date().getTime();
                var tmp_row = jQuery('#table_users_temporary tr[id^=temporary_row_user_]').length;
                jQuery('#form_users_temporary').append(
					<?php
					foreach ($fields as $key => $field) {
						if ($key > 0) echo "+";
						echo "'<input type=\"hidden\" name=\"users_temporary[new][' + tmp_idx + '][{$field}]\" value=\"' + tmp_{$field} + '\"/>'\n";
					}
					?>
                );
                if (tmp_row == 0) {
                    jQuery('#table_users_temporary').html('');
                }
                jQuery('#table_users_temporary').append(
                    '<tr class="odd" role="row" id="temporary_row_user_' + tmp_idx + '">' +
                    '<td class="text-center"></td>' +
                    '<td class="text-left">' + tmp_fullname + '</td>' +
                    '<td class="text-left">' + tmp_position_code + '</td>' +
                    '<td class="text-left">' + tmp_department + '</td>' +
                    '<td class="text-center">' + tmp_email + '</td>' +
                    '<td class="text-center">' + phone_number(tmp_telephone) + '</td>' +
                    '<td class="text-center white">' +
                    '<div>' +
                    '<a href="#" onclick="form_temporary_user(' + tmp_idx + ', \'new\');" data-toggle="modal" data-target="#temporary_users" class="table-link" title="แก้ไข">\n' +
                    '<button type="button" class="btn btn-xs btn-warning">\n' +
                    '<i class="fa fa-edit"></i> แก้ไข\n' +
                    '</button></a>\n' +
                    '<a href="#" class="table-link" onclick="delete_user_temporary(' + tmp_idx + ');" title="ยกเลิก">\n' +
                    '<button type="button" class="btn btn-xs btn-danger">\n' +
                    '<i class="fa fa-trash-o"></i> ยกเลิก\n' +
                    '</button></a>' +
                    '</div>' +
                    '</td>' +
                    '</tr>'
                );
            }
        } catch (e) {
            console.log(e);
        }
    }

    function delete_user_temporary(id) {
        swal({
                title: "แจ้งเตือน",
                text: "ต้องการยกเลิกผู้ใช้นี้",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ยกเลิก",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    jQuery('#temporary_row_user_' + id).remove();
                }
            });
    }

    function get_prefix_text(prename_id) {
        try {
            var prefix_list = {<?php
				if (isset($prefix_list) && !empty($prefix_list)) {
					$i = 0;
					foreach ($prefix_list as $key => $val) {
						if ($i > 0) echo ",";
						echo "{$key}: '{$val}'\n";
						$i++;
					}
				}
				?>};

            if (typeof prefix_list[prename_id] !== 'undefined') {
                return prefix_list[prename_id];
            } else {
                return '';
            }
        } catch (e) {
            console.log(e);
            return '';
        }
    }

    function phone_number(text) {
        if (text != '' && text.length == 10) {
            return text.substr(0, 3) + '-' + text.substr(3);
        } else {
            return '';
        }
    }
</script>
