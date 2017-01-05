<!DOCTYPE html>
<html>
<head>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="/public/js/bootstrap.min.js"></script>
	<meta http-equiv=Content-Type content="text/html; charset=tis-620">
	<link href="/public/css/font-awesome.min.css" rel="stylesheet">
	<link href="/public/css/bootstrap.min.css" rel="stylesheet">
	<?php require_once('/controller/view/view.php'); ?>
	<?php require_once('/controller/validate/validate.php'); ?>
	<?php require_once('/controller/db/db.php'); ?>
	<?php $view = new view(); ?>
	<?php $validate = new validate(); ?>


</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<h2>Bathline <br><small>UNSEEN FUTURE</small></h2>
		</div>
		<div class="col-md-6">
			<h3><i></i></h3><br>
			<h4>"WHAT WE THINK, WE BECOME" - BUDDHA</h4>

		</div>
		<div class="col-md-1">
			<h4><small><a href="dashboard"> dashboard</small></a></h4>

		</div>	
		<div class="col-md-1">
			<h4><small><a href="phpmyadmin">phpmyadmin</small></a></h4>

		</div>	
		<div class="col-md-1">
			<h4><small><a href="info">info</small></a></h4>

		</div>	

	</div>

	<div class="row">
		<div class="col-md-3">
			<?php				
				$table_list = $validate->table_list();
				foreach ($table_list as $key => $value) {
					echo '<a href="?db='.$value.'&act=grid">'.$value."</a><br>";
				}
			?>

		</div>	
		<div class="col-md-7">
	<?php
		$get=$_GET;	
		if (!empty($_GET)){
			switch ($_GET['act']) {
				case 'insert':
					$view -> display('insert',$get);
					break;
				case 'grid':
					$view -> display('grid',$get);
					break;
				case 'edit':
					$view -> display('edit',$get);
					break;
				default:
					$view -> display('error',$get);
					break;
			}			
		}
		else{
			$view -> display('home',$get);
		}		
	?>
	<div class="row">
	<?php
	$view -> hierarchy($get);
	?>
	</div>
		</div>		
	</div>
	
</div>
</body>
</html>