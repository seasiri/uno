<?php
  session_start();
  if (isset($_SESSION['username'])) {
      session_start();
      session_destroy();
  }
?>
<?php $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/private/config.ini');  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bathline Login System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../login/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../login/css/main.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class="container">
      <form class="form-signup" id="usersignup" name="usersignup" method="post" action="createuser.php">
        <h2 class="form-signup-heading">BATHLINE / <small><?php echo $config['version']; ?></small></h2>
        <h3 class="form-signup-heading">สมัครเพื่อใช้งานระบบใหม่</h3>
        <input name="newuser" id="newuser" type="text" class="form-control" placeholder="ชื่อเข้าใช้งาน" autofocus>
        <input name="email" id="email" type="text" class="form-control" placeholder="อีเมล">
<br>
        <input   name="thai_id" id="thai_id" type="number" class="form-control" placeholder="บัตรประชาชน">
<br>
        <input name="password1" id="password1" type="password" class="form-control" placeholder="รหัสผ่าน">
        <input name="password2" id="password2" type="password" class="form-control" placeholder="กรอก รหัสผ่าน อีกครั้ง">
        <br>
        <small>**โปรดจำ ชื่อผู้เข้าใช้งาน และ รหัสผ่านในการเข้าสู่ระบบ</small>
        <small>*ข้อมูลจะถูกเก็บไว้อย่างมีมาตรฐานการจัดเก็บ และ ปลอดภัย</small>
        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">เข้าร่วมการใช้งานระบบ</button>
        <a class="btn btn-lg btn-primary btn-warning"  onclick="gotoLogin()">ย้อนกลับ</a>
        <div id="message"></div>
      </form>   
      
      
    </div> <!-- /container -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="plugin/js/bootstrap.js"></script>
    <script src="js/signup.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
<script>
function gotoLogin() {
            window.location.href = '/';
        }
function reload() {
    location.reload();
}
$( "#usersignup" ).validate({
  rules: {
	email: {
		email: true,
		required: true
	},
    password1: {
      required: true,
      minlength: 4
	},
    password2: {
      equalTo: "#password1"
    }
  }
});
</script>
  </body>
</html>
