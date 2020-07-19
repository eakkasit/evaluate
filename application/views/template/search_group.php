<!-- search -->
<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form action="<?php if (isset($search_url)) {
				echo $search_url;
			}; ?>" method="POST" class="form-horizontal" role="form">
				<div class="row">
					<div class="col-md-12">
						<label for="stext">ค้นหา</label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-<?php echo (isset($groups_selected_list) && !empty($groups_selected_list)) ? 8 : 12; ?>">
						<input type="text" id="text" name="form_search_element[text]"
							   class="form-control"
							   value=""
							   placeholder="ค้นหา">
					</div>
					<?php
					if (isset($groups_selected_list) && !empty($groups_selected_list)) {
						$group_selected = array('' => 'ทั้งหมด');
						foreach ($groups_list as $group) {
							$group_selected[$group->group_id] = $group->group_name;
						}
						$group_selected[0] = 'ผู้ประชุมภายนอก';
						?>
						<div class="col-md-4">
							<select name="form_search_element[group_id]" class="form-control">
								<?php foreach ($group_selected as $key => $val) { ?>
									<option
										value="<?php echo $key; ?>"><?php echo $val; ?></option>
								<?php } ?>
							</select>
						</div>
					<?php } ?>
				</div>

				<div class="row">
					<div class="col-md-12 text-right">
						<button class="btn btn-sm btn-primary"
								type="submit" id="submit">
							<i class="fa fa-search"></i>
							ค้นหา
						</button>
						&nbsp;&nbsp;
						<a href="<?php echo (isset($search_url)) ? $search_url : '#'; ?>"
						   class="table-link" title="ล้างค่า">
							<button type="button" class="btn btn-sm btn-warning">
								<i class="fa fa-eye"></i> ล้างค่า
							</button></a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- end search -->
