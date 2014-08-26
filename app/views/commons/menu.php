
<?php 
if (isset($menu) && !empty($menu)) {
	echo "<ul class='nav nav-pills nav-stacked'>";
	foreach ($menu as $opcion) {
		echo "<li><a href='#'>".$opcion["nombre_categoria"]."</a></li>";
	}
	echo "</ul>";
}
?>
