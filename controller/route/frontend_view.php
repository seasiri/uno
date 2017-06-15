<?php
class frontend_view{
    public function breadcrumb($info,$get){  
       $db_help = new db_help();     
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
                            $url="?";
                            foreach ($_GET as $key3 => $value3) {
                                if ($key3 == "act" || $key3 == "task"){
                                    $url.="&".$key3."=".$value3;
                                }
                            }
                             echo "<h3><a href='http://".$_SERVER['HTTP_HOST'].$url."#main'>".$info['task'][$key]['name']."</a>";
                             $url="?";
                             $i=0;
                            foreach ($_GET as $key3 => $value3) {
                                if ($key3 == "act" || $key3 == "task"){
                                    $url.="&".$key3."=".$value3;
                                }else
                                if ($key3 !== "act" && $key3 !== "task"&& $value3!=0){
                                    $label="";
                                    switch ($key3) {
                                        case 'sid':
                                             $label=$db_help -> url2name($value3,"supplier");
                                            break;
                                        case 'cat':
                                             $label=$db_help -> url2name($value3,"product_catagories");
                                            break;  
                                        case 'pd':
                                             $label=$db_help -> url2name($value3,"product");
                                            break;                                       
                                        default:
                                            # code...
                                            break;
                                    }
                                    if ($i==0){
                                        $url.="&".$key3."=0";
                                         echo "-> <a href='http://".$_SERVER['HTTP_HOST'].$url."#main'>menu</a>";
                                        $url.="&".$key3."=".$value3;
                                         echo "-> <a href='http://".$_SERVER['HTTP_HOST'].$url."#main'>".$label."</a>";
                                     $i++;
                                    }else{
                                    $url.="&".$key3."=".$value3;
                                     echo "-> <a href='http://".$_SERVER['HTTP_HOST'].$url."#main'>".$label."</a>";
                                    }                                   
                                }else{
                                    if ($value3==0){
                                        $url.="&".$key3."=0";
                                         echo "-> <a href='http://".$_SERVER['HTTP_HOST'].$url."#main'>menu</a>";
                                    }
                                }

                                

                            }
                            echo "</h3><hr>";
                       
                        
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
                            case 'confirm_attachment': 
                                if (isset($get['doc'])){
                                     $frontend_view -> validate_order($key2,$get['doc']);
                                }else{
                                    $view -> grid($key2);
                                }
                                break;
                            case 'bl9_01': 
                                  $frontend_view -> bl9_01($key2);
                                break;
                            case 'bl3_01': 
                                  $frontend_view -> bl3_01($key2);
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
                        $form_id=$info['task'][$key2]['id'];
                        switch ($form_id) {
                            case '30':
                                echo "<small><a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$form_id."&row=1&skp=agent_id-customer_type_id-order_ref#main'>-[F".$form_id."]- ".$info['task'][$key2]['name']."</a></small><br>";
                                break;
                            case '42':
                                echo "<small><a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$form_id."&row=1&skp=product_id#main'>-[F".$form_id."]- ".$info['task'][$key2]['name']."</a></small><br>";
                                break;
                            case '15':
                                echo "<small><a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$form_id."&row=1&skp=order_list_id#main'>-[F".$form_id."]- ".$info['task'][$key2]['name']."</a></small><br>";
                                break;
                            default:
                                if (isset($_GET['task'])){
                                    if ($form_id==$_GET['task']){
                                        echo "<code><e href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$form_id."#main'><b>-[F".$form_id."]->".$info['task'][$key2]['name']."</e></b></code><br>";
                                    }else{
                                        echo "<small><a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$form_id."#main'>-[F".$form_id."]- ".$info['task'][$key2]['name']."</a></small><br>";
                                    }
                                }else{
                                        echo "<small><a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$form_id."#main'>-[F".$form_id."]- ".$info['task'][$key2]['name']."</a></small><br>";
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
    public function validate_order($title,$doc_id){
        $db = new Db();
        $view = new view();
        $doc_raw = $db -> select("SELECT * FROM  ".$title." WHERE id = ".$doc_id." AND owner =".$_SESSION['employee_id']." ");  
        $doc_raw = $db -> select("SELECT * FROM  ".$title." WHERE order_ref = '".$doc_raw[0]['order_ref']."' AND owner =".$_SESSION['employee_id']." ");   
        if (!$doc_raw)
        {
            return "doc_id_not_found";
        }
        $i=0;
        foreach ($doc_raw as $key => $value) {

            foreach ($value as $key2 => $value2) {
                $doc[$i][$key2] = $value2;
            }
            $i++;
        }   
        echo "SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner')";
        $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner')");
        
        echo '<form action="/public/action/frontend_submit.php"method="post" enctype="multipart/form-data">'; 
        echo '<input id="fileToUpload" name="fileToUpload" type="file" class="file" /><br>';
        echo '<input type="hidden" name="act" value="'.$_GET['act'].'">';
        echo '<input type="hidden" name="doc" value="'.$_GET['doc'].'">';
        echo '<input type="hidden" name="form_title" value="'.$title.'">';
        echo '<input type="hidden" name="task" value="'.$_GET['task'].'">';
       echo "<pre>";  
       var_dump($doc);
        echo "</pre>";
        $i=0;
        $i2=0;
        $avoid = array( "order_ref",
                         "agent_id",
                         "customer_type_id"
                      );
        foreach ($doc as $key3 => $value3)  
        {
            foreach ($rows as $key => $value) 
            {                                            
                    if ($i%4==0)
                    {
                        echo '<div class="row">';
                        echo '<div class="col-md-3">';
                    }else
                    {
                        echo '<div class="col-md-3">';
                    }   
                    
                    if (in_array($value["Field"], $avoid) && $i2==0) {
                        echo '<div class="form-group">';
                        echo '    <label>'.$value["Field"].'</label>';
                        echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="" value="'.$doc[$key3][$value["Field"]].'">';
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
            }
            if ($i%4 ==2 ||  $i%4 == 1){
                echo '</div>';
            }
            $i2++;
        }
        $i=0;
        $i2=0;
        foreach ($doc as $key3 => $value3)  
        {
            foreach ($rows as $key => $value) 
            {        
                    if (!in_array($value["Field"], $avoid) ) 
                    {                                    
                        if ($i%4==0)
                        {
                            echo '<div class="row">';
                            echo '<div class="col-md-3">';
                        }else
                        {
                            echo '<div class="col-md-3">';
                        }   
                        
                        
                            echo '<div class="form-group">';
                            echo '    <label>'.$value["Field"].'</label>';
                            echo '    <input type="text" class="form-control input-sm" name="'.$value["Field"].'" placeholder="" value="'.$doc[$key3][$value["Field"]].'">';
                            echo '</div>';  
                        
                                  
                        
                       
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
                    }
                
            }
            $i2++;
        }
        echo '<div class="row"><div class="col-md-10"></div><div class="col-md-2">';
        echo      '<button type="submit" class="btn btn-default">UPDATE</button>';
        echo '</div></div>';
    }
     public function bl3_01(){
        
        
    }
    public function bl9_01(){
        $db = new Db();
        $view = new view();
        $db_help = new db_help();
        $url_help = new url_help();
        $choice="";
        foreach ($_GET as $key => $value) {
            $choice=$key;
        }
        echo "<div style='color:red;'><small><h4><b>CLASSIFIED</b></h4>Whoever knowingly and willfully communicates, furnishes, transmits, or otherwise makes available to an unauthorized person, or publishes, or uses in any manner prejudicial to the safety or interest of the company or for the benefit of any foreign company to the detriment of BATHLINE any classified information<hr></small></div>";
        switch ($choice) {
            case 'pd':
                            /*
                            ////////////////// PRODUCTS_VIEW //////////////////////
                            */
                            $list_product = $db -> select("SELECT name,id FROM  product"); 
                            $product_count = $db -> select("SELECT count(id) as count FROM  product");
                            $product_count=$product_count[0]['count'];
                            foreach ($list_product as $key => $value) {
                                $temp_list_product[$value['id']]=$value['name'];
                            }
                            
                           sort($temp_list_product);
                            if (!isset($_GET['pd'])|| $_GET['pd']==0){
                                $i2=0;
                                $i3=0;
                                foreach ($temp_list_product as $key => $value) {
                                    if ($i2%4==0)
                                    {
                                        echo '<div class="row">';
                                    }
                                        echo '<div class="col-md-3">';
                                        echo "<a href='/?act=bl9_01&task=45&pd=".$key."#main'>".$value." </a>";
                                        echo '</div>';
                                    if ($i2%4==3)
                                    {
                                        echo '</div>';
                                        $i3=1;
                                    }
                                    $i3=0;
                                    $i2++;
                                }
                                if ($i3==0){
                                    echo '</div>';
                                }
                            }else
                            {
                            $raw = $db -> select("SELECT * FROM  product WHERE id=".$_GET['pd']."");     
                            $product_attribute = $db -> select("SELECT * FROM  product_attribute_value WHERE 1"); 
                                
                                foreach ($raw as $key => $value) {
                                    $product_img = $db -> select("SELECT file_dir FROM attachment INNER JOIN product ON attachment.record_document = ".$value['id']." WHERE attachment_type_id=2 LIMIT 1");   

                                    
                                        echo '<div class="col-md-12"><hr>';
                                        echo '<div class="row">';
                                         echo '<div class="col-md-6">';
                                             echo '<div class="row">';
                                             if ($product_img){
                                             echo '<img class="img-responsive" src="'.$product_img[0]['file_dir'].'" alt="'.$value['name'].'" >';
                                             }else{
                                             echo '<img class="img-responsive" src="/public/upload/default/no_picture.png">';
                                             }
                                             echo '</div><hr>';
                                             echo '<div class="row">';
                                             echo "สถานะ: ";  $db_help -> id2name($value['product_status_id'],"product_status_id");
                                             echo '</div>';
                                         echo '</div>';
                                         echo '<div class="col-md-6">'; 
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-12">'; 
                                                     echo "<h4>:<code>".$value['name']." (".$value['name_english'].")</code></h4>";
                                                     echo "</div>";
                                                 echo "</div>";  
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "หมวดหมู่"; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo ":<code><a href='/?act=bl9_01&task=45&cat=".$value['product_catagories_id']."#main'>";$db_help -> id2name($value['product_catagories_id'],"product_catagories_id");
                                                     echo "</a></code>";
                                                     echo "</div>";
                                                 echo "</div>"; 
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "barcode "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo ":<code>".$value['barcode']."</code>";
                                                     echo "</div>";
                                                 echo "</div>";
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "อธิบาย(สั่น): "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo "<textarea class='form-control' disabled rows='2' cols='50'>".$value['short_description']."</textarea>";
                                                     echo "</div>";
                                                 echo "</div>";            
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "อธิบาย(ยาว): "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo "<textarea class='form-control' disabled rows='3' cols='50'>".$value['long_description']."</textarea>";
                                                     echo "</div>";
                                                 echo "</div>";     
                                            echo "<h4>ATTRIBUTE:</h4>";
                                            //var_dump($product_attribute);
                                                     foreach ($product_attribute as $key2 => $value2) {
                                                        if ($value2['product_id']==$value['id']){                            
                                                            echo '<div class="row">';
                                                                 echo '<div class="col-md-3">';         
                                                                 echo $db_help -> id2name($value2['product_attribute_id'],"product_attribute_id"); 
                                                                 echo '</div>';  
                                                                 echo '<div class="col-md-4">';
                                                                 echo ": <code>"; $db_help -> id2name($value2['product_attribute_option_id'],"product_attribute_option_id");echo "</code>";
                                                                 echo "</div>";
                                                            echo "</div>";  
                                                        }
                                                     }
                                            echo "<br><h4>SUPPLIER</h4>"; 
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "ผู้ผลิต "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     $url=$url_help -> url_update("&sid=".$value['supplier_id']);
                                                     echo ":<code><a href='".$url."#main'>"; $db_help -> id2name($value['supplier_id'],"supplier_id"); echo "</a></code>";
                                                     echo "</div>";
                                                 echo "</div>";   
                                                     echo '<br><small>แก้ไขล่าสุด:'.$value['modified']."</small>";                 
                                             echo '</div>';  
                                         echo '</div><br>';
                                         echo '</div>';
                                    
                                }
                            }
                            /*
                            ////////////////// END PRODUCTS_VIEW //////////////////////
                            */
                break;
            case 'cat':
                            /*
                            ////////////////// CATAGORIES_VIEW //////////////////////
                            */
                            $list_catagories = $db -> select("SELECT product_catagories.name,product_catagories.id, count(product_catagories.id)as count FROM  product INNER JOIN product_catagories on product_catagories_id=product_catagories.id GROUP BY product_catagories.id"); 
                            if ((!isset($_GET['cat'])|| $_GET['cat']==0)){
                                foreach ($list_catagories as $key => $value) {
                                    echo "-<a href='/?act=bl9_01&task=45&cat=".$value['id']."#main'>".$value['name']." (".$value['count'].")</a><br>";
                                }
                            }else
                            {  

                                if (isset($_GET['sid'])&&$_GET['sid']!=0){
                                     $raw = $db -> select("SELECT * FROM  product WHERE product_catagories_id=".$_GET['cat']." && supplier_id=".$_GET['sid']); 
                                 }else{
                                    $raw = $db -> select("SELECT * FROM  product WHERE product_catagories_id=".$_GET['cat'].""); 
                                }  
                            $product_attribute = $db -> select("SELECT * FROM  product_attribute_value WHERE 1");    
                                $i=0;
                                foreach ($raw as $key => $value) {
                                    $url=$url_help -> url_update("&pd=".$value['id']);
                                    $product_img = $db -> select("SELECT file_dir FROM attachment INNER JOIN product ON attachment.record_document = ".$value['id']." WHERE attachment_type_id=2 LIMIT 1");
                                    if ($i%2==0){
                                       echo '<div class="row">';              
                                    }
                                        echo '<div class="col-md-6"><hr>';
                                        echo '<div class="row">';
                                         echo '<div class="col-md-3">';
                                         echo '<div class="row">';
                                             if ($product_img){
                                             echo '<a href="'.$url.'"><img  class="img-responsive" src="'.$product_img[0]['file_dir'].'" alt="'.$value['name'].'"></a>';
                                             }else{
                                             echo '<a href="'.$url.'"><img href="'.$url.'" class="img-responsive" src="/public/upload/default/no_picture.png" ></a>';
                                             }
                                             echo '</div><hr>';
                                             echo '<div class="row">';
                                             echo "สถานะ: ";  $db_help -> id2name($value['product_status_id'],"product_status_id");
                                             echo '</div>';
                                         echo '</div>';
                                         echo '<div class="col-md-9">'; 
                                             echo "<h4>:<code><a href='".$url."#main'>".$value['name']." (".$value['name_english'].")</a></code></h4>"; 
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "ชื่อ: "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo ":<code>".$value['name']." (".$value['name_english'].")</code>";
                                                     echo "</div>";
                                                 echo "</div>";  
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "หมวดหมู่ "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo ":<code><a href='/?act=bl9_01&task=45&cat=".$value['product_catagories_id']."#main'>";$db_help -> id2name($value['product_catagories_id'],"product_catagories_id");
                                                     echo "</a></code></div>";
                                                 echo "</div>"; 
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "barcode "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo ":<code>".$value['barcode']."</code>";
                                                     echo "</div>";
                                                 echo "</div>";
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "อธิบาย(สั่น): "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo "<textarea class='form-control' disabled rows='2' cols='50'>".$value['short_description']."</textarea>";
                                                     echo "</div>";
                                                 echo "</div>";            
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "อธิบาย(ยาว): "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     echo "<textarea class='form-control' disabled rows='3' cols='50'>".$value['long_description']."</textarea>";
                                                     echo "</div>";
                                                 echo "</div>";     
                                            echo "<h4>ATTRIBUTE:</h4>";
                                            //var_dump($product_attribute);
                                                     foreach ($product_attribute as $key2 => $value2) {
                                                        if ($value2['product_id']==$value['id']){                            
                                                            echo '<div class="row">';
                                                                 echo '<div class="col-md-3">';         
                                                                 echo $db_help -> id2name($value2['product_attribute_id'],"product_attribute_id"); 
                                                                 echo '</div>';  
                                                                 echo '<div class="col-md-4">';
                                                                 echo ": <code>"; $db_help -> id2name($value2['product_attribute_option_id'],"product_attribute_option_id");echo "</code>";
                                                                 echo "</div>";
                                                            echo "</div>";  
                                                        }
                                                     }
                                            echo "<br><h4>SUPPLIER</h4>"; 
                                                 echo '<div class="row">';
                                                     echo '<div class="col-md-3">';         
                                                     echo "ผู้ผลิต "; 
                                                     echo '</div>';  
                                                     echo '<div class="col-md-9">';
                                                     $url=$url_help -> url_update("&sid=".$value['supplier_id']);
                                                     echo ":<code><a href='".$url."#main'>"; $db_help -> id2name($value['supplier_id'],"supplier_id"); echo "</a></code>";
                                                     echo "</div>";
                                                 echo "</div>";   
                                                     echo '<br><small>แก้ไขล่าสุด:'.$value['modified']."</small>";                 
                                             echo '</div>';  
                                         echo '</div><br>';
                                         echo '</div>';
                                    
                                    if ($i%2==1){                
                                        echo "</div>";
                                    }
                                    $i++;
                                }
                            }
                            /*
                            ////////////////// END CATAGORIES_VIEW //////////////////////
                            */
                break;
            case 'sid':
                        /*
                        ////////////////// SUPPLIERS_VIEW //////////////////////
                        */                                
                        if (isset($_GET['sid'])&& $_GET['sid']==0){
                            $supplier_list = $db -> select("SELECT supplier.id,supplier.name, count(supplier.id) as count FROM  product INNER JOIN supplier on supplier_id=supplier.id GROUP BY supplier.id"); 
                            if (!isset($_GET['cat'])|| $_GET['cat']==0){
                                foreach ($supplier_list as $key => $value) {
                                    echo "-<a href='/?act=bl9_01&task=45&sid=".$value['id']."#main'>".$value['name']." (".$value['count'].")</a><br>";
                                }
                            }                            
                        }else
                        {
                            $raw = $db -> select("SELECT * FROM  supplier WHERE id=".$_GET['sid']);
                                foreach ($raw as $key => $value) {
                                 $supplier_catagories_list = $db -> select("SELECT product_catagories.name,product_catagories.id FROM product INNER JOIN product_catagories on product_catagories.id = product_catagories_id WHERE supplier_id = ".$value['id']." GROUP BY product_catagories.id");                               
                                 echo '<hr><div class="row">';
                                 echo '<div class="col-md-2">';
                                 echo '<img src="https://viz.tools.investis.com/UPM_CSR/html5dctpro/live/images/level1_icon6.png" alt="Mountain View" style="width:100px;height:100px;">';
                                 echo '</div>';
                                 echo '<div class="col-md-10">'; 
                                     echo "<h4>PROFILE</h4>"; 

                                     echo '<div class="row">';              
                                         echo '<div class="col-md-2">';         
                                         echo "ชื่อ: "; 
                                         echo '</div>';  
                                         echo '<div class="col-md-10">';
                                         echo "<code>".$value['name']." </code>";
                                         echo "</div>";  
                                     echo "</div>";

                                     echo '<div class="row">';              
                                         echo '<div class="col-md-2">';         
                                         echo "ผู้ติดต่อ: "; 
                                         echo '</div>';  
                                         echo '<div class="col-md-10">';
                                         echo "<code>".$value['contact_person']."</code>";
                                         echo "</div>";  
                                     echo "</div>";

                                     echo '<div class="row">';              
                                         echo '<div class="col-md-2">';         
                                         echo "เบอร์โทร: "; 
                                         echo '</div>';  
                                         echo '<div class="col-md-10">';
                                         echo "<code>".$value['phone']."</code>";
                                         echo "</div>";  
                                     echo "</div>";  

                                     echo '<div class="row">';              
                                         echo '<div class="col-md-2">';         
                                         echo "อีเมล: "; 
                                         echo '</div>';  
                                         echo '<div class="col-md-10">';
                                         echo "<code>".$value['email']."</code>";
                                         echo "</div>";  
                                     echo "</div>"; 

                                     echo '<div class="row">';              
                                         echo '<div class="col-md-2">';         
                                         echo "เลขที่บัญชี: "; 
                                         echo '</div>';  
                                         echo '<div class="col-md-10">';
                                         echo "<code>".$value['bank_account']."</code>";
                                         echo "</div>";  
                                     echo "</div>";  
                                     echo '<div class="row">';              
                                         echo '<div class="col-md-2">';         
                                         echo "ที่อยู่: "; 
                                         echo '</div>';  
                                         echo '<div class="col-md-10">';
                                         echo "<code>".$value['city']."<br> ".$value['province']."<br> ".$value['country'].",".$value['post_code']."</code><br>";
                                         echo "</div>";  
                                     echo "</div>"; 
                                     echo '<div class="row">';              
                                         echo '<div class="col-md-2">';         
                                         echo "ประวัติ: "; 
                                         echo '</div>';  
                                         echo '<div class="col-md-10">';
                                          echo "<textarea class='form-control' disabled rows='2' cols='50'>".$value['description']."</textarea><br>";
                                         echo "</div>";  
                                     echo "</div>";                  
                                         echo "หมวดหมู่สินค้า: <br>"; 
                                         foreach ($supplier_catagories_list as $key2 => $value2) {
                                            $supplier_product = $db -> select("SELECT product.name, product.id FROM  product WHERE supplier_id=".$_GET['sid']." && product_catagories_id=".$value2['id']);
                                            if (isset($_GET['cat']) ){
                                                if ($_GET['cat']>0 && $_GET['sid']>0){
                                                    echo '@ <b><a href="?act=bl9_01&task=45&cat='.$value2['id'].'#main">'.$value2['name'].'</a></b><br>';
                                                }
                                            }
                                            else{
                                            $url=$url_help -> url_update("&cat=".$value2['id']);                                            
                                             echo " @ <b><a href='".$url."#main'>".$value2['name']."</a></b><br>";
                                                }
                                             foreach ($supplier_product as $key3 => $value3) {
                                                 echo " - <a href='?act=bl9_01&task=45&pd=".$value3['id']."#main'>".$value3['name']."</a><br>";
                                             }
                                             
                                         }

                                         echo "<br>";   
                                        
                                         echo "<h4>ATTRIBUTE:</h4>";

                                         
                                     echo '<div class="row">';              
                                         echo '<div class="col-md-12">'; 
                                        
                                         echo '</div>';
                                         echo '<small>แก้ไขล่าสุด:'.$value['modified']."</small>";                 
                                     echo '</div>';            
                                 echo '</div>';
                                 echo '</div><br>';
                                }
                        }
                                /*
                                ////////////////// END SUPPLIERS_VIEW //////////////////////
                                */  
                break;
            default:
                echo "<a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&sid=0#main'>-SUPPLIERS</a><br>";
                echo "<a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&cat=0#main'>-CATAGORIES</a><br>";
                echo "<a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&pd=0#main'>-PRODUCTS</a>";
                break;
        }
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
                    echo "<a href='".$keep."#main'> ".$i." </a>";
                } 
                echo "<br><hr>";
                $form_count=0; 
                $first_time_of_row=0;  
                foreach ($count as $key => $value) {   
                    $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner');");
                    $i=0;                
                    foreach ($rows as $key => $value) 
                    {   
                            $skp=explode("-", $_GET['skp']);                                 
                            if (!in_array($value["Field"], $skp)){    
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
                                                unset($temp);
                                                if ($value["Field"]=="agent_id" ){
                                                    $temp['user_id']=$_SESSION['employee_id'];
                                                    $temp['refer_table']='retail_pc';
                                                }
                                                $temp['column']=$value["Field"];
                                                $temp['session']=$title;
                                                $view -> int_handle($temp);
                                                echo "<br><br>";
                                        }else{
                                            echo '<div class="form-group">';
                                            echo '    <label>'.$value["Field"].'</label>';
                                            echo '    <input type="text" class="form-control" name="'.$value["Field"].'" placeholder="">';
                                            echo '</div><hr>';

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