<?php
require_once $_SERVER['DOCUMENT_ROOT']."/plugin/login/loginheader.php";
class Db {
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() {    
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/private/config.ini'); 
            self::$connection = new mysqli('localhost',$config['username'],$config['password'],$config['dbname']);

        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        mysqli_set_charset(self::$connection, "utf8");
        return self::$connection;
    }

    /**
     * Query the database
     *
     * @param $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query) {
        $validate = New validate();
        //$check -> validate_query();
        // Connect to the database
        $connection = $this -> connect();
        //echo "<pre>";
        //print_r($_POST);
        //echo "</pre>";
        // Query the database
        mysqli_real_escape_string($connection,$query);
        $result = $connection -> query($query);
        //echo $connection -> affected_rows."<br>";         
        if ($result)
        {
            if (strpos($query, 'INSERT') !== false) {
                $table_list=$validate->table_list();              
                 foreach ( $table_list as $key => $value) {
                     if (strpos($query, " ".$value." ") !== false) {
                            $next_id= Db::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'bathline_uno' AND TABLE_NAME = '".$value."'"); 
                            $next_id=$next_id[0]['AUTO_INCREMENT'];
                            $column_head=$validate->column_head('task_log');                       
                            $sql="INSERT INTO task_log (".$column_head.") VALUES (DEFAULT,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'".$_SESSION['employee_id']."','".$value."',".$next_id.",".$_POST['task'].",'".strtoupper($_POST['act'])."','".mysqli_real_escape_string($connection,$query)."',1,'successfully')";  

                            $result = $connection->query($sql);
                        }
                  }  
            }
            if (strpos($query, 'UPDATE') !== false && mysqli_affected_rows($connection) > 0) {  
                $table_list=$validate->table_list();                            
                foreach ( $table_list as $key => $value) {
                    if (strpos($query, " ".$value." ") !== false) {
                            preg_match('/(?<=WHERE id =)\S+/i', $query, $doc_id);   
                            $column_head=$validate->column_head('task_log');                       
                            $sql="INSERT INTO task_log (".$column_head.") VALUES (DEFAULT,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'".$_SESSION['employee_id']."','".$value."',".$doc_id[0].",".$_POST['task'].",'".strtoupper($_POST['act'])."','".mysqli_real_escape_string($connection,$query)."',1,'successfully')";  
                            
                            $result = $connection->query($sql);  
                        }                      
                  }  
            }
            if (strpos($query, 'UPDATE') !== false) {
                
            }
            if (strpos($query, 'DELETE') !== false) {
                
            }
            return $result;
        } 
        else
        {
            return false;
        }
        
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function select($query) {
        $rows = array();
        $result = $this -> query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }    
}
?>