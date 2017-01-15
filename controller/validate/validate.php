<?php

class validate
{
    public function lookup_working_job($base_id,$id){
        $db = new Db();
        $user = $db -> select("SELECT job_id, job_log_status_id FROM job_log WHERE employee_id = ".$user_id." ORDER BY id DESC");
        foreach ($user as $key => $value) {
           if (strpos($base_id, '_id')){
            $base = rtrim($base_id,'_id');
            }  
        }
        if (strpos($base_id, '_id')){
            $base = rtrim($base_id,'_id');
        }        
        $result = $db -> select("SELECT * FROM ".$base." WHERE id = ".$id);
        if ($result)
        {
            return $result;
        }
        else
        {
            return false;
        }
    }
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
            $sql['value'].= " AND ( ";
            foreach ($rows as $key => $value) 
            {   
                //modified
                if (preg_match("~\btimestamp\b~",$value["Type"]) )
                {   
                    $sql['value'].="".$value['Field']." != modified OR ";       
                }
                if (preg_match("~\bdatetime\b~",$value["Type"]) )
                {   
                    if ($value['Field']=="created"){
                        $sql['value'].="".$value['Field']." != ".$post[$value['Field']]." OR ";
                    }
                    else{
                        $sql['value'].="".$value['Field']." != '".$post[$value['Field']]."' OR ";
                    }  
                }
                if (preg_match("~\bdate\b~",$value["Type"]) ) 
                {   
                    $datetime = strtotime($post[$value['Field']]);
                    $mysqldate = date("Y-m-d", $datetime);
                    $sql['value'].="".$value['Field']." != '".$mysqldate."' OR ";         
                }
                if (preg_match("~\bint\b~",$value["Type"]) )
                {                   
                    $sql['value'].="".$value['Field']." != ".$post[$value['Field']]." OR ";                              
                }
                if (preg_match("~\bvarchar\b~",$value["Type"]) )
                {               
                       
                    if ($value['Field']=="created"){
                        $sql['value'].="".$value['Field']." != ".$post[$value['Field']]."' OR ";
                    }
                    else{
                        $sql['value'].="".$value['Field']." != '".$post[$value['Field']]."' OR ";
                    }
                }
                if (preg_match("~\btext\b~",$value["Type"]) )
                {               
                    $sql['value'].="".$value['Field']." != '".$post[$value['Field']]."' OR ";
                }
                if (preg_match("~\bemail\b~",$value["Type"]) )
                {               
                    $sql['value'].="".$value['Field']." != ".$post[$value['Field']]."' OR "; 
                }
                    
                }
            $sql['value']= preg_replace('/\W\w+\s*(\W*)$/', '$1', $sql['value']);
            $sql['value'].= " )";
            //echo $sql['value'];
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
    $post['owner']=$_SESSION['employee_id'];
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
                /*
                if ($post[$value['Field']] == "")
                {
                    $query_value .="NULL,";
                }
                else
                {*/
                    $query_value .=$post[$value['Field']].",";
                //}                       
                            
            }
             if (strpos($value["Type"] , "decimal") !== false) 
            {                  
                $query_value .=$post[$value['Field']].",";   
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