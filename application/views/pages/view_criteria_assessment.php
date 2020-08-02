<p class="h4 header text-success">
	<i class="fa fa-file-text-o"></i> หมวดหมู่ / เกณฑ์การประเมิน
</p>

<div id="search-filter" class="widget-box">
	<div class="widget-body">
		<div class="widget-main">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?php echo base_url("criteria_assessments/dashboard_criteria_assessments"); ?>"
					   class="table-link" title="ย้อนกลับ">
						<button type="button" class="btn btn-xs btn-info">
							<i class="fa fa-arrow-left"></i> ย้อนกลับ
						</button></a>

					<a href="<?php echo base_url("criteria_assessments/new_criteria_assessment/{$profile_id}"); ?>"
					   class="table-link" title="แก้ไข">
						<button type="button" class="btn btn-xs btn-warning">
							<i class="fa fa-edit"></i> แก้ไข
						</button></a>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<!-- <div class="dd" id="nestable"> -->
					  <ol class="dd-list">
					    <?php
					    if (isset($datas) && !empty($datas)) {
					      foreach ($datas as $key => $value) {
					        ?>
					        <li class="dd-item" data-id="<?php echo $key+1; ?>">
					          <div class="dd-handle">
					            <?php echo $value->criteria_name; ?>
					          </div>
					          <?php if (isset($value->data) && !empty($value->data)) {
					            ?>
					            <ol class="dd-list">
					            <?php foreach ($value->data as $key_child => $child) { ?>
					              <li class="dd-item" data-id="<?php echo $key_child+1; ?>">
					                <div class="dd-handle">
					                  <?php echo $child->criteria_name; ?>
					                </div>
					              </li>
					            <?php }  ?>
					            </ol>
					            <?php
					          } ?>
					        </li>
					        <?php
					      }
					    }
					    ?>
					  </ol>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>
</div>
