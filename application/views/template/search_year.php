<!-- search -->
<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<form action="<?php if (isset($search_url)) {
				echo $search_url;
			}; ?>" method="GET" class="form-horizontal" role="form">
				<div class="row">
					<div class="col-md-12">
						<label for="stext">ค้นหา</label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<input type="text" id="text" name="search"
							   class="form-control"
							   value="<?php echo $this->input->get('search') ? $this->input->get('search') : ''; ?>"
							   placeholder="ค้นหา">
								 <label></label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="stext">ปีเริ่มต้น</label>
					</div>
					<div class="col-md-4">
						<label for="stext">ปีสิ้นสุด</label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<select class="form-control" name="search_year_start">
							<?php foreach ($year_list as $key => $value) { ?>
								<option
									value="<?php echo $key; ?>"<?php if (isset($search_year_start) && $search_year_start == $key) {
									echo 'selected="selected"';
								} ?>><?php echo $value; ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="col-md-4">
						<select class="form-control" name="search_year_end">
							<?php foreach ($year_list as $key => $value) { ?>
								<option
									value="<?php echo $key; ?>"<?php if (isset($search_year_end) && $search_year_end == $key) {
									echo 'selected="selected"';
								} ?>><?php echo $value; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-right">
						<button class="btn btn-sm btn-primary"
								type="submit" id="submit">
							<i class="fa fa-search"></i>
							ค้นหา
						</button>
						&nbsp;&nbsp;
						<a href="<?php echo (isset($search_url)) ? $search_url : current_url(); ?>"
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
