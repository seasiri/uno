<?php
class route
{    
    public function authorization($profile){
        $db = new Db(); 
        $validate = new validate();
        if (isset($profile)){
            $enviroment=route::setup_task_enviroment($profile['employee_id']);       
        }
        return $enviroment;
    }
    public function setup_task_enviroment($user_id){
        $db = new Db(); 
        $validate = new validate();
        $profile = $validate -> lookup_user_profile($user_id);
        $job = $validate -> lookup_working_job($user_id);
        $task = $validate -> lookup_task_from_job($job);     
        $db = $validate -> lookup_prepare_db_name($task); 
        $resource = $validate -> set_db_enviroment($db);
        $enviroment=array();
        //var_dump($job); 
        //var_dump($task); 
        // var_dump($db); 
        //var_dump($resource); 
        //ASSEMBLE JOB
        foreach ($job as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $enviroment['job'][$key2]=$value2;
            }            
        }
        foreach ($task as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $enviroment['task'][$key2]=$value2;
            }            
        }
        foreach ($resource as $key => $value) {
            foreach ($resource[$key] as $key2 => $value2) {
                $enviroment['db'][$key]=$resource[$key];
            }            
        }
        $enviroment['profile']=$profile[0];
        return $enviroment;
    }
    public function construct($user_id){
        //task_type to construct page
    }
}
?>