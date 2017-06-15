	<?php require $_SERVER['DOCUMENT_ROOT']."/plugin/login/loginheader.php"; ?>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/controller/route/frontend_view.php'); ?>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/controller/route/route.php'); ?>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/controller/view/view.php'); ?>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/controller/validate/validate.php'); ?>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/controller/function/db_help.php'); ?>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/controller/db/db.php'); ?>	
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
	<link href="/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="/public/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/public/css/main_frontend.css">
	<style type="text/css">
		.container { width: 85% !important; }
		body  {
			    background-image: url("/public/upload/default/whirlpool.png");
			    background-attachment: fixed;
			}
		.breadcrumb {
			 color: #FFFFFF;
			 background-color: #07a79f;
			 background-attachment: fixed;
		}
		.sidebar-left {
			 background-color: #a7eed7;
			 border-radius: 22px; 

			    background: linear-gradient(#a7eed7, #cbdb62); /* Standard syntax */
		}
		.sidebar-left a{
			 color: #1A2E58;
			 line-height: 0.1em;
		}
		.main {
			 
			 background-color: #fefefe; 
			 border-style: dotted dashed solid double;
			 border-radius: 22px; 
			 box-shadow: 10px 10px 5px grey;
		}
		.topbar-right {
			 color: #FFFFFF;
			 background-color: #07a79f;
			 background-attachment: fixed;
		}
		.profile-top {
			 color: #FFFFFF;
			 background-color: #07a79f;
			 background-attachment: fixed;
		}
		.logo {
			 color: #FFFFFF;
			 background-color: #07a79f;
			 background-attachment: fixed;
			 border-radius: 22px; 
			 text-align: right;
		}
		 
		
	</style>
</head>
<body>
<?php
if ($_SESSION['employee_id']!=0 && isset($_SESSION['username'])){
		$info= $route -> authorization($_SESSION);
		if (isset($_GET['msg'])){
			echo '
			<script>
			$( document ).ready(function() {
			    alert("'.$_GET['msg'].'");
			    var new_url = removeParam("msg","'.$_SERVER["REQUEST_URI"].'");
			});
			</script>
			';			
		}
?>


<div class="container">

	<div class="row" style="">
		<div class="col-md-4 ">
			<div class="row" style="">
				<div class="col-md-3 ">
					<img class="img-responsive" src="/public/upload/default/no_profile.png" alt="Smiley face" >
				</div>
				<div class="col-md-8 ">
					 <a class="btn bt-md  " href="#">
			         <i class="fa fa-user-o fa-2x pull-left"></i>
					 <small>เข้าใช้งาน เมื่อ <?php $db = new Db(); $raw = $db -> select("SELECT mod_timestamp FROM authentication WHERE employee_id=".$info['profile']['id']); 
					  echo $raw[0]['mod_timestamp'];   ?> </small>
					 <br><b> <?php  echo "คุณ ".$info['profile']['firstname_thai']." ".$info['profile']['lastname_thai']; ?></b></a>
			         
			         <a class="btn btn-danger" href="/plugin/login/logout.php" aria-label="logoff">
				     <i class="fa fa-remove" aria-hidden="true"></i></a><br>

					  <?php  echo "<i class='fa fa-id-card-o fa-1x' aria-hidden='true'> </i><a href='mailto:".$info['profile']['email']."'> ".$info['profile']['email']."" ?></small></a>
					  <?php  echo " <i class='fa fa-mobile-phone fa-1x' aria-hidden='true'> </i><small><a href='tel:".$info['profile']['phone']."'> ".$info['profile']['phone']."" ?></small></a>
				</div>

			</div>
			 
		</div>
		<div class="col-md-5">
			<blockquote class="blockquote blockquote-reverse">
			<small><b>you look at any giant corporation, and I mean the biggies, and they all started with a guy with an idea, doing it well.</b></small>
			<footer class="blockquote-footer">Irv Robbins</footer>
			</blockquote>
		</div>	
		<div class="col-md-3 logo">
			<h1><a style="color :#FFFFFF;" href="http://www.bathline.in.th">BATHLINE</a> / <a style="color :#FFFFFF;" href="http://bathline.seasiri.org">UNO</a> </h1><small><?php echo $config['version']; ?></small>
		</div>	
				
	</div>
	<hr>
	<div class="row">	
		<div class="col-md-3">
			<div class="panel panel-info panel-round sidebar-left" >
				<div class="panel panel-body sidebar-left" >
			<?php  $frontend_view -> navigation($info); ?>
				</div>
			</div>
		</div>
		<div class="col-md-9 main" id="main">
			<div class="container">
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
			</div>
			<div class="row">
			<?php if ($_SERVER['QUERY_STRING']==""){ ?>
				<div class="panel panel-default">
				  <div class="panel-body">
				    <h3>ระบบส่วนกลาง บาธไลน์</small>
				  </div>
				  <div class="panel-footer">
				  		<h4>แนวทางการพัฒนา</h4> สถานะ
						<div class="progress">				  
						  <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 35%;">
						    35%
						  </div>
						</div>
						แผนกบุคคล
						<div class="progress">
						  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 38%">
						    <span class="sr-only">40% Complete (success)</span>
						  </div>
						</div>
						<code>-กรอกข้อมูลพนักงาน -พัฒนาระบบบุคลากร -กระดานออนไลน์ -ระบบอีเมล</code>
						<br><br>
						แผนกซื้อ
						<div class="progress">
						  <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 55%">
						    <span class="sr-only">20% Complete</span>
						  </div>
						</div>
						<code>-กรอกลักษณะสินค้า -CADไฟล์ สำหรับสถาปนิค -เตรียมพร้อมรูปภาพสินค้า -แบบฟอร์มและขั้นตอนสั่งซื้อยังไม่ได้รับ EXCEL พร้อมหัวข้อ</code>
						<br><br>
						แผนกขาย
						<div class="progress">
						  <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
						    <span class="sr-only">60% Complete (warning)</span>
						  </div>
						</div>
						<code>-ช่วยกันคิดขั้นตอนที่มีประสิทธิภาพกว่านี้ ในเรื่องการแนบและปริ้นเอกสาร ต้องรวดเร็วชัดเจน ประหยัดกระดาษและใช้คนน้อยที่สุด</code>
						<br><br>
						แผนกบัญชี
						<div class="progress">
						  <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 5%">
						    <span class="sr-only">5% Complete (danger)</span>
						  </div>
						</div>
						<code>-</code>
						
				<?php }else{ ?>
						<br><br>
						<!-- SAY WQHAT U WANT GLOBAL-->
						</div>
				</div>	<!-- PANEL DIV-->
				<?php } ?>
				
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
©<small><?php  echo " ".date("Y "); ?> BATHLINE. All Rights Reserved.</small><hr>
</html>
	
