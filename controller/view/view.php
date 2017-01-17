<?php

class view{
    public function display($call,$get){        
        switch ($call) {
            case 'insert':
                echo "<h3><a href=?db=".$_GET['db']."&act=grid>".$_GET['db']."</a>/<small><code>".$_GET['act']."->doc_id</code></small></h3>";
                view::insert($get['db']);
                view::hidden($get);
                echo '</form>';
                break;
            case 'edit':
                echo "<h3>".$_GET['db']."/<small><a href='?db=".$_GET['db']."&act=grid'>grid</a>/<code>".$_GET['act']."->doc_id:".$_GET['doc']."</code></small></h3>";
                view::edit($get['db'],$get['doc']);
                view::hidden($get);
                echo '</form>';
                break;
            case 'grid':
                echo "<h3><a href=?db=".$_GET['db']."&act=insert>+".$_GET['db']."</a>
                /<small>".$_GET['act']."</small></h3>";
                view::grid($get['db']);
                view::hidden($get);
                echo '</form>';
                break; 
            case 'home':                
                break;           
            default:
                view::error('404');
                break;
        }
    }
    public function hidden($get){
        switch ($get['act']) {
            case 'insert':
                echo '<input type="hidden" name="form_title" value="'.$get['db'].'">';
                echo '<input type="hidden" name="act" value="'.$get['act'].'">';
                break;
            case 'edit':
                echo '<input type="hidden" name="form_title" value="'.$get['db'].'">';
                echo '<input type="hidden" name="act" value="'.$get['act'].'">';
                echo '<input type="hidden" name="doc" value="'.$get['doc'].'">';
                break;            
            default:
                # code...
                break;
        }
    }      
    public function insert($title){
        $db = new Db();
        $view = new view();
        $rows = $db -> select("SHOW COLUMNS FROM ".$title);
        $i=0;

        echo '<form action="/public/action/action.php" method="post" enctype="multipart/form-data">'; 
        echo ' <input id="fileToUpload" name="fileToUpload" type="file" class="file" />';
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
        echo '<div class="row"><div class="col-md-10"></div><div class="col-md-2">';
        echo      '<button type="submit" class="btn btn-default">Submit</button>';
        echo '</div></div>';
    }
    public function hierarchy($get){ 
        $db = new Db();
        $view = new view();
        $sql= "SELECT id,name, ".$get['db']."_parent_id FROM ".$get['db']." ";        
        $result = $db -> select($sql); 
        $temp=array();
        
        foreach ($result as $key => $value) {
            $temp[$value['id']]=array();
            foreach ($result as $key2 => $value2) {  
                if ($value2[$get['db'].'_parent_id']!==$value2['id'])  
                {            
                 $temp[$value2[$get['db'].'_parent_id']][$value2['id']]= $value2['name'];
                }
            }
        }
        $temp=array_filter($temp);  
        /*      
        for ($i=0; $i < 10 ; $i++) { 
            foreach ($temp as $key => $value){
                    foreach ($value as $key2 => $value2) {
                        foreach ($temp as $key3 => $value3) {
                           if ($key2==$key3){   
                            $temp[$key][$key2]=$temp[$key3];
                            unset($temp[$key3]);
                            
                           }
                       }

                    }
                }  
        }
       echo '<pre>';
            print_r($temp);
            echo '</pre>';
        function sea($result,$pole){
            foreach ($result as $key => $value) {  
                 sea( ai)            }
            echo '<pre>';
            print_r($result);
            echo '</pre>';
        }
        sea($temp,$temp);     
       */
        
        
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
        $rows = $db -> select("SHOW COLUMNS FROM ".$title);
        $i=0;
        echo '<form action="/public/action/action.php"method="post" enctype="multipart/form-data">'; 
        echo ' <input id="fileToUpload" name="fileToUpload" type="file" class="file" />';
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
    public function error($call){
        switch ($call) {
            case 'doc_id_not_found':
                echo "DOCUMENT NOT FOUND / 807"; 
                break;
            
            default:
                echo "PAGE NOT FOUND / 404"; 
                break;
        }
    }
    public function grid($title){                
        switch ($title) {
            case 'action_permission_type':
                    $show_list= "id,
                                name,
                                description                                                                                                                                                                          
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'action_permission':
                    $show_list= "id,
                                action_permission_type_id,
                                action_permission_parent_id,
                                name,
                                description                                                                                                                                                                           
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'task_status':
                    $show_list= "id,
                                name,
                                description                                                                                                                                                                            
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'task_log_status':
                    $show_list= "id,
                                name,
                                description                                                                                                                                                                            
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'task_log':
                    $show_list= "id,
                                record_name,
                                record_document,
                                task_id,
                                task_action_type                                                                                                                                                                             
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'task_flow':
                    $show_list= "id,
                                task_list,
                                name,
                                description                                                                                                                                              
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'task_action_type':
                    $show_list= "id,
                                name,
                                description                                                                                                                                              
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'task':
                    $show_list= "id,
                                task_parent_id,
                                task_action_type_id,
                                name,
                                task_status_id,
                                job_id                                                                                                                                               
                                ";
                view::grid_make($title,$show_list); 
                break;
            case 'supplier':
                    $show_list= "id,
                                name,
                                country,
                                email,
                                phone                                                                                                                                                 
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'province':
                    $show_list= "id,
                                name,
                                name_english                                                                                                                                                
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'product_status':
                    $show_list= "id,
                                name,
                                description                                                                                                                                                 
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'product_catagories':
                    $show_list= "id,
                                product_catagories_parent_id,
                                name,
                                name_english                                                                                                                                                   
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'product_attribute_value':
                    $show_list= "id,
                                product_id,
                                product_attribute_id,
                                value,
                                unit_thai                                                                                                                                                   
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'product_attribute':
                    $show_list= "id,
                                name,
                                description                                                                                                                                                    
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'product':
                    $show_list= "id,
                                product_catagories_id,
                                product_status_id,
                                name,
                                name_english                                                                                                                      
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'note_type':
                    $show_list= "id,
                                note_type_parent_id,
                                name                                                                                       
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'note':
                    $show_list= "id,
                                title,
                                body,
                                employee_id_from,
                                employee_id_to                                                                                          
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'job':
                    $show_list= "id,
                                job_parent_id,
                                name,
                                name_english,
                                description                                                                                            
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'job_log':
                    $show_list= "id,
                                job_id,
                                employee_id,
                                job_log_status                                                                                          
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'job_log_status':
                    $show_list= "id,
                                name,
                                description                                                                                        
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'attachment_type':
                    $show_list= "id,
                                attachment_type_parent_id,
                                name                                                                                            
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'attachment':
                    $show_list= "id,
                                record_name,
                                record_document,
                                file_dir,
                                file_extension                                
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'employee':
                    $show_list= "id,
                                firstname_thai,
                                lastname_thai,
                                phone,
                                amphur_id,
                                province_id,
                                national_id
                                ";
                view::grid_make($title,$show_list); 
                break; 
            case 'amphur':
                    $show_list= "id,
                                name,
                                name_english
                                ";
                view::grid_make($title,$show_list); 
                break;            
            default:               
                
                break;
        }
        
    }
    public function grid_make($title,$show_list){
        $db = new Db();
        $rows = $db -> select("SELECT ".$show_list." FROM ".$title." "); 
        if ($rows){
            $total_column=count($show_list);  
            echo '<div class="row">'; 
            echo '<table class="table">';
            echo '<thead>';                    
            
                echo '<tr>';
                foreach ($rows[0] as $key => $value) {                        
                    echo '<th>'.$key.'</th>'; 
                }
                echo '<th>act</th>';
                echo '</tr>';
                                
            echo '</thead>';  
            echo '<tbody>';                    
            
            foreach ($rows as $key => $array) {   
                echo "<tr>"; 
                foreach ($array as $key => $value) {                            
                    echo '<td>'.$value.'</td>';
                     
                }      
                    
                echo '<td><a href="?db='.$title.'&act=edit&doc='.$array['id'].'"> edit </a> / <a href="/?db='.$title.'&act=edit"> del </a></td>';
                echo '</tr>'; 
            }   
            echo '</tbody>'; 
            echo '</table>';
            echo '</div>';
        }
    }
    public function dropdown($result,$table){
        if ($result){ 
            echo $table['column']."<br>"; 
            echo '<select name="'.$table['column'].'">';
            if (!   isset($table['doc_id'])){
                echo '<option selected value="null">please select</option>';
            }  $i=0;
                foreach ($result as $key => $value) {
                    if (isset($table['existing'])){
                        if ($value['id']==$table['existing']){
                             echo '<option selected value="'.$value['id'].'">'.$value['name'].'</option>';
                        } 
                        else{
                            echo '<option value="'.$value['id'].'">'.$value['name'].'</option>'; 
                        } 
                    }
                    else{
                        echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                    }
                    $i++;
                } 
            echo '</select>';
        }
        else
        if(strpos($table['column'] , "parent_id")&&$table['count']==0){
            echo '<input type="hidden" name="'.$table['column'].'" value=1>';
            echo 'FIRST ROW';            
        }
        else
        if(strpos($table['column'] , "_id")&&$table['count']==0){
            echo $table['column']."<br>";
            echo "<a href=?db=".$table['relate']."&act=insert>create one</a>"; 
        }
    }
    public function int_handle($table){
        $db = new Db();
        $temp="";
        $table['relate']="";
        if (strpos($table['column'] , "parent_id")) {
            $temp="_parent_id";
            $table['relate'] = substr($table['column'], 0, strrpos($table['column'], $temp));                      
        }else
        if (strpos($table['column'] , "id")) {
            $temp="_id";
            $table['relate'] = substr($table['column'], 0, strrpos($table['column'], $temp));                         
        }
        if (isset($table['doc_id'])){
            $sql="SELECT ".$table['column']." FROM ".$table['session']." WHERE id = ".$table['doc_id'];
            //looking for exisiting dropdown value
            $result = $db -> select($sql);        
            $table['existing']=$result[0][$table['column']];
        }   
        // if no row then create one option
        $result = $db -> select("SELECT count(id) FROM ".$table['relate']);
        $table['count']=$result[0]['count(id)']; 

        switch ($temp) {
            case '_id':
                $sql= "SELECT ".$table['relate'].".id,".$table['relate'].".id,".$table['relate'].".name FROM ".$table['relate']." ";
                $result = $db -> select($sql);  
                view::dropdown($result,$table);
                break;
            case '_parent_id':
                $sql= "SELECT ".$table['relate']."_parent_id,".$table['relate'].".id,".$table['relate'].".name FROM ".$table['session']." ";
                $result = $db -> select($sql);  
                view::dropdown($result,$table);
                break; 
            default:
                if (isset($table['doc_id'])){
                    $sql= "SELECT ".$table['column']." FROM ".$table['session']." WHERE id = ".$table['doc_id']." ";
                    $result = $db -> select($sql);  
                    echo '<div class="form-group">';
                    echo '    <label>'.$table['column'].'</label>';
                    echo '    <input step=0.01  type="number" class="form-control input-sm" name="'.$table['column'].'" value="'.$result[0][$table['column']].'" placeholder="">';
                    echo '</div>'; 
                }
                else{
                    echo '<div class="form-group">';
                    echo '    <label>'.$table['column'].'</label>';
                    echo '    <input step=0.01 type="number" class="form-control input-sm" name="'.$table['column'].'"  placeholder="">';
                    echo '</div>'; 
                }
                break;
        }
        
               
        
    }
}
class attachment{
   public function upload($file,$post){ 
        if ($file['fileToUpload']['size']== 0){
            return 1;
            break;
        }
        $file_name=rand(100000000,999999999);
        $now=date("ymdhi");
        $target_dir = $_SERVER['DOCUMENT_ROOT']."/public/upload/attachment/"; 
        $target_file = $target_dir . basename($file["fileToUpload"]["name"]); 
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $target_file = $target_dir . basename($now."-".$file_name.".".$imageFileType);       
        $uploadOk = 1;
        
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($file["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        $file_size=1048576*5;
        if ($file["fileToUpload"]["size"] > $file_size) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "JPG" && $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "JPEG"
        && $imageFileType != "PNG" && $imageFileType != "png" 
        && $imageFileType != "PDF" && $imageFileType != "pdf"
        ) {
            echo "Sorry, only JPG, PNG and PDF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            return false;
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($file["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $file["fileToUpload"]["name"]). " has been uploaded.";
                $this -> log($target_file,$post);
                return true;
            } else {
                return false;
                echo "Sorry, there was an error uploading your file.";
            }
        }
   }
   public function log($dir,$post){
        $db = new Db();
        $validate = new validate();
        $table_list=$validate->table_list();  
        $extension = pathinfo($dir,PATHINFO_EXTENSION);            
         foreach ( $table_list as $key => $value) {
             if (strpos($post['form_title'], "".$value."") !== false) {
                    $next_id= $db -> select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'bathline_v2' AND TABLE_NAME = '".$value."'"); 
                    $next_id=$next_id[0]['AUTO_INCREMENT'];
                    $column_head=$validate->column_head('attachment');                       
                    $sql="INSERT INTO attachment (".$column_head.") VALUES (DEFAULT,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'".$_SESSION['employee_id']."','".$value."',".$next_id.",1,'".$dir."','".$extension."')";  
                    echo "<br>".$sql."</br>";
                    $result = $db->query($sql);
                }
          }  
        
   }

}
?>
