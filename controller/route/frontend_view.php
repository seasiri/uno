<?php
class frontend_view{
    public function breadcrumb($info,$get){
        foreach ($info['task'] as $key => $value) {
                if ($info['task'][$key]['id']==$get['task']){
                    if (isset($_GET['doc'])&&isset($_GET['db'])){
                        $url= $_SERVER['QUERY_STRING'];
                        $breadcrumb_url_list=explode("&", $_SERVER['QUERY_STRING']);
                        $back_url="";
                        foreach ( $breadcrumb_url_list as $key2 => $value2) {
                            if (strpos($value2 , "doc") !== false){
                                $back_url=str_replace($value2, "",  $url);
                            }
                            if (strpos($value2 , "db") !== false){
                                $back_url=str_replace($value2, "",  $url);
                            }
                            $url = $back_url;
                        }
                        $url=str_replace("&&", "&",  $url);
                        $url=ltrim($url, '&');
                        echo "<h3>".$info['task'][$key]['name']." /<small><a href='/?".$url."'>grid</a>/<code>".$_GET['act']."->doc_id:".$_GET['doc']."</code></small></h3><hr>";
                    }else{
                        echo "<h3>".$info['task'][$key]['name']."</h3><hr>";
                    }
                    return $task_action_type=$info['task'][$key]['task_action_type'];
                }
            }
    }
    public function template($info,$get){
        if (!empty($get)&&isset($get['act'])&&isset($get['task']))
        {

            $frontend_view = new frontend_view();
            $view = new view();
            ///BREADCRUMB
            $task_action_type = frontend_view::breadcrumb($info,$get);
            //ACT DECISION
            foreach ($info['db'] as $key => $value) {
                foreach ($info['db'][$key] as $key2 => $value2) {
                    if ($key==$get['task'] && $get['act']==strtolower($task_action_type)){
                        switch ($get['act']) {
                            case 'insert':
                                $frontend_view -> insert($key2); 
                                break;
                            case 'edit':
                                if (isset($get['doc'])){
                                    $frontend_view -> edit($key2,$get['doc']);
                                }else{
                                    $view -> grid($key2);
                                }
                                break;
                            case 'edit_credential_time':                     
                                if (isset($get['doc'])){
                                     $frontend_view -> edit_credential_time($key2,$get['doc']);
                                }else{
                                    $view -> grid($key2);
                                }
                                break;
                            case 'approval':
                                if (isset($get['filter'])){
                                    $frontend_view -> approval($key2,$info['job']); 
                                }
                                break;
                            case 'pending_approval':
                                # code...
                                break;
                            case 'insert_multiple': 
                                if(isset($get['row'])&&isset($get['skp'])){                                                      
                                    $frontend_view -> insert_multiple($key2);
                                }
                                break;
                            default:
                                # code...
                                break;
                        }
                    }                
                }
            }
        }       
    }
    public function navigation($info){
        
        foreach ($info['job'] as $key => $value) {
                $i=0;                               
                foreach ($info['task'] as $key2 => $value2) {
                    //headerr
                    if($i==0 && $info['task'][$key2]['job_id']==$info['job'][$key]['job_id']){
                        echo "<a href='/'>@<b>".$info['task'][$key2]['job_name']."</b></a><br>";
                        $i++;
                    }
                    //child
                    if ($info['task'][$key2]['job_id']==$info['job'][$key]['job_id'])
                    {
                        switch ($info['task'][$key2]['id']) {
                            case '30':
                                echo "<a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$info['task'][$key2]['id']."&row=1&skp=agent_id'>-- ".$info['task'][$key2]['name']."</a><br>";
                                break;
                            case '42':
                                echo "<a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$info['task'][$key2]['id']."&row=1&skp=product_id'>-- ".$info['task'][$key2]['name']."</a><br>";
                                break;
                            case '15':
                                echo "<a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$info['task'][$key2]['id']."&row=1&skp=order_list_id'>-- ".$info['task'][$key2]['name']."</a><br>";
                                break;
                            default:
                                if (isset($_GET['task'])){
                                    if ($info['task'][$key2]['id']==$_GET['task']){
                                        echo "<code><e href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$info['task'][$key2]['id']."'><b>-->".$info['task'][$key2]['name']."</e></b></code><br>";
                                    }else{
                                        echo "<a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$info['task'][$key2]['id']."'>-- ".$info['task'][$key2]['name']."</a><br>";
                                    }
                                }else{
                                        echo "<a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$info['task'][$key2]['id']."'>-- ".$info['task'][$key2]['name']."</a><br>";
                                }
                                break;
                        }
                        
                    }
                                        
                }
            
        }
    }
    
    public function edit($title,$doc_id){
        $db = new Db();
        $view = new view();
        $doc_raw = $db -> select("SELECT * FROM  ".$title." WHERE id = ".$doc_id."");       
        if (!$doc_raw)
        {
            return "doc_id_not_found";
        }
        foreach ($doc_raw as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $doc[$key2] = $value2;
            }
        }        
        $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner')");
        $i=0;
        echo '<form action="/public/action/frontend_submit.php"method="post" enctype="multipart/form-data">'; 
        echo '<input id="fileToUpload" name="fileToUpload" type="file" class="file" /><br>';
        echo '<input type="hidden" name="act" value="'.$_GET['act'].'">';
        echo '<input type="hidden" name="doc" value="'.$_GET['doc'].'">';
        echo '<input type="hidden" name="form_title" value="'.$title.'">';
        echo '<input type="hidden" name="task" value="'.$_GET['task'].'">';
        foreach ($rows as $key => $value) 
        {                             
                if ($i%3==0)
                {
                    echo '<div class="row">';
                    echo '<div class="col-md-4">';
                }else
                {
                    echo '<div class="col-md-4">';
                }   
                if (preg_match("~\bdate\b~",$value["Type"]) ) 
                {   
                    $datetime = strtotime($doc[$value["Field"]]);
                    $mysqldate = date("Y-m-d", $datetime);                                                 
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'"  placeholder="" value="'.$mysqldate.'">';
                    echo '</div>';              
                }
                if (preg_match("~\bdatetime\b~",$value["Type"]) ) 
                {   
                    $datetime = strtotime($doc[$value["Field"]]);
                    $mysqldate = date("Y-m-d", $datetime);                             
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'"  placeholder="" value="'.$mysqldate.'">';
                    echo '</div>';              
                }
                if (preg_match("~\btimestamp\b~",$value["Type"]) )  
                {   
                    $datetime = strtotime($doc[$value["Field"]]);
                    $mysqldate = date("Y-m-d", $datetime);                              
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'"  placeholder="" value="'.$mysqldate.'">';
                    echo '</div>';              
                }
                if (preg_match("~\bint\b~",$value["Type"]) ) 
                {
                    $temp['column']=$value["Field"];
                    $temp['session']=$title;
                    $temp['doc_id']=$doc_id;
                    $view -> int_handle($temp);               
                }
                if (preg_match("~\bdecimal\b~",$value["Type"]) ) 
                {
                    $temp['column']=$value["Field"];
                    $temp['session']=$title;
                    $temp['doc_id']=$doc_id;
                    $view -> int_handle($temp);               
                }
                if (preg_match("~\bvarchar\b~",$value["Type"]) ) 
                {               
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="" value="'.$doc[$value["Field"]].'">';
                    echo '</div>';              
                }
                if (preg_match("~\btext\b~",$value["Type"]) ) 
                {               
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="" value="'.$doc[$value["Field"]].'">';
                    echo '</div>';              
                }
                if (preg_match("~\bemail\b~",$value["Type"]) ) 
                {               
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="email" class="form-control input-sm  " name="'.$value["Field"].'" placeholder="" value="'.$doc[$value["Field"]].'">';
                    echo '</div>';              
                }
                if ($i%3==2)
                {
                    echo '</div>';
                    echo '</div>';                  
                }               
                else                
                {
                    echo '</div>';
                }   
                $i++;
            
        }
        echo '<div class="row"><div class="col-md-10"></div><div class="col-md-2">';
        echo      '<button type="submit" class="btn btn-default">UPDATE</button>';
        echo '</div></div>';
    }
     public function edit_credential_time($title,$doc_id){
        $db = new Db();
        $view = new view();
        $doc_raw = $db -> select("SELECT * FROM  ".$title." WHERE id = ".$doc_id." AND owner =".$_SESSION['employee_id']." AND created  BETWEEN CURDATE() - INTERVAL 1 DAY  AND CURDATE() + INTERVAL 1 DAY ");       
        if (!$doc_raw)
        {
            return "doc_id_not_found";
        }
        foreach ($doc_raw as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $doc[$key2] = $value2;
            }
        }        
        $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner')");
        $i=0;
        echo '<form action="/public/action/frontend_submit.php"method="post" enctype="multipart/form-data">'; 
        echo '<input id="fileToUpload" name="fileToUpload" type="file" class="file" /><br>';
        echo '<input type="hidden" name="act" value="'.$_GET['act'].'">';
        echo '<input type="hidden" name="doc" value="'.$_GET['doc'].'">';
        echo '<input type="hidden" name="form_title" value="'.$title.'">';
        echo '<input type="hidden" name="task" value="'.$_GET['task'].'">';
        foreach ($rows as $key => $value) 
        {                             
                if ($i%3==0)
                {
                    echo '<div class="row">';
                    echo '<div class="col-md-4">';
                }else
                {
                    echo '<div class="col-md-4">';
                }   
                if (preg_match("~\bdate\b~",$value["Type"]) ) 
                {   
                    $datetime = strtotime($doc[$value["Field"]]);
                    $mysqldate = date("Y-m-d", $datetime);                                                 
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'"  placeholder="" value="'.$mysqldate.'">';
                    echo '</div>';              
                }
                if (preg_match("~\bdatetime\b~",$value["Type"]) ) 
                {   
                    $datetime = strtotime($doc[$value["Field"]]);
                    $mysqldate = date("Y-m-d", $datetime);                             
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'"  placeholder="" value="'.$mysqldate.'">';
                    echo '</div>';              
                }
                if (preg_match("~\btimestamp\b~",$value["Type"]) )  
                {   
                    $datetime = strtotime($doc[$value["Field"]]);
                    $mysqldate = date("Y-m-d", $datetime);                              
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'"  placeholder="" value="'.$mysqldate.'">';
                    echo '</div>';              
                }
                if (preg_match("~\bint\b~",$value["Type"]) ) 
                {
                    $temp['column']=$value["Field"];
                    $temp['session']=$title;
                    $temp['doc_id']=$doc_id;
                    $view -> int_handle($temp);               
                }
                if (preg_match("~\bdecimal\b~",$value["Type"]) ) 
                {
                    $temp['column']=$value["Field"];
                    $temp['session']=$title;
                    $temp['doc_id']=$doc_id;
                    $view -> int_handle($temp);               
                }
                if (preg_match("~\bvarchar\b~",$value["Type"]) ) 
                {               
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="" value="'.$doc[$value["Field"]].'">';
                    echo '</div>';              
                }
                if (preg_match("~\btext\b~",$value["Type"]) ) 
                {               
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="" value="'.$doc[$value["Field"]].'">';
                    echo '</div>';              
                }
                if (preg_match("~\bemail\b~",$value["Type"]) ) 
                {               
                    echo '<div class="form-group">';
                    echo '    <label>'.$value["Field"].'</label>';
                    echo '    <input type="email" class="form-control input-sm  " name="'.$value["Field"].'" placeholder="" value="'.$doc[$value["Field"]].'">';
                    echo '</div>';              
                }
                if ($i%3==2)
                {
                    echo '</div>';
                    echo '</div>';                  
                }               
                else                
                {
                    echo '</div>';
                }   
                $i++;
            
        }
        echo '<div class="row"><div class="col-md-10"></div><div class="col-md-2">';
        echo      '<button type="submit" class="btn btn-default">UPDATE</button>';
        echo '</div></div>';
    }
    public function insert($title){
            $db = new Db();
            $view = new view();
            $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner');");
            $i=0;
            echo '<form action="/public/action/frontend_submit.php" method="post" enctype="multipart/form-data">'; 
            echo '<input id="fileToUpload" name="fileToUpload" type="file" class="file" /><br>';
            echo '<input type="hidden" name="act" value="'.$_GET['act'].'">';
            echo '<input type="hidden" name="form_title" value="'.$title.'">';
            echo '<input type="hidden" name="task" value="'.$_GET['task'].'">';
            
            foreach ($rows as $key => $value) 
            {                             
                    if ($i%3==0)
                    {
                        echo '<div class="row">';
                        echo '<div class="col-md-4">';
                    }else
                    {
                        echo '<div class="col-md-4">';
                    }   
                    
                    if (strpos($value["Type"] , "date") !== false) 
                    {                               
                        echo '<div class="form-group">';
                        echo '    <label>'.$value["Field"].'</label>';
                        echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'" value="'.date("m-d-Y").'" placeholder="">';
                        echo '</div>';              
                    }
                    if (strpos($value["Type"] , "timestamp") !== false) 
                    {                               
                        echo '<div class="form-group">';
                        echo '    <label>'.$value["Field"].'</label>';
                        echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'" value="'.date("m-d-Y").'" placeholder="">';
                        echo '</div>';              
                    }
                    if (strpos($value["Type"] , "int") !== false) 
                    { 
                        $temp['column']=$value["Field"];
                        $temp['session']=$title;
                        $view -> int_handle($temp);

                    }
                    if (strpos($value["Type"] , "decimal") !== false) 
                    { 
                        $temp['column']=$value["Field"];
                        $temp['session']=$title;
                        $view -> int_handle($temp);

                    }
                    if (strpos($value["Type"] , "varchar") !== false) 
                    {               
                        echo '<div class="form-group">';
                        echo '    <label>'.$value["Field"].'</label>';
                        echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                        echo '</div>';              
                    }
                    if (strpos($value["Type"] , "text") !== false) 
                    {               
                        echo '<div class="form-group">';
                        echo '    <label>'.$value["Field"].'</label>';
                        echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                        echo '</div>';              
                    }
                    if (strpos($value["Type"] , "email") !== false) 
                    {               
                        echo '<div class="form-group">';
                        echo '    <label>'.$value["Field"].'</label>';
                        echo '    <input type="email" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                        echo '</div>';              
                    }
                
                    if ($i%3==2)
                    {
                        echo '</div>';
                        echo '</div>';                  
                    }               
                    else                
                    {
                        echo '</div>';
                    }
                    $i++;                
            }
            if ($i%3==2 ){
                echo '</div>';
            }
            echo '<div class="row"><div class="col-md-10"></div><div class="col-md-2">';
            echo      '<button type="submit" class="btn btn-default">Submit</button>';
            echo '</div></div>';
        }
        public function insert_multiple($title){
            if (isset($_GET['row']) && $_GET['row']>0 && $_GET['row']<11){
                $db = new Db();
                $view = new view();
                    echo '<form action="/public/action/frontend_submit.php" method="post" >'; 
                    echo '<input type="hidden" name="act" value="'.$_GET['act'].'">';
                    echo '<input type="hidden" name="form_title" value="'.$title.'">';
                    echo '<input type="hidden" name="task" value="'.$_GET['task'].'">';
                    echo '<input type="hidden" name="row" value="'.$_GET['row'].'">';
                    echo '<input type="hidden" name="skp" value="'.$_GET['skp'].'">';
                for ($i=0; $i < $_GET['row']; $i++) {  
                    $count[]=$i;
                } 
                
                $url_explode=explode("&", $_SERVER['REQUEST_URI']);
                foreach ($url_explode as $key => $value) {
                    if (strpos($value , "row") !== false){
                        $row_explode=explode("=", $value);
                    }                                
                }
                echo "<code>*จำนวนช่อง เลือกให้พอดี</code>";
                for ($i=1; $i < 11; $i++) {  
                    $keep= str_replace("&row=".$row_explode[1]."&","&row=".$i."&",$_SERVER['REQUEST_URI']);
                    echo "<a href='".$keep."'> ".$i." </a>";
                } 
                echo "<br><br>";
                $form_count=0; 
                $first_time_of_row=0;  
                foreach ($count as $key => $value) {   
                    $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner');");
                    $i=0;                
                    foreach ($rows as $key => $value) 
                    {   
                            if ($value["Field"]!=$_GET['skp']){    
                                $temp=array();                
                                    if ($i%4==0)
                                    {
                                        echo '<div class="row">';
                                        echo '<div class="col-md-3">';
                                    }else
                                    {
                                        echo '<div class="col-md-3">';
                                    }   
                                    
                                    if (strpos($value["Type"] , "date") !== false) 
                                    {                               
                                        echo '<div class="form-group">';
                                        echo '    <label>'.$value["Field"].'</label>';
                                        echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'" value="'.date("m-d-Y").'" placeholder="">';
                                        echo '</div>';              
                                    }
                                    if (strpos($value["Type"] , "timestamp") !== false) 
                                    {                               
                                        echo '<div class="form-group">';
                                        echo '    <label>'.$value["Field"].'</label>';
                                        echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'" value="'.date("m-d-Y").'" placeholder="">';
                                        echo '</div>';              
                                    }
                                    if (strpos($value["Type"] , "int") !== false) 
                                    { 

                                        if ($value["Field"]=="agent_id"){
                                            //check credential of user on selectting drop down
                                            $temp['user_id']=$_SESSION['employee_id'];
                                            $temp['refer_table']='retail_pc';
                                        }
                                        $temp['column']=$value["Field"];
                                        $temp['session']=$title;
                                        //for multiple insert row
                                        $temp['form_count']=$form_count;
                                        $view -> int_handle($temp);

                                    }
                                    if (strpos($value["Type"] , "decimal") !== false) 
                                    { 
                                        $temp['column']=$value["Field"];
                                        $temp['session']=$title;
                                        $view -> int_handle($temp);

                                    }
                                    if (strpos($value["Type"] , "varchar") !== false) 
                                    {               
                                        echo '<div class="form-group">';
                                        echo '    <label>'.$value["Field"].'</label>';
                                        echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                                        echo '</div>';              
                                    }
                                    if (strpos($value["Type"] , "text") !== false) 
                                    {               
                                        echo '<div class="form-group">';
                                        echo '    <label>'.$value["Field"].'</label>';
                                        echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                                        echo '</div>';              
                                    }
                                    if (strpos($value["Type"] , "email") !== false) 
                                    {               
                                        echo '<div class="form-group">';
                                        echo '    <label>'.$value["Field"].'</label>';
                                        echo '    <input type="email" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                                        echo '</div>';              
                                    }
                                    
                                    if ($i%4==3)
                                    {
                                        echo '</div>';
                                        echo '</div>';                  
                                    }               
                                    else                
                                    {
                                        echo '</div>';
                                    }
                                    $i++;
                                    $first_time_of_row++;
                                }else{
                                    if ($first_time_of_row==0){
                                        if (strpos($value["Type"] , "int") !== false) 
                                        { 
                                            if ($value["Field"]=="agent_id"){
                                                $temp['user_id']=$_SESSION['employee_id'];
                                                $temp['refer_table']='retail_pc';
                                            }
                                            $temp['column']=$value["Field"];
                                            $temp['session']=$title;
                                            $view -> int_handle($temp);
                                            echo "<hr>";
                                        }
                                    }
                                }            
                        }
                        if ($i%4==3 ){
                            echo '</div>';
                    }
                    $form_count ++;   //count loop for adding numbberr after name attribute
                    if ($i%4==1 || $i%4==2 ){
                        echo '</div>';
                    }  
                }
              
                echo '<div class="row"><div class="col-md-10"></div><div class="col-md-2">';
                echo      '<button type="submit" class="btn btn-default">Submit</button>';
                echo '</div></div>';
            }
        }
        public function approval($title,$job){
                $db = new Db();
                $view = new view();
                $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner');");
                $i=0;
                echo '<form action="/public/action/frontend_submit.php" method="post" enctype="multipart/form-data">'; 
                echo '<input id="fileToUpload" name="fileToUpload" type="file" class="file" /><br>';
                echo '<input type="hidden" name="act" value="'.$_GET['act'].'">';
                echo '<input type="hidden" name="form_title" value="'.$title.'">';
                echo '<input type="hidden" name="task" value="'.$_GET['task'].'">';
                
                foreach ($rows as $key => $value) 
                {                             
                        if ($i%3==0)
                        {
                            echo '<div class="row">';
                            echo '<div class="col-md-4">';
                        }else
                        {
                            echo '<div class="col-md-4">';
                        }   
                        
                        if (strpos($value["Type"] , "date") !== false) 
                        {                               
                            echo '<div class="form-group">';
                            echo '    <label>'.$value["Field"].'</label>';
                            echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'" value="'.date("m-d-Y").'" placeholder="">';
                            echo '</div>';              
                        }
                        if (strpos($value["Type"] , "timestamp") !== false) 
                        {                               
                            echo '<div class="form-group">';
                            echo '    <label>'.$value["Field"].'</label>';
                            echo '    <input type="date" class="form-control input-sm" name="'.$value["Field"].'" value="'.date("m-d-Y").'" placeholder="">';
                            echo '</div>';              
                        }
                        if (strpos($value["Type"] , "int") !== false) 
                        { 
                                $temp['column']=$value["Field"];
                                $temp['session']=$title;
                                $temp['job']=$job;
                                $temp['refer_table']=$_GET['filter'];
                                $view -> int_handle($temp);
                        }
                        if (strpos($value["Type"] , "decimal") !== false) 
                        { 
                            $temp['column']=$value["Field"];
                            $temp['session']=$title;
                            $view -> int_handle($temp);

                        }
                        if (strpos($value["Type"] , "varchar") !== false) 
                        {               
                            echo '<div class="form-group">';
                            echo '    <label>'.$value["Field"].'</label>';
                            echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                            echo '</div>';              
                        }
                        if (strpos($value["Type"] , "text") !== false) 
                        {               
                            echo '<div class="form-group">';
                            echo '    <label>'.$value["Field"].'</label>';
                            echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                            echo '</div>';              
                        }
                        if (strpos($value["Type"] , "email") !== false) 
                        {               
                            echo '<div class="form-group">';
                            echo '    <label>'.$value["Field"].'</label>';
                            echo '    <input type="email" class="form-control input-sm" name="'.$value["Field"].'" placeholder="">';
                            echo '</div>';              
                        }
                    
                        if ($i%3==2)
                        {
                            echo '</div>';
                            echo '</div>';                  
                        }               
                        else                
                        {
                            echo '</div>';
                        }
                        $i++;                
                }
                if ($i%3==2 ){
                    echo '</div>';
                }
                echo '<div class="row"><div class="col-md-10"></div><div class="col-md-2">';
                echo      '<button type="submit" class="btn btn-default">Submit</button>';
                echo '</div></div>';
            }
}
?>