<?php

class validate
{
    public function lookup_user_profile($user_id){
        if($user_id)
        {
            $db = new Db();
            $result = $db -> select("SELECT firstname_thai, lastname_thai, name, phone, email, start_date, national_id FROM employee WHERE id = ".$user_id."");
            if ($result){
                return $result;
            }
        }
    }
    public function lookup_if_rows_exist($id,$table){
        if(isset($id)&&isset($table))
        {
            $db = new Db();
            $result = $db -> select("SELECT count(id) FROM ".$table." WHERE id = ".$id."");
            
            if ($result){
                if ($result[0]['count(id)']==0){
                    return 1;
                }else if ($result[0]['count(id)'] > 0){
                    return 0;
                }
                
            }
        }
    }
    public function set_db_enviroment($db_list){
        if($db_list)
        {
            $db = new Db();
            $unneed_column = array
            ("id",
             "created", 
             "modified",
             "owner");
            foreach ($db_list as $key => $value) {
                foreach ($db_list[$key] as $key2 => $value2) {
                    $result=validate::column_head($value2);
                    $temp[$value2]=explode(',', $result);
                    foreach ($temp[$value2] as $key3 => $value3) {
                        if (!in_array($value3, $unneed_column)){
                         $re_temp[$key][$value2][$value3]="";
                        }
                    } 
                }                            
             }              
             return $re_temp;
        }else{
            return false;
        }
    }
    public function lookup_prepare_db_name($task_list){
        if ($task_list)
        {
            $db = new Db();        
            //var_dump($task_list);
            $db_name;
            foreach ($task_list as $key => $value) {
                foreach ($task_list[$key] as $key2 => $value2) {
                    $result = $db -> select("SELECT db_reference_id FROM task_db_log WHERE task_id = ".$task_list[$key][$key2]['id']."");
                    if ($result){
                        foreach ($result as $key3 => $value3) {
                            $temp=validate::lookup_join_table('db_reference_id',$result[$key3]['db_reference_id']);
                            $db_name[$task_list[$key][$key2]['id']][]=$temp[0]['db_name'];
                        }
                    }
                }
            }
            return $db_name;
        }else
        {
            return false;
        }       
    }
    public function lookup_task_from_job($job_list){        
        if ($job_list)
        {
            $db = new Db();
            //var_dump($job_list);
            foreach ($job_list as $key => $value) {
                $user_id=$key;
                foreach ($job_list[$key] as $key2=> $value2) {
                        $task = $db -> select("SELECT id, name, description,task_parent_id,task_action_type_id,task_status_id FROM task WHERE job_id = ".$job_list[$key][$key2]['job_id']."");
                        foreach ($task as $key3 => $value3) {
                               $temp= validate::check_status($task[$key3]['task_status_id'],'task_status_id'); 
                               if ($temp){
                                    $job_info=validate::lookup_join_table('job_id',$job_list[$key][$key2]['job_id']);
                                    $task_action_type=validate::lookup_join_table('task_action_type_id',$task[$key3]['task_action_type_id']);
                                    $value3['job_name']=$job_info[0]['name'];
                                    $value3['job_id']=$job_list[$key][$key2]['job_id'];
                                    $value3['task_action_type']= $task_action_type[0]['name'];
                                    $remix_task[$user_id][]=$value3; 
                                }                
                        }
                }
            }
            
            //var_dump($remix_task);    
            if (isset($remix_task)){
                return $remix_task;
            }else{
                return false;
            }
        }       
    }
    public function lookup_working_job($user_id){
        $db = new Db();
        $user = $db -> select("SELECT job_id, job_log_status_id FROM job_log WHERE employee_id = ".$user_id."");
        $current_job=array();
        foreach ($user as $key => $value) {
            $temp= validate::check_status($user[$key]['job_log_status_id'],'job_log_status_id'); 
               if ($temp){
                    $remix_job[$user_id][$user[$key]['job_id']]=$value;  
                }else{
                    unset($remix_job[$user_id][$user[$key]['job_id']]);
                }
        }
        if (isset($remix_job)){
            return $remix_job;
        }else{
            return false;
        }  
    }
    public function check_status($input,$base_id){
        $db = new Db();
        if (strpos($base_id, '_id')){
            $base = rtrim($base_id,'_id');
        }
        $boolean = $db -> select("SELECT name FROM ".$base." WHERE boolean = 1");
        foreach ($boolean as $key => $value) {
            $yes[]=$boolean[$key]['name'];
        }
        $boolean = $db -> select("SELECT name FROM ".$base." WHERE boolean = 0");
        foreach ($boolean as $key => $value) {
            $no[]=$boolean[$key]['name'];
        }
        $status=validate::lookup_join_table($base_id,$input);
        if (!isset($yes) || !isset($no))
        {
            return false;
        }else
        if (in_array($status[0]['name'],$yes)){
            return $input;
        }else
        if (in_array($status[0]['name'],$no)){
            return false;
        }else{
            return false;
        }
     }
    public function lookup_join_table($base_id,$value){
        $db = new Db();
        if (isset($base_id)){
            if (strpos($base_id, '_id')){
                $base = rtrim($base_id,'_id');
            }
            if (strpos($base_id, '_parent_id')){
                $base=str_replace('_parent_id', '', $base_id);
            }
            $result = $db -> select("SELECT * FROM ".$base." WHERE id = ".$value);
            if ($result){
                return $result;
            }
            else{
                return false;
            }
        }else{
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
                                
            }
        }   
        $rows = $db -> select("SHOW COLUMNS FROM ".$post['form_title']);
        //var_dump($rows);  
        foreach ($rows as $key => $value) 
        {   
            $query_head .=$value['Field'].",";                 
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
    public function insert_multiple($post,$num){
    $db = new Db(); 
    $validate= new validate();
    $query_head ="";
    $query_value ="";   
    $post['id']="DEFAULT";
    $post['created']="CURRENT_TIMESTAMP";
    $post['modified']="CURRENT_TIMESTAMP";
    $post['owner']=$_SESSION['employee_id'];
    $disable_input_list= array("form_title","act");
    $disable_append_quote= array("CURRENT_TIMESTAMP","DEFAULT");
    foreach ($post as $key => $value) {
       if(strpos($key, "_")){   
            $explode=explode("_", $key);         
            $explode_list[$explode[0]]=0;
       }
    }
    foreach ($post as $key => $value) {
       if(strpos($key, "_")){ 
            $explode=explode("_", $key); 
            if (array_key_exists($explode[0], $explode_list)) {
                $explode_list[$explode[0]]++;
            }         
       }
    }
    $explode_list_else=$explode_list;
    foreach ($explode_list as $key => $value) {
        if ($value <2){
            unset($explode_list[$key]);
        }
    }
    $explode_list = array_filter($explode_list);
    if (!empty($explode_list)) {
            foreach ($post as $key => $value) {
                if (!in_array($key, $disable_input_list)) 
                {
                                    
                }
            }   
            $rows = $db -> select("SHOW COLUMNS FROM ".$post['form_title']);
            //var_dump($rows);  
            foreach ($rows as $key => $value) 
            {   
                $query_head .=$value['Field'].",";                 
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
                        $temp_value_count=0;
                        foreach ($explode_list as $key2 => $value2) {
                            if (strpos($value['Field'], $key2) !== false){
                                $query_value .=$post[$value['Field'].$num].",";
                                $temp_value_count=1;
                            }                        
                        }

                        if (strpos($value['Field'], $key2) == false && $temp_value_count==0){
                                $query_value .=$post[$value['Field']].",";
                        }
                      
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
        }else{
            foreach ($explode_list_else as $key => $value) {
                foreach ($post as $key2 => $value2) {
                    $last=substr($key2, -1);
                    if(is_numeric(substr($key2, -1))){
                        $post[rtrim($key2,$last)]=$value2;
                        unset($post[$key2]);
                    }
                    
                }
            }
           return $validate -> insert($post);  
        }
    }        
}
?>