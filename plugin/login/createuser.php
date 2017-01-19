<?php
require 'includes/functions.php';
include_once 'config.php';
//Pull username, generate new ID and hash password
$newid = uniqid(rand(), false);
$newuser = $_POST['newuser'];
$newpw = password_hash($_POST['password1'], PASSWORD_DEFAULT);
$pw1 = $_POST['password1'];
$pw2 = $_POST['password2'];

    //Enables moderator verification (overrides user self-verification emails)
if (isset($admin_email)) {

    $newemail = $admin_email;

} else {

    $newemail = $_POST['email'];

}
//Validation rules
if (strlen($_POST['thai_id'])<13 || strlen($_POST['thai_id'])>13)
{
    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>บัตรประชาชน ต้องมี 13 หลัก</div><div id="returnVal" style="display:none;">false</div>';

} elseif ($pw1 != $pw2) {

    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>รหัสผ่านไม่เหมือนกัน กรอกใหม่อีกครั้ง</div><div id="returnVal" style="display:none;">false</div>';

} elseif (strlen($pw1) < 4) {

    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>รหัสผ่าน ต้องมีอย่างน้อย 4 ตัว</div><div id="returnVal" style="display:none;">false</div>';

} elseif (!filter_var($newemail, FILTER_VALIDATE_EMAIL) == true) {

    echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>เช็คความถูกต้องของอีเมล</div><div id="returnVal" style="display:none;">false</div>';

} else {
    //Validation passed
    if (isset($_POST['thai_id']) && isset($_POST['newuser']) && !empty(str_replace(' ', '', $_POST['thai_id'])) && !empty(str_replace(' ', '', $_POST['newuser'])) && isset($_POST['password1']) && !empty(str_replace(' ', '', $_POST['password1']))) {

        //Tries inserting into database and add response to variable

        $a = new NewUserForm;

        $response = $a->createUser($newuser, $newid, $newemail, $newpw,$_POST['thai_id']);

        //Success
        if ($response == 'true') {

            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'. $signupthanks .'</div><div id="returnVal" style="display:none;">true</div>';

            //Send verification email
            //$m = new MailSender;
            //$m->sendMail($newemail, $newuser, $newid, 'Verify');

        } else {
            //Failure
            mySqlErrors($response);

        }
    } else {
        //Validation error from empty form variables
        echo 'An error occurred on the form... try again';
    }
};
