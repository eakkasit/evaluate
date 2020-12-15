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
					</div>
				</div>

				<?php
				if(isset($search_keyword)){
					?>
				<div class="row">
					<div class="col-md-12">
						<label for="stext">keyword</label>
					</div>
				</div>

				<div class="row ">
					<div class="col-md-12">
						<input type="text" class="w100 tagInputRewrite" name="keyword" id="keyword" value="<?php echo $this->input->get('keyword') ? $this->input->get('keyword') : ''; ?>" style="display: none;">
					</div>
				</div>
				<?php
				}
				?>

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
