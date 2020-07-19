<div class="paging">
	<p class="text-center page_all_row">
		จำนวนที่พบทั้งหมด <?php echo number_format((isset($count_rows) ? $count_rows : 0), 0); ?> รายการ
		<?php
		if (isset($pages)) {
			echo $pages;
		}
		?>
	</p>
</div>
