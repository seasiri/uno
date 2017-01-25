	<?php require "plugin/login/loginheader.php"; ?>
	<?php require_once('/controller/route/frontend_view.php'); ?>
	<?php require_once('/controller/route/route.php'); ?>
	<?php require_once('/controller/view/view.php'); ?>
	<?php require_once('/controller/validate/validate.php'); ?>
	<?php require_once('/controller/db/db.php'); ?>	
	<?php $route = new route(); ?>
	<?php $frontend_view = new frontend_view(); ?>	
	<?php $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/private/config.ini');  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Bathline UNO | <?php echo $config['version']; ?></title>
	<link rel="stylesheet" type="text/css" href="/public/fonts/thsarabunnew.css" />
	<script src="/public/js/jquery-latest.js"></script>
	<script src="/public/js/bootstrap.min.js"></script> 
	<script src="/public/js/main.js"></script>
	<meta http-equiv=Content-Type content="text/html; charset=tis-620">
	<link href="/public/css/font-awesome.min.css" rel="stylesheet">
	<link href="/public/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/public/css/main_frontend.css">
</head>
<body>
<?php if ($_SESSION['employee_id']!=0 && isset($_SESSION['username'])){
		$info= $route -> authorization($_SESSION);
		if (isset($_GET['msg'])){
			echo '
			<script>
			$( document ).ready(function() {
			    alert("'.$_GET['msg'].'");
			    var new_url = removeParam("msg","'.$_SERVER["HTTP_REFERER"].'");
			});
			</script>
			';
		}
?>
<div class="container">
	<div class="row">	
		<div class="col-md-2">
			<h1>BATHLINE / UNO </h1><small><?php echo $config['version']; ?></small>
		</div>	
		<div class="col-md-3">
			<br><blockquote>"บอกให้รู้ไว้ หัวใจรักจริง"</blockquote>
		</div>
		<div class="col-md-4">
			<small><br>คุณภาพ คือ การใส่ใจในการป้อน ข้อมูล <br>ข้อมูล คือ ความเข้าใจในงาน<br>ประสิทธิภาพ คือ ความเชื่อมั่นในระบบ และ พัฒนาตามความต้องการ<br>ผลลัพธ์ คือ ความชัดเจนของข้อมูล</small>
		</div>	
		<div class="col-md-2">
			<br>ใช้งานล่าสุด <br>
			 <h4> <?php echo "<a href=''>คุณ ".$info['profile']['firstname_thai']." ".$info['profile']['lastname_thai']."</a>"; ?></h4>
			 <a href="/plugin/login/logout.php">ออกจากระบบ</a>
		</div>		
	</div>
	<hr>
	<div class="row">	
		<div class="col-md-3">
			<div class="panel panel-info panel-round">
				<div class="panel panel-body">
			<?php  $frontend_view -> navigation($info); ?>
				</div>
			</div>
		</div>
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
			<div class="row">
			<?php $frontend_view -> template($info,$_GET); ?>
				<div class="row">	
					<?php
						/*
						echo '<pre>';
						print_r($info);
						echo '</pre>';
						*/
					?>
				</div>
			</div>
			<div class="row">
				<h4>อ่านด้วย! แนวทางการกรอก เบื้องต้น</h4><hr>
				*ทุกอย่างแก้ไขภายหลังได้ ลองผิดลองถูกได้ ถ้าผิดก็แค่เข้าหมวดแก้ไข (ตอนนี้ยังไม่มีการลบ)<br>
				*ช่องที่มี parent_id ลงท้าย คือ การเพิ่มชั้นงาน<br>
				*สำหรับ ดำแหน่งงาน อำเภอ ใส่เป็นอะไรก็ได้ แต่ใส่จังหวัดลพบุรี<br>
			</div>
		</div>
		<div class="col-md-2">
			
		</div>
		
	</div>
</div>
<?php
}else{
?>
<div class="container">
	<div class="row">	
		<div class="col-md-2">
			<h1>BATHLINE</h1>
		</div>
		<div class="col-md-6">

		</div>
		<div class="col-md-2">
			
		</div>
		<div class="col-md-2">
			<a href="/plugin/login/logout.php">ออกจากระบบ</a>
		</div>
	</div>
	<hr>
	<div class="row">	
		<div class="col-md-5">
		<div class="alert alert-success" role="alert">รอการอนุมัติ ข้อมูลจาก แผนกบุคคลากร (Human Resource Manager)</div>
		</div>
		<div class="col-md-3">

		</div>
		<div class="col-md-2">
			
		</div>
		<div class="col-md-2">			
		</div>
	</div>
</div>
<?php
}
?>
</body>
</html>
	
