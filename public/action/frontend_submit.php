<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/view/view.php'); 
 	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/validate/validate.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/db/db.php');	
	$db = new Db(); 
	$validate = new validate();
	$attachment = new attachment();
	$_POST['act']=strtolower($_POST['act']);
	switch ($_POST['act']) {
		case 'insert':
			$result = $attachment -> upload($_FILES,$_POST);
			if($result)
			{
				$sql_element = $validate -> insert($_POST);	
				$sql="INSERT INTO ".$_POST['form_title']." (".$sql_element['head'].") VALUES (".$sql_element['value'].")";
				$result=$db -> query($sql);
				if ($result)
				{
					$url = '/?task='.$_POST['task'].'&act='.$_POST['act'].'&msg=เเก้ไขเรียบร้อย'; 
					header( "Location: $url" );
				}else{
					echo '<a href="javascript:history.go(-1)">คลิกเพื่อกลับไปแก้ไข</a>';
				}
			}
			break;
		case 'edit':
			$result = $attachment -> upload($_FILES,$_POST);
			if($result)
			{
				$sql_element = $validate -> edit($_POST);
				$sql="UPDATE ".$_POST['form_title']." SET ".$sql_element['value'];
				$result=$db -> query($sql);
				if ($result)
				{
					$url = '/?task='.$_POST['task'].'&act='.$_POST['act'].'&msg=เเก้ไขเรียบร้อย'; 
					header( "Location: $url" );
				}else{
					echo '<a href="javascript:history.go(-1)">คลิกเพื่อกลับไปแก้ไข</a>';
				}
				/*
				ob_start();
				while (ob_get_status()) 
				{
				    ob_end_clean();
				}*/
				//header( "Location: $url" );
			}
			break;
		default:
			# code...
			break;
	}
	
	
	//var_dump($error);
?>