<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/view/view.php'); 
 	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/validate/validate.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/db/db.php'); 	
	$db = new Db(); 
	$validate = new validate();
	switch ($_POST['act']) {
		case 'insert':
			$sql_element = $validate -> insert($_POST);	
			$sql="INSERT INTO ".$_POST['form_title']." (".$sql_element['head'].") VALUES (".$sql_element['value'].")";
			$db -> query($sql);
			break;
		case 'edit':
			$sql_element = $validate -> edit($_POST);
			$sql="UPDATE ".$_POST['form_title']." SET ".$sql_element['value'];
			$db -> query($sql);
			break;
		default:
			# code...
			break;
	}
	
	
	//var_dump($error);
?>