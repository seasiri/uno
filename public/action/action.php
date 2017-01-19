<?php
	var_dump($_FILES);
	var_dump($_POST);
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/view/view.php'); 
 	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/validate/validate.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/db/db.php');	
	$db = new Db(); 
	$validate = new validate();
	$attachment = new attachment();
	switch ($_POST['act']) {
		case 'insert':
			$result = $attachment -> upload($_FILES,$_POST);
			if($result)
			{
				$sql_element = $validate -> insert($_POST);	
				$sql="INSERT INTO ".$_POST['form_title']." (".$sql_element['head'].") VALUES (".$sql_element['value'].")";
				echo $sql;
				$db -> query($sql);
			}
			break;
		case 'edit':
			$result = $attachment -> upload($_FILES,$_POST);
			if($result)
			{
				$sql_element = $validate -> edit($_POST);
				$sql="UPDATE ".$_POST['form_title']." SET ".$sql_element['value'];
				echo $sql;
				$db -> query($sql);
			}
			break;
		default:
			# code...
			break;
	}
	
	
	//var_dump($error);
?>