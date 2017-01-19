	<?php require "plugin/login/loginheader.php"; ?>
	<?php require_once('/controller/route/frontend_view.php'); ?>
	<?php require_once('/controller/route/route.php'); ?>
	<?php require_once('/controller/view/view.php'); ?>
	<?php require_once('/controller/validate/validate.php'); ?>
	<?php require_once('/controller/db/db.php'); ?>	
	<?php $route = new route(); ?>
	<?php $frontend_view = new frontend_view(); ?>

<?php $info= $route -> authorization($_SESSION); ?>
<!DOCTYPE html>
<html>
<head>
	<script src="/public/js/jquery-latest.js"></script>
	<script src="/public/js/bootstrap.min.js"></script>
	<meta http-equiv=Content-Type content="text/html; charset=tis-620">
	<link href="/public/css/font-awesome.min.css" rel="stylesheet">
	<link href="/public/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<div class="row">	
		<div class="col-md-2">
			<h1>BATHLINE</h1>
		</div>
		<div class="col-md-6">

		</div>
		<div class="col-md-2">
			ใช้งานล่าสุด <br>
			 <h4> <?php echo "<a href=''>".$info['profile']['firstname_thai']." ".$info['profile']['lastname_thai']."</a>"; ?></h4>
		</div>
		<div class="col-md-2">
			<a href="http://localhost/plugin/login/logout.php">ออกจากระบบ</a>
		</div>
	</div>
	<hr>
	<div class="row">	
		<div class="col-md-3">
			<?php  $frontend_view -> navigation($info); ?>
		</div>
		<div class="col-md-7">		
		<?php $frontend_view -> template($info,$_GET); ?>
			<div class="row">	
				<?php
					echo '<pre>';
					print_r($info);
					echo '</pre>';
				?>
			</div>
		</div>
		<div class="col-md-2">
			
		</div>
		
	</div>

</div>
</body>
</html>
