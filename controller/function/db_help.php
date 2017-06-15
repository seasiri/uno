<?php 
class db_help{
	public function id2name($id,$table_name){
       $db = new Db();
       $table_name=str_replace("_id","",$table_name);
       $rows = $db -> select("SELECT name FROM ".$table_name." WHERE id =".$id);
       echo $rows[0]['name']."";
    }
    public function url2name($id,$table_name){
       $db = new Db();
       $table_name=str_replace("_id","",$table_name);
       $rows = $db -> select("SELECT name FROM ".$table_name." WHERE id =".$id);
       return $rows[0]['name'];
    }
    
}
class url_help{
	public function url_update($text){
		preg_match('~&(.*?)=~', $text, $var);
		
			$prefix = "&".$var[1]."=";
			$str=$_SERVER['REQUEST_URI'];
			if (substr($str, 0, strlen($prefix)) == $prefix) {
			    $str = substr($str, strlen($prefix));
			} 
    	$result= "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$text;         
        return  $result;                      
    }    
}


?>