<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/view/view.php'); 
 	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/validate/validate.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/controller/db/db.php');	
	$db = new Db(); 
	$validate = new validate();
	$attachment = new attachment();
	$_POST['act']=strtolower($_POST['act']);
	var_dump($_POST);
	switch ($_POST['act']) {
		case 'insert':
			$result = $attachment -> upload($_FILES,$_POST);
			if($result)
			{
				$sql_element = $validate -> insert($_POST);	
				$sql="INSERT INTO ".$_POST['form_title']." (".$sql_element['head'].") VALUES (".$sql_element['value'].")";
				echo $sql;
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
		case 'insert_credential':
			
				$temp_count=0;
				foreach ($_POST as $key => $value) {
					if (strpos($key , "_id") !== false) {
						$exist_result = $validate -> lookup_if_rows_exist($_POST[$key],str_replace("_id", "", $key));
						$temp_count = $exist_result+$temp_count;
					}                    
				}
				if ($temp_count==0)
				{
					$sql_element = $validate -> insert($_POST);	
					$sql="INSERT INTO ".$_POST['form_title']." (".$sql_element['head'].") VALUES (".$sql_element['value'].")";
					echo $sql;
					$result=$db -> query($sql);
					if ($result)
					{
						$url = '/?task='.$_POST['task'].'&act='.$_POST['act'].'&msg=เเก้ไขเรียบร้อย'; 
						header( "Location: $url" );
					}else{
						echo '<a href="javascript:history.go(-1)">คลิกเพื่อกลับไปแก้ไข</a>';
					}
				}else{
					echo '<a href="javascript:history.go(-1)">คลิกเพื่อกลับไปแก้ไข ค่าไม่มีอยู่จริง</a>';
				}
			
			break;
		case 'edit':
			$result = $attachment -> upload($_FILES,$_POST);
			if($result)
			{
				$sql_element = $validate -> edit($_POST);
				$sql="UPDATE ".$_POST['form_title']." SET ".$sql_element['value'];
				ECHO $sql;
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