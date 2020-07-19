<?php
$class = $this->router->fetch_class(); // class = controller
$action = $this->router->fetch_method();
$menu = json_decode(SYSTEM_MENU);
?>
<div id="sidebar" class="sidebar sidebar-custom responsive">
	<script type="text/javascript">
        try {
            ace.settings.check('sidebar', 'fixed')
        } catch (e) {
            console.log(e);
        }
	</script>

	<div class="sidebar-sysname" title="ระบบบริหารจัดการข้อมูลดิจิทัล">
		<p class="sys-max">ระบบบริหารจัดการข้อมูลดิจิทัล</p>
		<p class="sys-min"></p>
	</div>

	<ul class="nav nav-list">
		<?php
		if (isset($menu) && !empty($menu)) {
			foreach ($menu as $item) {
				?>
				<li class="<?php echo (isset($item->menu->$class)) ? 'open' : ''; ?>">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon <?php echo $item->icon; ?>"></i>
						<span class="menu-text">
								<?php echo $item->name; ?>
							</span>

						<b class="arrow fa fa-angle-down"></b>
					</a>

					<b class="arrow"></b>

					<ul class="submenu">

						<?php
						if (isset($item->menu) && !empty($item->menu)) {
							foreach ($item->menu as $class_name => $sub_menu) {
								?>
								<li class="<?php echo ($class == $class_name) ? 'active' : ''; ?>">
									<a href="<?php echo base_url($sub_menu->base_url); ?>">
										<i class="menu-icon fa fa-caret-right"></i>
										<?php echo $sub_menu->name; ?>
									</a>

									<b class="arrow"></b>
								</li>
								<?php
							}
						}
						?>
					</ul>
				</li>
				<?php
			}
		}
		?>

	</ul><!-- /.nav-list -->

	<!-- #section:basics/sidebar.layout.minimize -->
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
		   data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div>

	<!-- /section:basics/sidebar.layout.minimize -->
	<script type="text/javascript">
        try {
            ace.settings.check('sidebar', 'collapsed')
        } catch (e) {
        }
	</script>
</div>
