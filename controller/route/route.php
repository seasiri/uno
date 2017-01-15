<?php
class route
{
    public function authorization($profile){
        $db = new Db(); 
        $validate = new validate();
        if (isset($profile)){
            route::task_permission($profile['employee_id']);        }
    }
    public function task_permission($user_id){
        $db = new Db(); 
        $validate = new validate();
        $job = $validate -> lookup_working_job($user_id);
        $task = $validate -> lookup_task_from_job($job);        
    }
    public function construct($user_id){
        //task_type to construct page
    }
}
?>