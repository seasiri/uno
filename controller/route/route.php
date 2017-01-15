<?php
class route
{
    public function authorization($info){
        if (isset($info)){
            route::view($info['employee_id']);
        }
    }
    public function view($user_id){
        $db = new Db(); 
        $validate = new validate();
        //what user's job?
        $user = $db -> select("SELECT job_id, job_log_status_id FROM job_log WHERE employee_id = ".$user_id." ORDER BY id DESC");
        var_dump($user);
        $job = $validate -> lookup('job_id',$user[0]['job_id']);
        $job_log_status = $validate -> lookup('job_log_status_id',$user[0]['job_log_status_id']);
        var_dump($job_log_status);
        //if (isset($user['job_id']) && $job_log_status[0]['name']=="HIRE")
        
    }
}
?>