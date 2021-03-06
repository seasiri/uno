<?php
session_start();

if (isset($_SESSION['username'])) {
    header("location:/index.php");
}
?>
<?php $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/private/config.ini');  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bathline Login System </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../login/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="../login/css/main.css" rel="stylesheet" media="screen">
  </head>

  <body>
    <div class="container">

      <form class="form-signin" name="form1" method="post" action="checklogin.php">
        <h2 class="form-signin-heading">BATHLINE / UNO <small><?php echo $config['version']; ?></small></h2>
        <input name="myusername" id="myusername" type="text" class="form-control" placeholder="ชื่อเข้าใช้งาน" autofocus>
        <input name="mypassword" id="mypassword" type="password" class="form-control" placeholder="รหัสผ่าน">
        <!-- The checkbox remember me is not implemented yet...
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        -->
        <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">เข้าสู่ระบบ</button>
        <h4><li><a href="signup.php">พนักงานบาธไลน์เข้าใช้ระบบครั้งแรก</a></li></h4>
        <div id="message"></div>
      </form>


    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- The AJAX login script -->
    <script src="js/login.js"></script>

  </body>
</html>
