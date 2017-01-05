<?php

class validate
{
    public function column_head($input){
        $db = new Db();
        $column_list="";
        $database_column = $db -> select("SHOW COLUMNS FROM ".$input);
        foreach ($database_column as $key => $value) {
            $column_list .= $value['Field'].",";
        }
        $column_list = rtrim($column_list,',');
        return $column_list;
    }
    public function table_list(){
        $db = new Db();
        $database_table = $db -> query("show tables from bathline_v2");
        foreach ($database_table as $key => $value) {
            $table_list[] = $value['Tables_in_bathline_v2'];
        }
        return $table_list;
    }
    public function edit($post){
    $db = new Db(); 
    $query_head ="";
    $query_value ="";   
    $post['id']="id";
    $post['created']="created";
    $post['modified']="CURRENT_TIMESTAMP";
    $post['owner']='owner';
    $disable_input_list= array("form_title","act");
    $disable_append_quote= array("CURRENT_TIMESTAMP","DEFAULT");
        foreach ($post as $key => $value) {
            if (!in_array($key, $disable_input_list)) 
            {
                $query_head .=$key.",";                 
            }
        }   
        $rows = $db -> select("SHOW COLUMNS FROM ".$post['form_title']);
        //var_dump($rows);  
        foreach ($rows as $key => $value) 
        {                   
            if (preg_match("~\btimestamp\b~",$value["Type"]) )
            {   
                $query_value .=$value['Field']."="."CURRENT_TIMESTAMP,";         
            }
            if (preg_match("~\bdatetime\b~",$value["Type"]) )
            {   
                if ($value['Field']=="created"){
                    $query_value .= $value['Field']."=".$post[$value['Field']].",";
                }
                else{
                    $query_value .= $value['Field']."='".$post[$value['Field']]."',";
                }  
            }
            if (preg_match("~\bdate\b~",$value["Type"]) ) 
            {   
                $datetime = strtotime($post[$value['Field']]);
                $mysqldate = date("Y-m-d", $datetime);
                $query_value .= $value['Field']."='".$mysqldate."',";            
            }
            if (preg_match("~\bint\b~",$value["Type"]) )
            {                   
                $query_value .=$value['Field']."=".$post[$value['Field']].",";                              
            }
            if (preg_match("~\bvarchar\b~",$value["Type"]) )
            {               
                   
                if ($value['Field']=="created"){
                    $query_value .= $value['Field']."=".$post[$value['Field']].",";
                }
                else{
                    $query_value .= $value['Field']."='".$post[$value['Field']]."',";
                }
            }
            if (preg_match("~\btext\b~",$value["Type"]) )
            {               
                $query_value .= $value['Field']."='".$post[$value['Field']]."',";  
            }
            if (preg_match("~\bemail\b~",$value["Type"]) )
            {               
                $query_value .= $value['Field']."='".$post[$value['Field']]."',";  
            }
            
        }            
            $sql['value']=rtrim($query_value,",");
            $sql['value'].=" WHERE id =".$post['doc'];
            
            return $sql;
    }
    public function validate_variable_type($input){
        
    }

    public function insert($post){
    $db = new Db(); 
    $query_head ="";
    $query_value ="";   
    $post['id']="DEFAULT";
    $post['created']="CURRENT_TIMESTAMP";
    $post['modified']="CURRENT_TIMESTAMP";
    $post['owner']='1';
    $disable_input_list= array("form_title","act");
    $disable_append_quote= array("CURRENT_TIMESTAMP","DEFAULT");
        foreach ($post as $key => $value) {
            if (!in_array($key, $disable_input_list)) 
            {
                $query_head .=$key.",";                 
            }
        }   
        $rows = $db -> select("SHOW COLUMNS FROM ".$post['form_title']);
        //var_dump($rows);  
        foreach ($rows as $key => $value) 
        {                   
            if (strpos($value["Type"] , "timestamp") !== false) 
            {   
                $query_value .=$post[$value['Field']].",";         
            }
            if ($value["Type"]== "datetime") 
            {   
                $query_value .="".$post[$value['Field']].",";     
            }
            if ($value["Type"]== "date") 
            {   
                $datetime = strtotime($post[$value['Field']]);
                $mysqldate = date("Y-m-d", $datetime);
                $query_value .= "'".$mysqldate."',";            
            }
            if (strpos($value["Type"] , "int") !== false) 
            {   
                
                if ($post[$value['Field']] == "")
                {
                    $query_value .="NULL,";
                }
                else
                {
                    $query_value .=$post[$value['Field']].",";
                }                       
                            
            }
            if (strpos($value["Type"] , "varchar") !== false) 
            {               
                $query_value .= "'".$post[$value['Field']]."',";   
            }
            if (strpos($value["Type"] , "text") !== false) 
            {               
                $query_value .= "'".$post[$value['Field']]."',";   
            }
            if (strpos($value["Type"] , "email") !== false) 
            {               
                $query_value .= "'".$post[$value['Field']]."',";   
            }
            
        }
            $sql['head']=rtrim($query_head,",");
            $sql['value']=rtrim($query_value,",");
            
            return $sql;
    }    
}
?>