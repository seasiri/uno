	<?php require "plugin/login/loginheader.php"; ?>
	<?php require_once('/controller/route/route.php'); ?>
	<?php require_once('/controller/view/view.php'); ?>
	<?php require_once('/controller/validate/validate.php'); ?>
	<?php require_once('/controller/db/db.php'); ?>	
	<?php $route = new route(); ?>
<?php
	$enviroment= $route -> authorization($_SESSION);
	echo '<pre>';
	print_r($enviroment);
	echo '</pre>';


?>