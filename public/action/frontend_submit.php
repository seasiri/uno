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
				echo $sql;
				$result=$db -> query($sql);
				if ($result)
				{
					$url = '/?task='.$_POST['task'].'&act='.$_POST['act'].'&msg=เเก้ไขเรียบร้อย'; 
					echo "<script>window.location = '".$url."';</script>";
				}else{
					echo '<a href="javascript:history.go(-1)">คลิกเพื่อกลับไปแก้ไข</a>';
				}
			}
			break;
		case 'insert_multiple':
				if(isset($_POST['row'])&&isset($_POST['skp'])&&!($_POST['row']>10)){
					for ($i=0; $i < $_POST['row']; $i++) { 
						$count[]=$i;
					}
					$i=0;	
					$sql_value="";
					foreach ($count as $key => $value) {	
						$sql_element = $validate -> insert_multiple($_POST,$i);						
						$sql_head="INSERT INTO ".$_POST['form_title']." (".$sql_element['head'].") VALUES ";
						$sql_value.="(".$sql_element['value']."),";
						$i++;
					}
					$sql_value=rtrim($sql_value,",");
					$sql_value.=";";
					$sql=$sql_head.$sql_value;
					$result=$db -> query($sql);
						if ($result)
						{
							$url = '/?task='.$_POST['task'].'&act='.$_POST['act'].'&msg=เเก้ไขเรียบร้อย&row='.$_POST['row'].'&skp='.$_POST['skp'].''; 
							echo "<script>window.location = '".$url."';</script>";
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
				ECHO $sql;
				$result=$db -> query($sql);
				if ($result)
				{
					$url = '/?task='.$_POST['task'].'&act='.$_POST['act'].'&msg=เเก้ไขเรียบร้อย'; 
					echo "<script>window.location = '".$url."';</script>";
				}else{
					echo '<a href="javascript:history.go(-1)">คลิกเพื่อกลับไปแก้ไข</a>';
				}
			}
			break;
		case 'edit_credential_time':
			$result = $attachment -> upload($_FILES,$_POST);
			if($result)
			{
				$sql_element = $validate -> edit($_POST);
				$sql="UPDATE ".$_POST['form_title']." SET ".$sql_element['value'];
				$result=$db -> query($sql);
				if ($result)
				{
					$url = '/?task='.$_POST['task'].'&act='.$_POST['act'].'&msg=เเก้ไขเรียบร้อย'; 
					echo "<script>window.location = '".$url."';</script>";
				}else{
					echo '<a href="javascript:history.go(-1)">คลิกเพื่อกลับไปแก้ไข</a>';
				}
			}
			break;
		default:
			# code...
			break;
	}
	
	
	//var_dump($error);
?>