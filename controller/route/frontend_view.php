<?php
class frontend_view{
    public function template($info,$get){
        if (!empty($get))
        {
            $frontend_view = new frontend_view();
            $view = new view();
            foreach ($info['task'] as $key => $value) {
                if ($info['task'][$key]['id']==$get['task']){
                    echo "<h3>".$info['task'][$key]['name']."</h3><hr>";
                    $task_action_type=$info['task'][$key]['task_action_type'];
                }
            }
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
                            case 'approval':
                                # code...
                                break;
                            case 'pending_approval':
                                # code...
                                break;
                            case 'insert_credential':
                                $frontend_view -> insert_credential($key2);
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
                    if($i==0 && $info['task'][$key2]['job_id']==$info['job'][$key]['job_id']){
                        echo "<a href='/'>@<b>".$info['task'][$key2]['job_name']."</b></a><br>";
                        $i++;
                    }
                    if ($info['task'][$key2]['job_id']==$info['job'][$key]['job_id'])
                    {

                        echo "<a href='?act=".strtolower($info['task'][$key2]['task_action_type'])."&task=".$info['task'][$key2]['id']."'>-- ".$info['task'][$key2]['name']."</a><br>";
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
            return view::error('doc_id_not_found');
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
        public function insert_credential($title){
            $db = new Db();
            $view = new view();
            $rows = $db -> select("SHOW COLUMNS FROM ".$title." WHERE Field NOT IN ('id', 'created','modified','owner');");
            $i=0;
            echo '<form action="/public/action/frontend_submit.php" method="post" >'; 
            echo '<input type="hidden" name="act" value="'.$_GET['act'].'">';
            echo '<input type="hidden" name="form_title" value="'.$title.'">';
            echo '<input type="hidden" name="task" value="'.$_GET['task'].'">';
            
            foreach ($rows as $key => $value) 
            {       
                $temp=array();                     
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
                        if ($value["Field"]=="agent_id"){
                            $temp['user_id']=$_SESSION['employee_id'];
                            $temp['refer_table']='retail_pc';
                        }
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
}
?>