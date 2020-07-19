<?php
$class = $this->router->fetch_class(); // class = controller
$action = $this->router->fetch_method();
$menu = json_decode(SYSTEM_MENU);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
        try {
            ace.settings.check('breadcrumbs', 'fixed')
        } catch (e) {
            console.log(e);
        }
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="#">หน้าหลัก</a>
		</li>
		<?php
		if (isset($menu) && !empty($menu)) {
			foreach ($menu as $item) {
				if (isset($item->menu->$class)) {
					echo "<li class=\"active\">{$item->name}</li>";
					break;
				}
			}
			foreach ($menu as $item) {
				if (isset($item->menu->$class)) {
					echo "<li class=\"active\">{$item->menu->$class->name}</li>";
					break;
				}
			}
		}
		?>

	</ul><!-- /.breadcrumb -->

	<!-- /section:basics/content.searchbox -->
</div>

<div class="page-content">

	<div class="page-header page-header-custom">
		<?php
		if (isset($menu) && !empty($menu)) {
			foreach ($menu as $item) {
				if (isset($item->menu->$class)) {
					echo "<p id=\"sbs-app-parent\"><i class=\"fa fa-comment\"></i>&nbsp;{$item->name}</p>";
					break;
				}
			}
			foreach ($menu as $item) {
				if (isset($item->menu->$class)) {
					echo "<h1 id=\"sbs-app-header\" class=\"\">{$item->menu->$class->name}</h1><h1 id=\"sbs-app-header_custom\" class=\"hide\"></h1>";
					break;
				}
			}
		}
		?>

	</div>
